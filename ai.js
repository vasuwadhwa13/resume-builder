// ================= AI SUMMARY =================
async function generateSummary() {
  const btn = event.target;

  try {
    btn.disabled = true;

    let name = document.getElementById("name").value;
    let email = document.getElementById("email").value;
    let skills = document.getElementById("skills").value;
    let experience = document.getElementById("experience").value;
    let project = document.getElementById("extra").value;

    if (!name && !skills && !experience) {
      alert("⚠️ Fill some data first");
      btn.disabled = false;
      return;
    }

    let text = `
    Name: ${name}
    Email: ${email}
    Skills: ${skills}
    Experience: ${experience}
    Projects: ${project}
    `;

    document.getElementById("summary").value = "⏳ Writing short summary...";

    let res = await fetch("ai-summary.php", {
      method: "POST",
      headers: {"Content-Type": "application/json"},
      body: JSON.stringify({ text })
    });

    let data = await res.json();

    let clean = (data.result || "❌ Failed")
      .replace(/\*\*/g, "")
      .replace(/\n/g, " ")
      .trim();

    document.getElementById("summary").value = clean;

  } catch (err) {
    console.error(err);
    alert("❌ AI Summary failed");
  } finally {
    btn.disabled = false;
  }
}


// ================= AI SKILLS =================
async function getSuggestions() {
  const btn = event.target;

  try {
    btn.disabled = true;

    let role = document.getElementById("name").value;

    if (!role) {
      alert("⚠️ Enter role/name first");
      btn.disabled = false;
      return;
    }

    document.getElementById("skills").value = "⏳ Generating skills...";

    let res = await fetch("ai-suggestions.php", {
      method: "POST",
      headers: {"Content-Type": "application/json"},
      body: JSON.stringify({ role })
    });

    let data = await res.json();

    let clean = (data.result || "❌ Failed")
      .replace(/\*\*/g, "")
      .replace(/\n/g, "")
      .replace(/\s{2,}/g, " ")
      .trim();

    document.getElementById("skills").value = clean;

  } catch (err) {
    console.error(err);
    alert("❌ AI Skills failed");
  } finally {
    btn.disabled = false;
  }
}


// ================= ATS =================
async function checkATS() {
  const btn = event.target;

  try {
    btn.disabled = true;

    let skills = document.getElementById("skills").value;
    let experience = document.getElementById("experience").value;
    let project = document.getElementById("extra").value;

    if (!skills && !experience) {
      alert("⚠️ Fill resume data first");
      btn.disabled = false;
      return;
    }

    let resume = `
    Skills: ${skills}
    Experience: ${experience}
    Projects: ${project}
    `;

    let job = prompt("Paste Job Description:");
    if (!job) {
      btn.disabled = false;
      return;
    }

    document.getElementById("atsBox").innerText = "⏳ Analyzing ATS score...";

    let res = await fetch("ai-ats.php", {
      method: "POST",
      headers: {"Content-Type": "application/json"},
      body: JSON.stringify({ resume, job })
    });

    let data = await res.json();

    // 🔥 CLEAN + SHORT + BULLETS
    let raw = data.result || "❌ Failed";

    let clean = raw
      .replace(/\*\*/g, "")
      .replace(/###/g, "")
      .replace(/\n\n/g, "\n")
      .split("\n")
      .filter(line => line.trim() !== "")
      .slice(0, 8) // 👈 sirf top 8 points
      .map(line => "• " + line.trim())
      .join("\n");

    document.getElementById("atsBox").innerText = clean;

  } catch (err) {
    console.error(err);
    alert("❌ ATS failed");
  } finally {
    btn.disabled = false;
  }
}