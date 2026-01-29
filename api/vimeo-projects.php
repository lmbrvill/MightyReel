<?php
require __DIR__ . "/bootstrap.php";

$token = $_ENV["VIMEO_TOKEN"] ?? null;
if (!$token) {
  http_response_code(500);
  header("Content-Type: application/json");
  echo json_encode(["error" => "Missing VIMEO_TOKEN in .env"]);
  exit;
}
$url = "https://api.vimeo.com/me/projects?per_page=50";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  "Authorization: Bearer $token",
  "Accept: application/vnd.vimeo.*+json;version=3.4"
]);

$response = curl_exec($ch);
curl_close($ch);

echo $response;
