<?php
header("Content-Type: application/json");

// 🔥 get input
$data = json_decode(file_get_contents("php://input"), true);
$role = $data['role'] ?? '';

if (empty(trim($role))) {
    echo json_encode(["result" => "No role provided"]);
    exit;
}

// 🔑 API KEY
$apiKey = "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"; // 👈 replace

// 🌐 API URL
$url = "https://openrouter.ai/api/v1/chat/completions";

// 📦 request data
$postData = [
    "model" => "openai/gpt-3.5-turbo", // ✅ stable
    "messages" => [
        [
            "role" => "user",
            "content" => "Give 8-10 professional resume skills for $role in ONE LINE, comma separated only. No explanation."
        ]
    ]
];

// 🔌 CURL INIT
$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

// 🔐 headers
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $apiKey"
]);

// 🚀 execute
$response = curl_exec($ch);

// ❌ curl error
if (curl_errno($ch)) {
    echo json_encode([
        "result" => "Curl error: " . curl_error($ch)
    ]);
    curl_close($ch);
    exit;
}

curl_close($ch);

// 🔍 decode response
$result = json_decode($response, true);

// ❌ invalid response
if (!$result) {
    echo json_encode([
        "result" => "API response empty",
        "raw" => $response
    ]);
    exit;
}

// ❌ API error
if (isset($result['error'])) {
    echo json_encode([
        "result" => "API Error: " . $result['error']['message']
    ]);
    exit;
}

// ✅ extract safely
$content = $result['choices'][0]['message']['content'] ?? null;

// ❌ no content
if (!$content) {
    echo json_encode([
        "result" => "No response from AI",
        "debug" => $result
    ]);
    exit;
}

// ✅ success
echo json_encode([
    "result" => trim($content)
]);
?>