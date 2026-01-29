<?php
	//VIMEO SETUP
		require __DIR__ . "/bootstrap.php";
		//ENSURE VALID TOKEN
			$token = $_ENV["VIMEO_TOKEN"] ?? null;
			if (!$token) {
				http_response_code(500);
				header("Content-Type: application/json");
				echo json_encode(["error" => "Missing VIMEO_TOKEN in .env"]);
				exit;
			}
	//VARS
		$perPage = 50;          // Vimeo max
		$page = 1;
		$allVideos = [];
		$arr = array();
	// GET https://api.vimeo.com/me/videos?per_page=50&page=1&fields=uri,name
		do {
			$url = "https://api.vimeo.com/me/videos"
				. "?per_page={$perPage}"
				. "&page={$page}"
				. "&sort=date"
				. "&fields=uri,name,pictures.sizes,tags"
				. "&direction=desc";
			//CURL
		
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HTTPHEADER, [
					"Authorization: Bearer $token",
					"Accept: application/vnd.vimeo.*+json;version=3.4"
				]);
				$response = curl_exec($ch);
				$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				curl_close($ch);
				
				$json = json_decode($response, true);
				$allVideos = array_merge($allVideos, $json["data"]);


			$moreVideos = !empty($json["paging"]["next"]); 
			$page++;
		} while ($moreVideos);


// SHAPE AS VIMEO OBJECT
	$output = [
		"total" => $json["total"],
		"page" => 1,
		"per_page" => $perPage,
		"paging" => null,
		"data" => $allVideos
	];
	//RETURN RESULT
		// file_put_contents("output.json", 
		// 	json_encode($output,JSON_PRETTY_PRINT));
		header("Content-Type: application/json");

		echo json_encode(
			$output,
			JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
		);
		
?>
