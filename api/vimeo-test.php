<?php
require __DIR__ . "/bootstrap.php";

$token = $_ENV["VIMEO_TOKEN"] ?? null;
if (!$token) {
  http_response_code(500);
  header("Content-Type: application/json");
  echo json_encode(["error" => "Missing VIMEO_TOKEN in .env"]);
  exit;
}

$ch = curl_init("https://api.vimeo.com/me");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  "Authorization: Bearer $token",
  "Accept: application/vnd.vimeo.*+json;version=3.4"
]);

$response = curl_exec($ch);

if ($response === false) {
  http_response_code(500);
  header("Content-Type: application/json");
  echo json_encode(["error" => curl_error($ch)]);
  exit;
}

$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

http_response_code($code);
header("Content-Type: application/json");
echo $response;
