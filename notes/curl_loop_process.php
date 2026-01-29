$perPage = 50;          // Vimeo max
$page = 1;
$allVideos = [];

do {
  $url = "https://api.vimeo.com/me/videos"
       . "?per_page={$perPage}"
       . "&page={$page}"
       . "&sort=date"
       . "&direction=desc";

  $ch = curl_init($url);
  curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
      "Authorization: Bearer $token",
      "Accept: application/vnd.vimeo.*+json;version=3.4"
    ]
  ]);

  $response = curl_exec($ch);
  $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);

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

  $allVideos = array_merge($allVideos, $json["data"]);

  $hasNext = !empty($json["paging"]["next"]); // Vimeo sets next to null when done
  $page++;

} while ($hasNext);