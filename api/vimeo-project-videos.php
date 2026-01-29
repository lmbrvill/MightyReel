<?php
require __DIR__ . "/bootstrap.php";

header("Content-Type: application/json");

$token = $_ENV["VIMEO_TOKEN"] ?? null;
if (!$token) {
  http_response_code(500);
  echo json_encode(["error" => "Missing VIMEO_TOKEN"]);
  exit;
}

$projectId = $_GET["project_id"] ?? null;
if (!$projectId) {
  http_response_code(400);
  echo json_encode(["error" => "Missing project_id"]);
  exit;
}

$page = isset($_GET["page"]) ? (int)$_GET["page"] : 1;
$perPage = isset($_GET["per_page"]) ? (int)$_GET["per_page"] : 50;
$perPage = max(1, min($perPage, 100));

$url =
  "https://api.vimeo.com/projects/$projectId/videos" .
  "?page=$page" .
  "&per_page=$perPage" .
  "&sort=date" .
  "&direction=desc";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  "Authorization: Bearer $token",
  "Accept: application/vnd.vimeo.*+json;version=3.4"
]);

$response = curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if ($response === false) {
  http_response_code(500);
  echo json_encode(["error" => curl_error($ch)]);
  exit;
}

curl_close($ch);

http_response_code($code);
echo $response;
