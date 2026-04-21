<?php
header("Content-Type: application/json");

// 🔥 get input data
$data = json_decode(file_get_contents("php://input"), true);

$resume = $data['resume'] ?? '';
$job = $data['job'] ?? '';

// ❌ check empty input
if (empty(trim($resume)) || empty(trim($job))) {
    echo json_encode([
        "result" => "Resume or Job description missing"
    ]);
    exit;
}

// 🔑 YOUR OPENROUTER API KEY (IMPORTANT)
$apiKey = "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"; // 👈 PUT YOUR REAL KEY HERE

// 🌐 API URL
$url = "https://openrouter.ai/api/v1/chat/completions";

// 📦 request body
$postData = [
    "model" => "openai/gpt-3.5-turbo", // ✅ stable model
    "messages" => [
        [
            "role" => "user",
            "content" => "Analyze this resume for the given job.\n\nGive:\n1. ATS score out of 100\n2. 4-5 improvements (bullet points)\n\nResume:\n$resume\n\nJob:\n$job"
        ]
    ]
];

// 🔌 init curl
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

// 🚀 execute request
$response = curl_exec($ch);

// ❌ curl error check
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

// ✅ extract AI content
$content = $result['choices'][0]['message']['content'] ?? null;

// ❌ no content
if (!$content) {
    echo json_encode([
        "result" => "No response from AI",
        "debug" => $result
    ]);
    exit;
}

// ✅ success output
echo json_encode([
    "result" => trim($content)
]);
?>