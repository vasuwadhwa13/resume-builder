<?php

$apiKey = "xai-Of27R7Zf39C5KKXf9e075Z3VngFiqwGkQW2ddUXzHG4nAlcgtLjqtGytD7F6Ujt5YpeIZxDQHcbebIAK"; // 🔥 apni key

$url = "https://openrouter.ai/api/v1/chat/completions";

$data = [
    "model" => "stepfun/step-3.5-flash:free",
    "messages" => [
        ["role" => "user", "content" => "Say hello"]
    ]
];

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $apiKey"
]);

$response = curl_exec($ch);

echo $response;