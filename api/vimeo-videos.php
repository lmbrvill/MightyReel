<?php
require __DIR__ . "/bootstrap.php";

header("Content-Type: application/json");

$token = $_ENV["VIMEO_TOKEN"] ?? null;
if (!$token) {
  http_response_code(500);
  echo json_encode(["error" => "Missing VIMEO_TOKEN in .env"]);
  exit;
}

// query params (optional)
$perPage = isset($_GET["per_page"]) ? (int)$_GET["per_page"] : 10;
$perPage = max(1, min($perPage, 50)); // clamp 1..50 = 
//"get the larger of (1 & (the smaller of $perPage & 50))"

$page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
$page = max(1, $page);

$url = "https://api.vimeo.com/me/videos"
     . "?per_page={$perPage}"
     . "&page={$page}"
     . "&sort=date"
     . "&direction=desc";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  "Authorization: Bearer $token",
  "Accept: application/vnd.vimeo.*+json;version=3.4"
]);
$rArr = array();
$response = curl_exec($ch);
$json1 = json_decode($response,true);
$json2 = json_decode($response,true);
$rArr = array_merge(
    $json1["data"],
    $json2["data"]
);
$output = [
    "total" => $json1["total"],
    "page" => 1,
    "per_page" => count($rArr),
    "paging" => null,
    "data" => $rArr
];


$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if ($response === false) {
  http_response_code(500);
  echo json_encode(["error" => curl_error($ch)]);
  exit;
}

curl_close($ch);

http_response_code($code);
// echo $response;
// print_r($rArr);
// echo json_encode($rArr);
echo json_encode(
    $output,
    JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
);

