<?php
session_start();
include "db.php";

// 🔐 If already logged in → redirect
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = "";
$success = "";

// ================= REGISTER =================
if (isset($_POST['register'])) {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password_raw = $_POST['password'];

    // 🔐 Password validation
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/', $password_raw)) {
        $error = "Password must be 8+ chars, include uppercase, lowercase, number & special character!";
    } else {

        $password = password_hash($password_raw, PASSWORD_DEFAULT);

        // 🔐 Prepared statement (SAFE)
        $stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $check = $stmt->get_result();

        if ($check->num_rows > 0) {
            $error = "User already exists!";
        } else {
            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $password);
            $stmt->execute();

            $success = "Registered successfully! Please login.";
        }
    }
}

// ================= LOGIN =================
if (isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // 🔐 Prepared statement
    $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

       if (password_verify($password, $user['password'])) {

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];

    // 🔥 builder se aaye ho kya?
    if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'builder.php') !== false) {
        header("Location: " . $_SERVER['HTTP_REFERER']);
    } else {
        header("Location: dashboard.php");
    }

    exit();
}
 else {
            $error = "Wrong password!";
        }
    } else {
        $error = "User not found!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login / Register</title>

    <style>
        body {
    margin: 0;
    font-family: "Inter", sans-serif;
    background: #f5f7fb; /* light professional background */
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Card improvement */
.card {
    background: #ffffff;
    width: 360px;
    padding: 35px;
    border-radius: 12px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    text-align: center;
    border: 1px solid #e5e7eb;
}

/* Tabs */
.tabs button {
    flex: 1;
    padding: 10px;
    border: none;
    background: #f1f5f9;
    cursor: pointer;
    font-weight: 600;
}

.tabs button.active {
    background: #2563eb; /* professional blue */
    color: white;
    border-radius: 6px;
}

/* Inputs */
input {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border-radius: 6px;
    border: 1px solid #d1d5db;
    outline: none;
}

input:focus {
    border-color: #2563eb;
}

/* Button */
.btn {
    width: 100%;
    padding: 12px;
    background: #2563eb;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
}

.btn:hover {
    background: #1e40af;
}

/* Messages */
.error {
    color: #dc2626;
}

.success {
    color: #16a34a;
}

/* Welcome screen */
.welcome {
    background: white;
    padding: 30px;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    text-align: center;
}

.logout-btn {
    display: inline-block;
    margin-top: 15px;
    padding: 10px 20px;
    background: #dc2626;
    color: white;
    text-decoration: none;
    border-radius: 6px;
}
.form {
    display: none;
    animation: fade 0.2s ease;
}

@keyframes fade {
    from { opacity: 0; }
    to { opacity: 1; }
}

.form.active {
    display: block;
}
    </style>
</head>
<body>

<?php if(isset($_SESSION['user_id'])): ?>  
    <!-- AUTHORIZED USER -->
    <div class="welcome">
        <h2>Welcome <?php echo $_SESSION['user']; ?> 🎉</h2>
        <p>You are logged in (Authorized)</p>
        <a href="?logout=true" class="logout-btn">Logout</a>
    </div>

<?php else: ?>

<div class="card">

    <div class="tabs">
        <button id="loginTab" class="active">Login</button>
        <button id="registerTab">Register</button>
    </div>

    <?php if($error) echo "<p class='error'>$error</p>"; ?>
    <?php if($success) echo "<p class='success'>$success</p>"; ?>

    <!-- LOGIN FORM -->
    <form method="POST" id="loginForm" class="form active">
        <h2>Welcome Back 👋</h2>
        <input type="email" name="email" placeholder="Enter Email" required>
        <input type="password" name="password" placeholder="Enter Password" required>
        <button class="btn" name="login">Login</button>
    </form>

    <!-- REGISTER FORM -->
    <form method="POST" id="registerForm" class="form">
        <h2>Create Account 🚀</h2>
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Enter Email" required>
        <input 
  type="password" 
  name="password" 
  placeholder="Create Password"
  pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$"
  title="Password must be 8+ chars, include uppercase, lowercase, number & special character"
  required
>
        <button class="btn" name="register">Register</button>
    </form>

</div>

<?php endif; ?>

<script>
   const loginTab = document.getElementById("loginTab");
const registerTab = document.getElementById("registerTab");

const loginForm = document.getElementById("loginForm");
const registerForm = document.getElementById("registerForm");

loginTab.addEventListener("click", () => {
    loginTab.classList.add("active");
    registerTab.classList.remove("active");

    loginForm.classList.add("active");
    registerForm.classList.remove("active");
});

registerTab.addEventListener("click", () => {
    registerTab.classList.add("active");
    loginTab.classList.remove("active");

    registerForm.classList.add("active");
    loginForm.classList.remove("active");
});
</script>

</body>
</html>