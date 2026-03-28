import React, { useEffect, useState } from "react";
import "./Landing.css";
import { useNavigate } from "react-router-dom";

function Landing() {
  const navigate = useNavigate();

  const words = ["Resume", "CV", "Portfolio"];
  const [index, setIndex] = useState(0);
  const [display, setDisplay] = useState("");

  useEffect(() => {
    let i = 0;

    const typing = setInterval(() => {
      setDisplay(words[index].slice(0, i));
      i++;

      if (i > words[index].length) {
        clearInterval(typing);
        setTimeout(() => {
          setIndex((prev) => (prev + 1) % words.length);
        }, 1200);
      }
    }, 80);

    return () => clearInterval(typing);
  }, [index]);

  return (
    <div className="landing">

      {/* BACKGROUND GLOW */}
      <div className="bg-glow"></div>

      {/* NAVBAR */}
      <div className="navbar">
        <h2 className="logo">VisualCV</h2>
      </div>

      {/* HERO */}
      <div className="hero">

        <div className="hero-left">
          <h1>
            Create a <span>{display}</span> in Minutes 🚀
          </h1>

          <p>
            Build ATS-friendly resumes with modern templates and live preview.
          </p>

          <button className="cta" onClick={() => navigate("/builder")}>
            Create Resume →
          </button>
        </div>

        {/* FLOATING CARD */}
        <div className="hero-right">
          <div className="resume-card">
            <h3>Your Name</h3>
            <p className="role">Frontend Developer</p>

            <div className="line"></div>
            <div className="line short"></div>
            <div className="line"></div>

            <div className="line"></div>
            <div className="line short"></div>
          </div>
        </div>

      </div>

    </div>
  );
}

export default Landing;