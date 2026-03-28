import React from "react";
import styles from "./Header.module.css";
import resumeSvg from "../assets/resume.svg";

const Header = () => {
  return (
    <div className={styles.container}>

      {/* HERO CONTENT */}
      <div className={styles.content}>
        <h1>
          Create Your <span>Professional Resume</span>
        </h1>

        <p>
          Design modern resumes with live preview and powerful editing tools.
        </p>
      </div>

      {/* FLOATING CARD */}
      <div className={styles.card}>
        <img src={resumeSvg} alt="resume" />
      </div>

    </div>
  );
};

export default Header;