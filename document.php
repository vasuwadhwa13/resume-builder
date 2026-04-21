<?php
session_start();
$conn = new mysqli("localhost", "root", "", "resume_builder");

// ✅ check login
if (!isset($_SESSION['user_id'])) {
    die("⚠️ Please login first");
}

// ✅ dynamic user id
$user_id = $_SESSION['user_id'];

// fetch resumes
$result = $conn->query("SELECT * FROM resumes WHERE user_id = $user_id ORDER BY id DESC");
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>My Documents</title>

<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: #f1f5f9;
    margin: 0;
}

/* NAV */
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
.container {
    padding: 40px 60px;
}

h2 {
    margin-bottom: 20px;
}

/* CARD GRID */
.grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 20px;
}

/* CARD */
.card {
    background: white;
    padding: 20px;
    border-radius: 12px;
    cursor: pointer;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    transition: 0.2s;
}

.card:hover {
    transform: translateY(-5px);
}

.card h3 {
    margin: 0;
    font-size: 18px;
}

.card p {
    font-size: 13px;
    color: #666;
}

/* EMPTY */
.empty {
    background: white;
    padding: 30px;
    border-radius: 10px;
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

<h2>Your Documents</h2>

<div class="grid">

<?php if ($result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>

        <div class="card" onclick="openResume(<?= $row['id'] ?>, '<?= $row['template'] ?>')">
            <h3><?= $row['name'] ?: 'Untitled Resume' ?></h3>
            <p><?= $row['email'] ?></p>
            <p>Template: <?= $row['template'] ?></p>
        </div>

    <?php endwhile; ?>
<?php else: ?>

    <div class="empty">
        <p>No resumes found 😔</p>
    </div>

<?php endif; ?>

</div>
</div>

<script>
function openResume(id, template) {
    window.location.href = `builder.php?id=${id}&template=${template}`;
}
</script>
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