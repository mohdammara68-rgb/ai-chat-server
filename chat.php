<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
  http_response_code(200);
  exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$message = $data["message"] ?? "";

if ($message === "") {
  echo json_encode(["reply" => "Mesej kosong"]);
  exit;
}

$API_KEY = "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";

$payload = [
  "model" => "gpt-3.5-turbo",
  "messages" => [
    ["role" => "system", "content" => "Jawab dalam Bahasa Melayu"],
    ["role" => "user", "content" => $message]
  ]
];

$ch = curl_init("https://api.openai.com/v1/chat/completions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  "Content-Type: application/json",
  "Authorization: Bearer $API_KEY"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);
$reply = $result["choices"][0]["message"]["content"] ?? "Tiada jawapan";

echo json_encode(["reply" => $reply]);
