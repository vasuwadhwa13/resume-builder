<?php
session_start();
require "db.php";

$resumeData = null;

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    $res = $conn->query("SELECT * FROM resumes WHERE id = $id");

    if ($res && $res->num_rows > 0) {
        $resumeData = $res->fetch_assoc();
    }
}
// 🔥 check login
$isLoggedIn = isset($_SESSION['user_id']);

// template from URL
$template = $_GET['template'] ?? 'template1';
?>
<script src="js/ai.js"></script>
<!DOCTYPE html>
<html>
<head>
<title>Resume Builder</title>
<link rel="stylesheet" href="spell.css">
<style>
body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: #f1f5f9;
}

.container {
    display: flex;
    height: 100vh;
}

/* LEFT */
.left {
    width: 20%;
    background: #0f172a;
    color: white;
    padding: 20px;
    overflow-y: auto;
}

.template-card {
    margin-bottom: 15px;
    cursor: pointer;
    border-radius: 10px;
    overflow: hidden;
}

.template-card img {
    width: 100%;
}

.template-card.active {
    border: 3px solid #22c55e;
}

/* CENTER */
.center {
    width: 50%;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    padding: 30px;
    overflow-y: auto;
}

.resume {
    width: 450px;
    min-height: 1100px;
    background: white;
    border-radius: 12px;
    padding: 40px;
}

/* RIGHT */
.right {
    width: 30%;
    padding: 20px;
    overflow-y: auto;
}

input, textarea {
    width: 100%;
    margin-bottom: 10px;
    padding: 10px;
}

/* COMMON */
h3 {
    color: #2563eb;
    border-bottom: 2px solid #2563eb;
}

/* TEMPLATE 1 */
.content {
    display: flex;
    gap: 40px;
}
.left-sec { width: 65%; }
.right-sec { width: 35%; }

/* TEMPLATE 2 */
.grid2 {
    display: flex;
    gap: 30px;
}
.left2 { width: 65%; }
.right2 { width: 35%; }

.right2 li {
    background: none;
    color: #222;
}

/* TEMPLATE 3 */
.modern3 {
    display: flex;
}

.left3 {
    width: 35%;
    background: #0f172a;
    color: white;
    padding: 20px;
}

