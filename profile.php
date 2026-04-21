<?php
session_start();
include "db.php";

// 🔐 Auth check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 🔐 Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php"); // fixed space issue also
    exit();
}

$user_id = $_SESSION['user_id'];

$success = "";
$error = "";

// ✅ Fetch user FIRST
$stmt = $conn->prepare("SELECT name, email, password, created_at FROM users WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();


// ✏️ UPDATE PROFILE (ONLY ONE BLOCK)
if (isset($_POST['update_profile'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];

    $stmt = $conn->prepare("UPDATE users SET name=?, email=? WHERE id=?");
    $stmt->bind_param("ssi", $name, $email, $user_id);

    if ($stmt->execute()) {
        $_SESSION['user_name'] = $name;
        $success = "Profile updated!";

        // update local data
        $user['name'] = $name;
        $user['email'] = $email;
    }
}


// 🔐 CHANGE PASSWORD
if (isset($_POST['change_password'])) {

    $old = $_POST['old_password'];
    $new = $_POST['new_password'];

    if (!password_verify($old, $user['password'])) {
        $error = "Old password incorrect!";
    } 
    elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/', $new)) {
        $error = "New password must be strong!";
    } 
    else {
        $hashed = password_hash($new, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
        $stmt->bind_param("si", $hashed, $user_id);
        $stmt->execute();

        $success = "Password updated successfully!";
    }
}
// 🗑️ DELETE ACCOUNT
if (isset($_POST['delete_account'])) {

    $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    // destroy session
    session_destroy();

    // redirect
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Profile</title>

    <style>
 body {
    margin: 0;
    font-family: "Inter", sans-serif;
    background: #f5f7fb;
}

/* NAVBAR */
.navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 40px;
            background: white;
            border-bottom: 1px solid #e5e7eb;
        }

        .nav-left {
            font-weight: bold;
            font-size: 20px;
        }

        .nav-links a {
            margin: 0 15px;
            text-decoration: none;
            color: #333;
            font-weight: 500;
        }

        .nav-links a:hover {
            color: #2563eb;
        }

        .logout {
            background: red;
            color: white;
            padding: 8px 15px;
            border-radius: 6px;
            text-decoration: none;
        }
        .logo {
    font-size: 24px;
    font-weight: 800;
}

.logo span {
    color: #2563eb;
}

/* MAIN CONTAINER */

.container {
    padding: 40px ;
    
}

/* HEADING */
h1 {
    margin-bottom: 30px;
}

/* SECTION */
.section {
    background: white;
    padding: 25px;
    border-radius: 12px;
    margin-bottom: 20px;
    border: 1px solid #e5e7eb;
}

/* PROFILE TEXT */
.section p {
    margin: 8px 0;
}

/* BUTTON */
.btn {
    margin-top: 10px;
    padding: 10px 16px;
    background: #2563eb;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

.btn:hover {
    background: #1e40af;
}

/* STATS */
.stats {
    display: flex;
    gap: 20px;
    margin-top: 15px;
}

.stat-box {
    flex: 1;
    background: #f9fafb;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    border: 1px solid #e5e7eb;
}

.stat-box h3 {
    font-size: 28px;
    margin-bottom: 5px;
}

/* DANGER */
.btn.danger {
    background: #dc2626;
}

.btn.danger:hover {
    background: #991b1b;
}
 .footer {
    background: #0f172a;
    color: white;
    padding: 60px 80px 20px;
}

.footer-container {
    display: flex;
    justify-content: space-between;
    gap: 40px;
}

/* COLUMNS */
.footer-col {
    width: 25%;
}

.footer-col h3, .footer-col h4 {
    margin-bottom: 15px;
}

.footer-col p {
    font-size: 14px;
    color: #cbd5f5;
}

.footer-col a {
    display: block;
    color: #cbd5f5;
    text-decoration: none;
    margin-bottom: 8px;
    font-size: 14px;
}

.footer-col a:hover {
    color: white;
}

/* COPYRIGHT */
.footer-bottom {
    text-align: center;
    margin-top: 40px;
    padding-top: 20px;
    border-top: 1px solid #1e293b;
    font-size: 14px;
    color: #94a3b8;
}
.hidden {
    display: none;
}
/* EDIT BOX */
.edit-box {
    background: #ffffff;
    padding: 25px;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    margin-top: 15px;
    margin-bottom: 15px;
    max-width: 100%;
}

/* FORM */
.form {
    margin-top: 10px;
}

/* INPUT GROUP */
.input-group {
    margin-bottom: 15px;
}

.input-group label {
    display: block;
    font-size: 14px;
    margin-bottom: 5px;
    color: #374151;
}

.input-group input {
    width: 100%;
    padding: 12px;
    border-radius: 6px;
    border: 1px solid #d1d5db;
    outline: none;
    transition: 0.2s;
}

.input-group input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 2px rgba(37,99,235,0.1);
}

/* BUTTON GROUP */
.btn-group {
    display: flex;
    gap: 10px;
    margin-top: 10px;
}

/* CANCEL BUTTON */
.btn.cancel {
    background: #6b7280;
}
    </style>
</head>
<body>
 <div class="navbar">
    <div class="logo">Resume<span>Now</span></div>
    <div class="nav-links">
        <a href="dashboard.php">Home</a>
        <a href="document.php">Documents</a>
        <a href="profile.php">Profile</a>
    </div>

    <a href="?logout=true" class="logout">Logout</a>
</div>
<div class="container">

   <div class="container">

    <h1>Hello, <?php echo $_SESSION['user_name']; ?> 👋</h1>

    <!-- PROFILE INFO -->
   <div class="section" id="profileView">
        <h2>👤 Profile</h2>

        <p><b>Name:</b> <?php echo $user['name']; ?></p>
        <p><b>Email:</b> <?php echo $user['email']; ?></p>
        <button class="btn" onclick="showEdit()">Edit Profile</button>
    </div>
<div id="editForm" class="edit-box hidden">
    <h3>Edit Profile</h3>

    <form method="POST" class="form">
        <div class="input-group">
            <label>Name</label>
            <input type="text" name="name" value="<?php echo $user['name']; ?>" required>
        </div>

        <div class="input-group">
            <label>Email</label>
            <input type="email" name="email" value="<?php echo $user['email']; ?>" required>
        </div>

        <div class="btn-group">
            <button class="btn" name="update_profile">Save Changes</button>
            <button type="button" class="btn cancel" onclick="goBack()">Cancel</button>
        </div>
    </form>
</div>

    <!-- ACCOUNT STATS -->
    <div class="section">
        <h2>📊 Account Stats</h2>

        <div class="stats">
            <div class="stat-box">
                <h3>0</h3>
                <p>Resumes</p>
            </div>

            <div class="stat-box">
                <h3>0</h3>
                <p>Downloads</p>
            </div>
        </div>
    </div>


    <!-- ACCOUNT INFO -->
    <div class="section">
        <h2>ℹ️ Account Info</h2>

        <p><b>User ID:</b> <?php echo $_SESSION['user_id']; ?></p>
        <p><b>Status:</b> Active</p>
        <p><b>Joined:</b> 
<?php echo date("d M Y", strtotime($user['created_at'])); ?>
</p>
    </div>


    <!-- SECURITY -->
    <div class="section">
        <h2>🔐 Security</h2>

        <button class="btn" onclick="showPassword()">Change Password</button>
    </div>
    <div id="passwordForm" class="edit-box hidden">
    <h3>Change Password</h3>

    <form method="POST" class="form">
        <div class="input-group">
            <label>Old Password</label>
            <input type="password" name="old_password" required>
        </div>

        <div class="input-group">
            <label>New Password</label>
            <input type="password" name="new_password" required>
        </div>

        <div class="btn-group">
            <button class="btn" name="change_password">Update Password</button>
            <button type="button" class="btn cancel" onclick="goBack()">Cancel</button>
        </div>
    </form>
</div>

    <!-- DELETE -->
    <div class="section danger-zone">
    <h2>Delete Account</h2>

    <form method="POST" onsubmit="return confirmDelete()">
        <button type="submit" name="delete_account" class="btn danger">
            Delete Account
        </button>
    </form>
</div>

</div>
</div>

<footer class="footer">

    <div class="footer-container">

        <!-- ABOUT -->
        <div class="footer-col">
            <h3>Resume Builder</h3>
            <p>
                Create professional resumes easily using modern templates 
                and AI-powered tools.
            </p>
        </div>

        <!-- QUICK LINKS -->
        <div class="footer-col">
            <h4>Quick Links</h4>
            <a href="index.php">Home</a>
            <a href="templates.php">Templates</a>
            <a href="builder.php">Builder</a>
            <a href="contact.php">Contact</a>
        </div>

        <!-- FEATURES -->
        <div class="footer-col">
            <h4>Features</h4>
            <a href="#">AI Resume Builder</a>
            <a href="#">ATS Friendly</a>
            <a href="#">Easy Customization</a>
        </div>

        <!-- CONTACT -->
        <div class="footer-col">
            <h4>Contact</h4>
            <p>Email: vasuwadhwa1313@gmail.com</p>
            <p>Mobile: 86992-90622 </p>
        </div>

    </div>

    <!-- COPYRIGHT -->
    <div class="footer-bottom">
        © 2026 Resume Builder | All Rights Reserved
    </div>

</footer>

</body>
</html>
<script>
function showEdit() {
    document.getElementById("profileView").classList.add("hidden");
    document.getElementById("editForm").classList.remove("hidden");
    document.getElementById("passwordForm").classList.add("hidden");
}

function showPassword() {
    document.getElementById("profileView").classList.add("hidden");
    document.getElementById("editForm").classList.add("hidden");
    document.getElementById("passwordForm").classList.remove("hidden");
}

function goBack() {
    document.getElementById("profileView").classList.remove("hidden");
    document.getElementById("editForm").classList.add("hidden");
    document.getElementById("passwordForm").classList.add("hidden");
}
function confirmDelete() {
    return confirm("Are you sure you want to delete your account? This action cannot be undone!");
}
</script>