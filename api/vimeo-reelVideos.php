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
		$mr_project_id = null;
	//LOOP TO GET MightyReel Folder id
		do {
		//PROJECTS ENDPOINT
			$url = "https://api.vimeo.com/me/projects" 
				. "?per_page={$perPage}"
       			. "&page={$page}"
       			. "&sort=date"
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
		//ERRORS
			if ($response === false) {
				http_response_code(500);
				echo json_encode(["error" => "cURL failed"]);
				exit;
			}
			if ($code < 200 || $code >= 300) {
				http_response_code($code);
				echo $response; // pass through Vimeo error JSON
				exit;
			}
			$json = json_decode($response, true);
			if (!is_array($json) || !isset($json["data"])) {
				http_response_code(500);
				echo json_encode(["error" => "Invalid JSON from Vimeo"]);
				exit;
			}
		//NO ERRORS CONTINUE
			// file_put_contents("interim.json", $response);
			$json = json_decode($response, true);
			for ($d = 0; $d < count($json["data"]); $d++){
				if ($json["data"][$d]['name'] == "MightyReel"){
					$mr_project_id = basename($json["data"][$d]['uri']);
					break;
				}
			}
			// $allVideos = array_merge($allVideos, $json["data"]);
			$morePages = !empty($json["paging"]["next"]); // Vimeo sets next to null when done
			$page++;
		} while ($morePages);
		if (!$mr_project_id){
			echo "ERROR: no directory\n";
			exit;
		} else {
			//get videos
			// echo $mr_project_id . "\n";
			//VARS
				$perPage = 50;          // Vimeo max
				$page = 1;
				$allVideos = [];
				do {
					$url = "https://api.vimeo.com/me/folders/$mr_project_id/videos"
						. "?per_page={$perPage}"
						. "&page={$page}"
						. "&sort=date"
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
						file_put_contents("interim2.json", $response);
						$json = json_decode($response, true);
						$allVideos = array_merge($allVideos, $json["data"]);


					$moreVideos = !empty($json["paging"]["next"]); 
					$page++;
				} while ($moreVideos);
		}
		
	// SHAPE AS VIMEO OBJECT
			$output = [
				"total" => $json["total"],
				"page" => 1,
				"per_page" => $perPage,
				"paging" => null,
				"data" => $allVideos
			];
	//RETURN RESULT
		file_put_contents("output.json", 
			json_encode($output,JSON_PRETTY_PRINT));
		header("Content-Type: application/json");

		echo json_encode(
			$output,
			JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
		);
		
?>
