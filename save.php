<?php
header("Content-Type: application/json");
session_start();

// ❌ अगर login nahi hai
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "status" => "error",
        "msg" => "User not logged in"
    ]);
    exit;
}

// ✅ logged in user
$user_id = $_SESSION['user_id'];

// DB CONNECTION
$conn = new mysqli("localhost", "root", "", "resume_builder");

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "msg" => "DB Connection Failed"]);
    exit;
}

// GET JSON DATA
$data = json_decode(file_get_contents("php://input"), true);

// SAFE VARIABLES
$name = $conn->real_escape_string($data['name'] ?? '');
$email = $conn->real_escape_string($data['email'] ?? '');
$summary = $conn->real_escape_string($data['summary'] ?? '');

$skills = isset($data['skills']) ? implode(",", $data['skills']) : '';
$experience = isset($data['experience']) ? implode("|", $data['experience']) : '';
$extra = isset($data['extra']) ? implode("|", $data['extra']) : '';

$template = $conn->real_escape_string($data['template'] ?? 'template1');

// 🔥 dynamic sections JSON
$dynamic = isset($data['dynamicSections']) 
    ? $conn->real_escape_string(json_encode($data['dynamicSections'])) 
    : '[]';

// INSERT QUERY (🔥 user_id added)
$sql = "INSERT INTO resumes 
(user_id, name, email, summary, skills, experience, extra, dynamic_sections, template)
VALUES 
('$user_id','$name','$email','$summary','$skills','$experience','$extra','$dynamic','$template')";

if ($conn->query($sql)) {
    echo json_encode(["status" => "ok"]);
} else {
    echo json_encode(["status" => "error", "msg" => $conn->error]);
}

$conn->close();
?>