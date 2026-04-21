<?php
// You can later add backend logic here
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Resume Builder</title>

    <style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Inter", sans-serif;
}

body::before {
    content: "";
    position: fixed;
    top: -200px;
    left: -200px;
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, #c7d2fe, transparent 70%);
    z-index: -1;
}

/* NAVBAR */
.navbar {
    display: flex;
    justify-content: space-between;
    padding: 20px 60px;
    background: white;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
     position: sticky;
    top: 0;
    z-index: 1000;
    background: white;
}

.logo {
    font-size: 24px;
    font-weight: 800;
}

.logo span {
    color: #2563eb;
}

.nav-links a {
    margin-left: 25px;
    text-decoration: none;
    color: #444;
    font-weight: 500;
}

/* HERO */
.hero {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 100px 80px;
}

/* LEFT SIDE */
.hero-left {
    width: 50%;
}

.hero-left h1 {
    font-size: 56px;
    font-weight: 900;
    line-height: 1.2;
    margin-bottom: 20px;
}

.hero-left p {
    font-size: 18px;
    color: #6b7280;
    margin-bottom: 30px;
    max-width: 500px;
}

/* BUTTONS */
.buttons {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
}
.btn {
    display: inline-block;
    padding: 14px 28px;
    border-radius: 30px;
    background: linear-gradient(135deg, #4f46e5, #2563eb);
    color: white;
    text-decoration: none;
    font-weight: 600;
}

.primary {
    background: linear-gradient(135deg, #2563eb, #4f46e5);
    color: white;
    box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
}

.primary:hover {
    transform: translateY(-2px);
}

.secondary {
    border: 1px solid #ddd;
    color: #333;
    background: white;
}

.secondary:hover {
    background: #f1f5f9;
}

/* RIGHT SIDE */
.hero-right {
    width: 45%;
}

/* CARD EFFECT */
.resume-card {
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(10px);
    padding: 20px;
    border-radius: 20px;
    box-shadow: 0 20px 50px rgba(0,0,0,0.1);
    transition: 0.3s;
}

.resume-card:hover {
    transform: scale(1.02);
}

.resume-card img {
    width: 100%;
    border-radius: 10px;
}

/* RATING */
.rating {
    font-size: 14px;
    color: #16a34a;
    font-weight: 500;
}
/* CTA SECTION */
.cta-section {
    padding: 120px 80px;
    background: #ffffff;
}

/* CONTENT ALIGNMENT */
.cta-content {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 250px;
    max-width: 1000px;
    margin: 0 auto;
}

/* HEADING */
.cta-content h2 {
    font-size: 42px;
    font-weight: 800;
}

.cta-content span {
    border-bottom: 5px solid #22c55e;
}

/* BUTTON */
.choose-btn {
    background: linear-gradient(135deg, #2563eb, #4f46e5);
    color: white;
    padding: 14px 30px;
    border-radius: 30px;
    text-decoration: none;
    font-weight: 600;
    box-shadow: 0 10px 25px rgba(37, 99, 235, 0.3);
    transition: 0.3s;
}

.choose-btn:hover {
    transform: translateY(-2px);
}
.cta-section {
    width: 100%;
    display: block;
    clear: both;
    padding: 120px 80px;
    background: linear-gradient(to right, #f8fafc, #eef2ff);
    margin-top: 10px;
}
/* FEATURES ROW */
.features-row {
    display: flex;
    justify-content: space-between;
    margin-top: 80px;
    gap: 40px;
}

/* EACH BOX */
.feature {
    width: 30%;
}

/* ICON STYLE */
.icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    margin-bottom: 15px;
}

/* COLORS */
.green {
    background: #bbf7d0;
}

.blue {
    background: #c7d2fe;
}

.yellow {
    background: #fde68a;
}

/* TEXT */
.feature h3 {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 10px;
}

.feature p {
    font-size: 15px;
    color: #6b7280;
    line-height: 1.6;
}
/* FOOTER */
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

<!-- NAVBAR -->
<header class="navbar">
    <div class="logo">Resume<span>Now</span></div>
    <div class="nav-links">
        <a href="#">Contact Us</a>
        <a href="login.php">Login</a>
    </div>
</header>

<!-- HERO SECTION -->
<section class="hero">

    <div class="hero-left">
        <h1>India's Top <br> Resume Templates</h1>

        <p>
            Get the job 2x as fast. Use recruiter-approved templates and
            step-by-step content recommendations to create a new resume
            or optimize your current one.
        </p>

        <div class="buttons">
           <div class="buttons">
  <a href="templates.php" class="btn primary">Create new resume</a>
</div>
        </div>

        <div class="rating">
            ⭐ 4.5 out of 5 based on 16,000+ reviews
        </div>
    </div>

    <div class="hero-right">
        <div class="resume-card">
            <!-- Put your image in same folder -->
            <img src="images/mainpage.png" alt="Resume Preview">
        </div>
    </div>
</section>  <!-- HERO END -->

<section class="cta-section">

    <div class="cta-content">
        <h2>
            Create a resume  <br>
            that <span>gets results</span>
        </h2>

        <a href="templates.php" class="choose-btn">Choose a template</a>
    </div>

    <!-- FEATURES BELOW -->
    <div class="features-row">

        <div class="feature">
            <div class="icon green">📄</div>
            <h3>Recruiter-Approved Resume</h3>
            <p>
                We work with recruiters to design resume templates that format automatically.
            </p>
        </div>

        <div class="feature">
            <div class="icon blue">⏱️</div>
            <h3>Finish Your Resume in 15 Minutes using AI</h3>
            <p>
                Resume Now helps you tackle your work experience by reminding you what you did at your job.
            </p>
        </div>

        <div class="feature">
            <div class="icon yellow">💼</div>
            <h3>Land an Interview</h3>
            <p>
                We suggest the skills you should add. It helped over a million people get interviews.
            </p>
        </div>

    </div>

</section>
</section>
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