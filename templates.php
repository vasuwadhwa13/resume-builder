<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Templates</title>

<style>
body {
    margin: 0;
    font-family: "Inter", sans-serif;
    background: linear-gradient(#ffffff, #cfe7ff);
}

/* HEADER */
.header {
    padding: 20px 40px;
    font-weight: bold;
}

/* HERO */
.hero {
    text-align: center;
    margin-top: 20px;
}

.hero h1 {
    font-size: 32px;
}

.hero p {
    color: gray;
}

/* FILTER BAR */
.filter-bar {
    display: flex;
    align-items: center;
    gap: 15px;
    background: #f3f4f6;
    padding: 15px 20px;
    border-radius: 12px;
    width: fit-content;
    margin: 30px auto;
    box-shadow: 0 10px 20px rgba(0,0,0,0.08);
}

.filter-bar select {
    padding: 8px;
    border-radius: 8px;
}

/* COLORS */
.colors {
    display: flex;
    gap: 10px;
}

.color {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    cursor: pointer;
}

.color.active {
    border: 3px solid white;
    outline: 2px solid black;
}

/* COUNT */
.count {
    margin-left: 60px;
    color: #555;
}

/* GRID */
.templates {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 25px;
    padding: 40px;
}

/* CARD */
.card {
    background: white;
    padding: 15px;
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    position: relative;
    transition: 0.3s;
}

.card img {
    width: 100%;
    border-radius: 10px;
}

/* ACTIVE */
.card.active {
    border: 4px solid #2563eb;
}

/* BUTTON */
.card button {
    position: absolute;
    bottom: 15px;
    left: 50%;
    transform: translateX(-50%);
    padding: 12px 25px;
    border-radius: 25px;
    border: none;
    background: #2563eb;
    color: white;
    cursor: pointer;
}

/* HOVER */
.card:hover {
    transform: translateY(-5px);
}
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
</style>

</head>

<body>

<header class="navbar">
    <div class="logo">Resume<span>Now</span></div>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="login.php">Login</a>
    </div>
</header>

<section class="hero">
    <h1>Templates we recommend for you</h1>
    <p>You can always change your template later.</p>
</section>

<p class="count">All templates (6)</p>

<div class="templates">

    <div class="card active">
        <img src="images/template1.png">
        <button onclick="selectTemplate(event,'template1')">Choose template</button>
    </div>

    <div class="card">
        <img src="images/template2.png">
        <button onclick="selectTemplate(event,'template2')">Choose template</button>
    </div>

    <div class="card">
        <img src="images/template3.png">
        <button onclick="selectTemplate(event,'template3')">Choose template</button>
    </div>
    <div class="card">
        <img src="images/template4.png">
        <button onclick="selectTemplate(event,'template4')">Choose template</button>
    </div>
    <div class="card">
        <img src="images/template5.jpg">
        <button onclick="selectTemplate(event,'template5')">Choose template</button>
    </div>
    <div class="card">
        <img src="images/template6.jpg">
        <button onclick="selectTemplate(event,'template6')">Choose template</button>
    </div>

</div>

<script>
function selectTemplate(event, template) {

    // remove old active
    document.querySelectorAll(".card").forEach(c => c.classList.remove("active"));

    // add active to clicked
    event.target.closest(".card").classList.add("active");

    // store
    localStorage.setItem("selectedTemplate", template);

    // redirect
    window.location.href = "builder.php?template=" + template;
}

// color selection
document.querySelectorAll(".color").forEach(color => {
    color.addEventListener("click", function() {

        document.querySelectorAll(".color").forEach(c => c.classList.remove("active"));
        this.classList.add("active");

        let selectedColor = this.style.background;
        localStorage.setItem("selectedColor", selectedColor);
    });
});
</script>

</body>
</html>