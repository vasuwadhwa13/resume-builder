<?php
session_start();
include "db.php";
// ✅ Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

// ✅ Auth check
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// 🔥 count resumes
$countResult = $conn->query("SELECT COUNT(*) as total FROM resumes WHERE user_id = $user_id");
$countRow = $countResult->fetch_assoc();
$totalResumes = $countRow['total'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>

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

        /* MAIN */
        .container {
            padding: 40px;
        }

        h1 {
            margin-bottom: 20px;
        }

        /* CARD */
        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            margin-top: 20px;
        }

        .btn {
            background: #2563eb;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .btn:hover {
            background: #1e40af;
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
.actions {
    display: flex;
    gap: 15px;
    margin-top: 15px;
}

.action-box {
    flex: 1;
    background: #f1f5f9;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    cursor: pointer;
    border: 1px solid #e5e7eb;
    transition: 0.2s;
}

.action-box:hover {
    background: #e0e7ff;
    border: 1px solid #2563eb;
}
.action-link {
    text-decoration: none;
    color: inherit;
    flex: 1;
}
.logo {
    font-size: 24px;
    font-weight: 800;
}

.logo span {
    color: #2563eb;
}
    </style>
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="logo">Resume<span>Now</span></div>
    <div class="nav-links">
        <a href="dashboard.php">Home</a>
        <a href="document.php">Documents</a>
        <a href="profile.php">Profile</a>
    </div>

    <a href="?logout=true" class="logout">Logout</a>
</div>

<!-- MAIN CONTENT -->
<div class="container">
    <h1>Hello, <?php echo $_SESSION['user_name']; ?> 👋</h1>

    <div class="card">
        <h2>Your Documents</h2>
       <p>You have <?php echo $totalResumes; ?> resume<?php echo $totalResumes != 1 ? 's' : ''; ?>.</p>
        <button class="btn" onclick="window.location.href='templates.php'">
    + Add Resume
</button>
    </div>

   <div class="card">
    <h2>Quick Actions</h2>

    <div class="actions">

        <a href="templates.php" class="action-link">
            <div class="action-box">
                📝 <p>Create Resume</p>
            </div>
        </a>

        <a href="document.php" class="action-link">
            <div class="action-box">
                📄 <p>My Resumes</p>
            </div>
        </a>

        <a href="profile.php" class="action-link">
            <div class="action-box">
                ✏️ <p>Edit Profile</p>
            </div>
        </a>

       

    </div>
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