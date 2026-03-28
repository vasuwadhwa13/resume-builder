import React, { useState } from "react";
import styles from "./Body.module.css";
import Editor from "./Editor";
import ResumeContainer from "./ResumeContainer";

/* TEMPLATE IMAGES */
import t1 from "../assets/templates/t1.png";
import t2 from "../assets/templates/t2.png";
import t3 from "../assets/templates/t3.png";
import t4 from "../assets/templates/t4.png";

const templates = [
  { id: "Template1", name: "Modern", img: t1 },
  { id: "Template2", name: "Sidebar", img: t2 },
  { id: "Template3", name: "Creative", img: t3 },
  { id: "Template4", name: "Professional", img: t4 },
];

const Body = ({ sections }) => {
  const [activeTemplate, setActiveTemplate] = useState("Template1");

  const [information, setInformation] = useState({
    [sections.basicInfo]: { detail: {} },
    [sections.workExp]: { details: [] },
    [sections.project]: { details: [] },
    [sections.education]: { details: [] },
    [sections.achievements]: { points: [] },
    [sections.summary]: { detail: "" },
    [sections.others]: { detail: "" },
  });

  return (
    <div className={styles.container}>
      <div className={styles.main}>

        {/* EDITOR */}
        <div className={styles.editor}>
          <Editor
            sections={sections}
            information={information}
            setInformation={setInformation}
          />
        </div>

        {/* PREVIEW */}
        <div className={styles.preview}>
          <ResumeContainer
            sections={sections}
            information={information}
            activeTemplate={activeTemplate}
            setActiveTemplate={setActiveTemplate}
          />
        </div>

      </div>
    </div>
  );
};

export default Body;