.right3 {
    width: 65%;
    padding: 25px;
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
.btn {
    padding: 10px 14px;
    margin: 6px 6px 6px 0;
    border-radius: 6px;
    border: none;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    min-width: 150px; /* 👈 same size */
}

/* Add Section */
.btn.add {
    background: #e5e7eb;
    color: #111;
}
.btn.add:hover {
    background: #d1d5db;
}

/* Update */
.btn.update {
    background: #2563eb;
    color: white;
}
.btn.update:hover {
    background: #1e40af;
}

/* Save */
.btn.save {
    background: #16a34a;
    color: white;
}
.btn.save:hover {
    background: #15803d;
}

/* Download */
.btn.download {
    background: #6b7280;
    color: white;
}
.btn.download:hover {
    background: #4b5563;
}

</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
</head>

<body>
<!-- NAVBAR -->
<header class="navbar">
    <div class="logo">Resume<span>Now</span></div>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="login.php">Login</a>
    </div>
</header>
<div class="container">

<div class="left">
    <h3>Templates</h3>

    <div class="template-card" data-template="template1" onclick="changeTemplate('template1', this)">
        <img src="images/template1.png">
    </div>

    <div class="template-card" data-template="template2" onclick="changeTemplate('template2', this)">
        <img src="images/template2.png">
    </div>

    <div class="template-card" data-template="template3" onclick="changeTemplate('template3', this)">
        <img src="images/template3.png">
    </div>
    <div class="template-card" data-template="template4" onclick="changeTemplate('template4', this)">
    <img src="images/template4.png">
</div>

<div class="template-card" data-template="template5" onclick="changeTemplate('template5', this)">
    <img src="images/template5.jpg">
</div>

<div class="template-card" data-template="template6" onclick="changeTemplate('template6', this)">
    <img src="images/template6.jpg">
</div>
</div>

<div class="center">

    <div class="center-inner">

        <!-- RESUME -->
        <div id="preview"></div>

        <!-- ATS BOX -->
        <div id="atsContainer">
            <h3>📊 ATS Analysis</h3>
            <div id="atsBox">Your ATS result will appear here...</div>
        </div>

    </div>

</div>

<div class="right">
    <h3>Edit</h3>

    <input id="name" placeholder="Name">
    <input id="email" placeholder="Email">
    <textarea id="summary" placeholder="Summary"></textarea>
    <textarea id="skills" placeholder="Skills (comma separated)"></textarea>
    <textarea id="experience" placeholder="Experience (line by line)"></textarea>
    <textarea id="extra" placeholder="Project"></textarea>
    <div id="dynamicSections"></div>

<button class="btn add" onclick="addSectionField()">+ Add Section</button>
<button class="btn update" onclick="updateResume()">Update</button>
<button class="btn save" onclick="saveResume()">💾 Save Resume</button>
<button class="btn download" onclick="downloadPDF()">📄 Download PDF</button>
<button class="btn spell" onclick="openSpellCheck()">🔤 Spell Check</button>

<!-- 🔥 AI BUTTONS YAHAN ADD KAR -->
<button type="button" class="btn" onclick="generateSummary()">✨ AI Summary</button>
<button type="button" class="btn" onclick="getSuggestions()">💡 AI Skills</button>
<button type="button" class="btn" onclick="checkATS()">📊 ATS Score</button>
</button>
</div>

</div>
</div>
<script>

let currentTemplate = "<?php echo $template; ?>";
let sectionCount = 0;
let isLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;
let savedData = <?php echo json_encode($resumeData); ?>;
function getData() {

    let dynamicSections = [];

    for (let i = 1; i <= sectionCount; i++) {
    const titleEl = document.getElementById(`title_${i}`);
    const contentEl = document.getElementById(`content_${i}`);

    if (titleEl && contentEl && (titleEl.value.trim() || contentEl.value.trim())) {
        dynamicSections.push({
            title: titleEl.value || "Additional Section",
            items: contentEl.value.split(/,|\n/).map(i => i.trim())
        });
    }
}

    return {
        name: document.getElementById("name").value,
        email: document.getElementById("email").value,
        summary: document.getElementById("summary").value,
        
        skills: document.getElementById("skills").value
                    .split(/,|\n/)
                    .map(s => s.trim()),

        experience: document.getElementById("experience").value
                        .split(/,|\n/)
                        .map(e => e.trim()),

        extra: document.getElementById("extra").value
                    .split(/,|\n/)
                    .map(x => x.trim()),

        dynamicSections
    };
}
function downloadPDF() {

    const element = document.getElementById("preview"); // resume preview

    const opt = {
        margin: 0.3,
        filename: 'My_Resume.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
    };

    html2pdf().set(opt).from(element).save();
}
function convertToList(arr) {
    return "<ul>" + arr
        .filter(i => i && i.trim() !== "")
        .map(i => `<li>${i}</li>`)
        .join("") + "</ul>";
}

function renderTemplate(d) {

    const preview = document.getElementById("preview");

    if (!preview) return;

    if (currentTemplate === "template1") {
    preview.innerHTML = `
    <div class="resume">
    <h1>${d.name || "Your Name"}</h1>
    <p>${d.email || "email@example.com"}</p>

    <div class="content">
    <div class="left-sec">
    <h3>Summary</h3><p>${d.summary || "-"}</p>
    <h3>Experience</h3>${convertToList(d.experience)}
    <h3>Projects</h3>${convertToList(d.extra)}

    ${d.dynamicSections.map(sec => `
        <h3>${sec.title}</h3>
        ${convertToList(sec.items)}
    `).join("")}

    </div>

    <div class="right-sec">
    <h3>Skills</h3>${convertToList(d.skills)}
    </div>
    </div>
    </div>`;
}

 else if (currentTemplate === "template2") {
    preview.innerHTML = `
    <div class="resume2">
        <h1>${d.name || "Your Name"}</h1>
        <p class="email">${d.email || "email@example.com"}</p>

        <div class="grid2">
            <div class="left2">
                <div class="section">
                    <h3>Summary</h3>
                    <p>${d.summary || "-"}</p>
                </div>

                <div class="section">
                    <h3>Experience</h3>
                    ${convertToList(d.experience)}
                </div>

                <div class="section">
                    <h3>Projects</h3>
                    ${convertToList(d.extra)}
                </div>
               
            </div>

            <div class="right2">
                 <div class="section">
                    <h3>Skills</h3>
                   ${convertToList(d.skills)}
                </div>

                ${d.dynamicSections.map(sec => `
                    <div class="section">
                        <h3>${sec.title}</h3>
                        ${convertToList(sec.items)}
                    </div>
                `).join("")}
            </div>
        </div>
    </div>

    <style>
    .resume2 {
        font-family: 'Inter', sans-serif;
        width: 450px;
        margin: auto;
        padding: 40px;
        background: #ffffff;
        border-radius: 6px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .resume2 h1 {
        font-size: 28px;
        margin-bottom: 4px;
        color: #111;
    }

    .resume2 .email {
        color: #555;
        margin-bottom: 25px;
        font-size: 13px;
    }

    .grid2 {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
    }

    .section {
        margin-bottom: 18px;
    }

    .section h3 {
        font-size: 14px;
        color: #222;
        margin-bottom: 6px;
        border-bottom: 1px solid #ddd;
        padding-bottom: 3px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .section p {
        font-size: 13px;
        color: #333;
        line-height: 1.5;
    }

    .section ul {
    list-style-type: disc !important;
    padding-left: 18px;
    margin: 0;
}

.section li {
    display: list-item !important;
    background: none !important;
    padding: 0;
    border-radius: 0;
    color: #222;
}

    .right2 {
        background: transparent;
        padding: 0;
        border-radius: 0;
    }
    </style>
    `;
}

    else if (currentTemplate === "template3"){
    preview.innerHTML = `
    <div class="resume modern3">

    <div class="left3">
    <h2>${d.name || "Your Name"}</h2>
    <p>${d.email || "-"}</p>
    <h4>Skills</h4>${convertToList(d.skills)}
    </div>

    <div class="right3">
    <h3>Summary</h3><p>${d.summary || "-"}</p>
    <h3>Experience</h3>${convertToList(d.experience)}
    <h3>Project</h3>${convertToList(d.extra)}

    ${d.dynamicSections.map(sec => `
        <h3>${sec.title}</h3>
        ${convertToList(sec.items)}
    `).join("")}

    </div>

    </div>`;
}
else if (currentTemplate === "template4") {
    preview.innerHTML = `
    <div class="resume4">
        <h1>${d.name || "Your Name"}</h1>
    <p>${d.email || "-"}</p>
        <hr>

        <h3>Summary</h3>
        <p>${d.summary}</p>

        <h3>Experience</h3>
        ${convertToList(d.experience)}

        <h3>Projects</h3>
        ${convertToList(d.extra)}

        <h3>Skills</h3>
        ${convertToList(d.skills)}

        ${d.dynamicSections.map(sec => `
            <h3>${sec.title}</h3>
            ${convertToList(sec.items)}
        `).join("")}
    </div>

    <style>
    .resume4 {
        font-family: Arial;
        width: 480px;
        margin: auto;
        padding: 30px;
        background: #fff;
    }

    .resume4 h1 {
        margin-bottom: 5px;
    }

    .resume4 h3 {
        margin-top: 20px;
        border-bottom: 1px solid #ccc;
        padding-bottom: 3px;
    }
    </style>
    `;
}
else if (currentTemplate === "template5") {
    preview.innerHTML = `
    <div class="resume5">
        <div class="sidebar5">
            <h2>${d.name || "Your Name"}</h2>
    <p>${d.email || "-"}</p>

            <h4>Skills</h4>
            ${convertToList(d.skills)}
        </div>

        <div class="main5">
            <h3>Summary</h3>
            <p>${d.summary}</p>

            <h3>Experience</h3>
            ${convertToList(d.experience)}

            <h3>Projects</h3>
            ${convertToList(d.extra)}

            ${d.dynamicSections.map(sec => `
                <h3>${sec.title}</h3>
                ${convertToList(sec.items)}
            `).join("")}
        </div>
    </div>

    <style>
    .resume5 {
        display: flex;
        width: 480px;
        margin: auto;
        font-family: 'Inter';
    }

    .sidebar5 {
        width: 30%;
        background: #222;
        color: white;
        padding: 20px;
    }

    .main5 {
        width: 70%;
        padding: 20px;
        background: #fff;
    }

    .main5 h3 {
        border-bottom: 1px solid #ddd;
    }
    </style>
    `;
}
else if (currentTemplate === "template6") {
    preview.innerHTML = `
    <div class="resume6">
        <h1>${d.name || "Your Name"}</h1>
    <p>${d.email || "-"}</p>

        <div class="section6">
            <h3>Summary</h3>
            <p>${d.summary}</p>
        </div>

        <div class="section6">
            <h3>Skills</h3>
            ${convertToList(d.skills)}
        </div>

        <div class="section6">
            <h3>Experience</h3>
            ${convertToList(d.experience)}
        </div>

        <div class="section6">
            <h3>Projects</h3>
            ${convertToList(d.extra)}
        </div>

        ${d.dynamicSections.map(sec => `
            <div class="section6">
                <h3>${sec.title}</h3>
                ${convertToList(sec.items)}
            </div>
        `).join("")}
    </div>

    <style>
    .resume6 {
        width: 480px;
        margin: auto;
        padding: 30px;
        background: white;
        border-radius: 10px;
        font-family: 'Inter';
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    }

    .section6 {
        margin-top: 20px;
    }

    .section6 h3 {
        border-bottom: 1px solid #ccc;
    }
    </style>
    `;
}
}

function updateResume() {
    renderTemplate(getData());
}

function changeTemplate(t, el) {
    document.querySelectorAll(".template-card").forEach(c => c.classList.remove("active"));
    el.classList.add("active");
    currentTemplate = t;
    updateResume();
}

function addSectionField() {
    sectionCount++;

    const container = document.getElementById("dynamicSections");

    const div = document.createElement("div");
    div.classList.add("dynamic-block");

    div.innerHTML = `
        <input type="text" placeholder="Section Title" id="title_${sectionCount}" />
        
        <textarea placeholder="Enter items (comma or new line)" 
                  id="content_${sectionCount}"></textarea>
    `;

    container.appendChild(div);

    // 🔥 important fix
    div.querySelectorAll("input, textarea").forEach(el => {
        el.addEventListener("input", updateResume);
    });
    updateResume();
}
function saveResume() {

    // ❌ अगर login nahi hai
    if (!isLoggedIn) {
        alert("⚠️ Please login to save your resume");

        // current page save karlo (after login wapas yahi aayega)
        sessionStorage.setItem("redirectAfterLogin", window.location.href);

        window.location.href = "login.php";
        return;
    }

    // ✅ अगर login hai
    const data = getData();

    fetch("save.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            ...data,
            template: currentTemplate
        })
    })
    .then(res => res.json())
    .then(res => {
        if (res.status === "ok") {
            alert("✅ Resume Saved!");
        } else {
            alert("❌ Error saving resume");
        }
    })
    .catch(err => {
        console.error(err);
        alert("Server error");
    });
}
if (savedData) {

    document.getElementById("name").value = savedData.name || "";
    document.getElementById("email").value = savedData.email || "";
    document.getElementById("summary").value = savedData.summary || "";

    document.getElementById("skills").value = savedData.skills || "";
    document.getElementById("experience").value = (savedData.experience || "").replace(/\|/g, "\n");
    document.getElementById("extra").value = (savedData.extra || "").replace(/\|/g, "\n");

    // 🔥 template bhi set karo
    if (savedData.template) {
        currentTemplate = savedData.template;
    }

    // 🔥 dynamic sections
    let dyn = JSON.parse(savedData.dynamic_sections || "[]");

    dyn.forEach(sec => {
        addSectionField();

        document.getElementById(`title_${sectionCount}`).value = sec.title;
        document.getElementById(`content_${sectionCount}`).value = sec.items.join("\n");
    });

    updateResume();
}
// initial render
updateResume();

