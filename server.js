import express from "express";
import cors from "cors";

const app = express();

app.use(cors());
app.use(express.json());

// 🔥 API KEY DAAL
const API_KEY = "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"; // 👈 apni key daal

// ================= AI SUMMARY =================
app.post("/ai-summary", async (req, res) => {
  try {
    const { text } = req.body;

    const response = await fetch("https://openrouter.ai/api/v1/chat/completions", {
      method: "POST",
      headers: {
        "Authorization": `Bearer ${API_KEY}`,
        "Content-Type": "application/json"
      },
      body: JSON.stringify({
        model: "mistralai/mistral-7b-instruct", // 🔥 stable model
        messages: [
          {
            role: "user",
            content: `Write a short professional resume summary:\n${text}`
          }
        ]
      })
    });

    const data = await response.json();
    console.log("API RESPONSE:", data);

    res.json({
      result: data?.choices?.[0]?.message?.content || "AI failed"
    });

  } catch (err) {
    console.error("❌ ERROR:", err);
    res.status(500).json({ error: "Server crash" });
  }
});


// ================= AI SKILLS =================
app.post("/ai-suggestions", async (req, res) => {
  try {
    const { role } = req.body;

    const response = await fetch("https://openrouter.ai/api/v1/chat/completions", {
      method: "POST",
      headers: {
        "Authorization": `Bearer ${API_KEY}`,
        "Content-Type": "application/json"
      },
      body: JSON.stringify({
        model: "mistralai/mistral-7b-instruct",
        messages: [
          {
            role: "user",
            content: `Give resume skills for ${role} in comma format`
          }
        ]
      })
    });

    const data = await response.json();

    res.json({
      result: data?.choices?.[0]?.message?.content || "AI failed"
    });

  } catch (err) {
    console.error("❌ ERROR:", err);
    res.status(500).json({ error: "Server crash" });
  }
});


// ================= ATS =================
app.post("/ai-ats", async (req, res) => {
  try {
    const { resume, job } = req.body;

    const response = await fetch("https://openrouter.ai/api/v1/chat/completions", {
      method: "POST",
      headers: {
        "Authorization": `Bearer ${API_KEY}`,
        "Content-Type": "application/json"
      },
      body: JSON.stringify({
        model: "mistralai/mistral-7b-instruct",
        messages: [
          {
            role: "user",
            content: `
Give ATS score out of 100 and improvements.

Resume:
${resume}

Job:
${job}
`
          }
        ]
      })
    });

    const data = await response.json();

    res.json({
      result: data?.choices?.[0]?.message?.content || "AI failed"
    });

  } catch (err) {
    console.error("❌ ERROR:", err);
    res.status(500).json({ error: "Server crash" });
  }
});


app.listen(3000, () => {
  console.log("🚀 Server running on http://localhost:3000");
});