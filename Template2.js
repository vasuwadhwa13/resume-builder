import React, { forwardRef } from "react";
import {
  AtSign,
  Phone,
  Linkedin,
  GitHub,
  Calendar,
  MapPin,
} from "react-feather";
import styles from "./Template2.module.css";

const Template2 = forwardRef(({ information, sections }, ref) => {
  const info = {
    basicInfo: information[sections.basicInfo],
    workExp: information[sections.workExp],
    project: information[sections.project],
    education: information[sections.education],
    achievements: information[sections.achievements],
    summary: information[sections.summary],
    others: information[sections.others],
  };

  // ✅ DEFAULT PLACEHOLDER DATA
  const defaults = {
    name: "Your Name",
    title: "Frontend Developer",
    email: "your.email@gmail.com",
    phone: "+91 XXXXX XXXXX",
    linkedin: "linkedin.com/in/yourprofile",
    github: "github.com/yourgithub",
    summary: "Write a short professional summary about yourself.",

    workExp: [
      {
        title: "Job Title",
        companyName: "Company Name",
        startDate: "2022-01-01",
        endDate: "2023-01-01",
        location: "City, India",
        points: ["Did something impactful", "Improved performance"],
      },
    ],

    project: [
      {
        title: "Project Name",
        overview: "Brief about your project",
        points: ["Built using React", "API integration"],
      },
    ],

    education: [
      {
        title: "BCA / MCA / Degree",
        college: "Your College",
        startDate: "2021-01-01",
        endDate: "2024-01-01",
      },
    ],

    achievements: ["Won hackathon", "Top performer"],
    others: "Languages, hobbies, interests etc.",
  };

  const formatDate = (value) => {
    if (!value) return "";
    const d = new Date(value);
    return `${d.getMonth() + 1}/${d.getFullYear()}`;
  };

  return (
    <div ref={ref} className={styles.container}>
      
      {/* ===== SIDEBAR ===== */}
      <div className={styles.sidebar}>
        <h1>{info.basicInfo?.detail?.name || defaults.name}</h1>
        <p className={styles.title}>
          {info.basicInfo?.detail?.title || defaults.title}
        </p>

        <div className={styles.links}>
          <span>
            <AtSign /> {info.basicInfo?.detail?.email || defaults.email}
          </span>
          <span>
            <Phone /> {info.basicInfo?.detail?.phone || defaults.phone}
          </span>
          <span>
            <Linkedin />{" "}
            {info.basicInfo?.detail?.linkedin || defaults.linkedin}
          </span>
          <span>
            <GitHub /> {info.basicInfo?.detail?.github || defaults.github}
          </span>
        </div>
      </div>

      {/* ===== MAIN CONTENT ===== */}
      <div className={styles.main}>

        {/* SUMMARY */}
        <div className={styles.card}>
          <h2>Summary</h2>
          <p>{info.summary?.detail || defaults.summary}</p>
        </div>

        {/* WORK EXPERIENCE */}
        <div className={styles.card}>
          <h2>Work Experience</h2>
          {(info.workExp?.details?.length
            ? info.workExp.details
            : defaults.workExp
          ).map((item, i) => (
            <div key={i} className={styles.block}>
              <h3>{item.title}</h3>
              <p className={styles.company}>{item.companyName}</p>

              <div className={styles.meta}>
                <span>
                  <Calendar /> {formatDate(item.startDate)} -{" "}
                  {formatDate(item.endDate)}
                </span>
                <span>
                  <MapPin /> {item.location}
                </span>
              </div>

              <ul>
                {(item.points || []).map((p, idx) => (
                  <li key={idx}>{p}</li>
                ))}
              </ul>
            </div>
          ))}
        </div>

        {/* PROJECTS */}
        <div className={styles.card}>
          <h2>Projects</h2>
          {(info.project?.details?.length
            ? info.project.details
            : defaults.project
          ).map((item, i) => (
            <div key={i} className={styles.block}>
              <h3>{item.title}</h3>
              <p>{item.overview}</p>

              <ul>
                {(item.points || []).map((p, idx) => (
                  <li key={idx}>{p}</li>
                ))}
              </ul>
            </div>
          ))}
        </div>

        {/* EDUCATION */}
        <div className={styles.card}>
          <h2>Education</h2>
          {(info.education?.details?.length
            ? info.education.details
            : defaults.education
          ).map((item, i) => (
            <div key={i} className={styles.block}>
              <h3>{item.title}</h3>
              <p className={styles.company}>{item.college}</p>

              <span className={styles.meta}>
                <Calendar /> {formatDate(item.startDate)} -{" "}
                {formatDate(item.endDate)}
              </span>
            </div>
          ))}
        </div>

        {/* ACHIEVEMENTS */}
        <div className={styles.card}>
          <h2>Achievements</h2>
          <ul>
            {(info.achievements?.points?.length
              ? info.achievements.points
              : defaults.achievements
            ).map((p, i) => (
              <li key={i}>{p}</li>
            ))}
          </ul>
        </div>

        {/* OTHERS */}
        <div className={styles.card}>
          <h2>Others</h2>
          <p>{info.others?.detail || defaults.others}</p>
        </div>

      </div>
    </div>
  );
});

export default Template2;