</script>
<div id="spellModal" style="
    display:none;
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.5);
    justify-content:center;
    align-items:center;
    z-index:9999;
">

<div style="
    background:#fff;
    padding:25px;
    width:550px;
    border-radius:12px;
    box-shadow:0 10px 30px rgba(0,0,0,0.2);
">

    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h2>🔤 Spell Check</h2>
        <span onclick="closeSpellCheck()" style="cursor:pointer; font-size:20px;">✖</span>
    </div>

    <div style="display:flex; gap:20px; margin-top:20px;">

        <!-- LEFT -->
        <div style="flex:1;">
            <h4>❌ Not in Dictionary</h4>
            <div id="wrongWord" style="color:red; font-weight:bold;"></div>
        </div>

        <!-- RIGHT -->
        <div style="flex:1;">
            <h4>💡 Suggestions</h4>
            <div id="suggestions" style="max-height:150px; overflow-y:auto;"></div>
        </div>

    </div>

    <div style="margin-top:20px; display:flex; gap:10px;">
        <button onclick="changeWord()">Change</button>
        <button onclick="changeAll()">Change All</button>
        <button onclick="ignoreWord()">Ignore</button>
        <button onclick="closeSpellCheck()">Done</button>
    </div>

</div>
</div>
<script src="spell.js"></script>
</body>
</html>