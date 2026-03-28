import React, { useRef } from "react";
import ReactToPrint from "react-to-print";

import Template1 from "./Template1";
import Template2 from "./Template2";
import Template3 from "./Template3";
import Template4 from "./Template4";

import styles from "./ResumeContainer.module.css";

const templates = {
  Template1: Template1,
  Template2: Template2,
  Template3: Template3,
  Template4: Template4,
};

const ResumeContainer = ({
  activeTemplate,
  setActiveTemplate,   // ✅ IMPORTANT
  sections,
  information,
}) => {
  const Component = templates[activeTemplate] || Template1;
  const resumeRef = useRef();

  return (
    <div className={styles.container}>

      {/* TOP BAR */}
      <div className={styles.topbar}>

        {/* TITLE */}
        <h3>Live Preview</h3>

        {/* TEMPLATE SWITCH */}
        <div className={styles.templateSwitch}>
          {Object.keys(templates).map((temp) => (
            <button
              key={temp}
              className={`${styles.templateBtn} ${
                activeTemplate === temp ? styles.activeTemplate : ""
              }`}
              onClick={() => setActiveTemplate(temp)}
            >
              {temp.replace("Template", "T")}
            </button>
          ))}
        </div>

        {/* DOWNLOAD */}
        <ReactToPrint
          trigger={() => (
            <button className={styles.downloadBtn}>
              ⬇ Download
            </button>
          )}
          content={() => resumeRef.current}
        />
      </div>

      {/* PREVIEW AREA */}
      <div className={styles.previewWrapper}>
        <div className={styles.preview} ref={resumeRef}>
          <Component sections={sections} information={information} />
        </div>
      </div>

    </div>
  );
};

export default ResumeContainer;