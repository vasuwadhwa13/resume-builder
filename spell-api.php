<?php

// ✅ check if text exists
if (!isset($_POST['text']) || empty(trim($_POST['text']))) {
    echo json_encode([
        "matches" => []
    ]);
    exit;
}

$text = $_POST['text'];

// ✅ LanguageTool API URL
$url = "https://api.languagetool.org/v2/check";

// ✅ Prepare data
$data = http_build_query([
    'text' => $text,
    'language' => 'en-US'
]);

// ✅ Use cURL (better than file_get_contents)
$ch = curl_init($url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/x-www-form-urlencoded"
]);

$response = curl_exec($ch);

if (!$response) {
    echo json_encode([
        "matches" => [],
        "error" => "API failed"
    ]);
    exit;
}

// ❌ error handling
if (curl_errno($ch)) {
    echo json_encode([
        "error" => "API request failed",
        "details" => curl_error($ch)
    ]);
    curl_close($ch);
    exit;
}

curl_close($ch);

// ✅ decode + clean response
$result = json_decode($response, true);

// ✅ ensure matches exist
if (!isset($result['matches'])) {
    echo json_encode([
        "matches" => []
    ]);
    exit;
}

// ✅ return clean response
echo json_encode([
    "matches" => $result['matches']
]);