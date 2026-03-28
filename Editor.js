import React, { useEffect, useState } from "react";
import styles from "./Editor.module.css";
import InputControl from "./InputControls";

const Editor = ({ sections, information, setInformation }) => {
  const sectionKeys = Object.keys(sections);

  const [activeSectionKey, setActiveSectionKey] = useState(sectionKeys[0]);
  const [values, setValues] = useState({});
  const [activeIndex, setActiveIndex] = useState(0);

  const activeSection = sections[activeSectionKey];
  const activeData = information[activeSection];

  /* LOAD DATA */
  useEffect(() => {
    if (!activeData) return;

    if (activeData.details) {
      setValues(activeData.details[activeIndex] || {});
    } else if (activeData.points) {
      setValues((prev) => ({ text: prev?.text || "" }));
    } else {
      setValues(activeData.detail || "");
    }
  }, [activeSectionKey, activeIndex, information]);

  /* UPDATE */
  const updateInformation = (newValues) => {
    const updated = { ...information };

    if (activeData.details) {
      const newDetails = [...activeData.details];
      newDetails[activeIndex] = newValues;

      updated[activeSection] = {
        ...activeData,
        details: newDetails,
      };
    } else if (activeData.points) {
      updated[activeSection] = {
        ...activeData,
        points: newValues,
      };
    } else {
      updated[activeSection] = {
        ...activeData,
        detail: newValues,
      };
    }

    setInformation(updated);
  };

  /* CHANGE (FIXED HERE) */
  const handleChange = (key, val) => {
    const value = val?.target?.value ?? val;

    const updatedValues = {
      ...(typeof values === "object" ? values : {}),
      [key]: value,
    };

    setValues(updatedValues);
    updateInformation(updatedValues);
  };

  const handleSimpleChange = (val) => {
    const value = val?.target?.value ?? val;
    setValues(value || "");
    updateInformation(value || "");
  };

  /* ADD / DELETE */
  const handleAdd = () => {
    if (!activeData.details) return;

    const updated = { ...information };
    updated[activeSection].details.push({});
    setInformation(updated);
    setActiveIndex(updated[activeSection].details.length - 1);
  };

  const handleDelete = (index) => {
    const updated = { ...information };
    updated[activeSection].details.splice(index, 1);
    setInformation(updated);
    setActiveIndex(0);
  };

  /* ACHIEVEMENTS */
  const handleAddAchievement = () => {
    if (!values?.text?.trim()) return;

    const updated = { ...information };
    updated[activeSection].points = [
      ...(updated[activeSection].points || []),
      values.text,
    ];

    setInformation(updated);
    setValues({ text: "" });
  };

  const handleDeleteAchievement = (index) => {
    const updated = { ...information };
    updated[activeSection].points.splice(index, 1);
    setInformation(updated);
  };

  /* FIELDS */
  const renderFields = () => {
    switch (activeSection) {
      case sections.basicInfo:
        return (
          <>
            <InputControl label="Name" placeholder="e.g. John Doe" hint="Enter your full name"
              value={values.name || ""} onChange={(e) => handleChange("name", e)} />

            <InputControl label="Title" placeholder="e.g. Frontend Developer" hint="Your role"
              value={values.title || ""} onChange={(e) => handleChange("title", e)} />

            <InputControl label="Email" placeholder="e.g. john@gmail.com"
              value={values.email || ""} onChange={(e) => handleChange("email", e)} />

            <InputControl label="Phone" placeholder="e.g. +91 9876543210"
              value={values.phone || ""} onChange={(e) => handleChange("phone", e)} />

            <InputControl label="LinkedIn" placeholder="LinkedIn profile link"
              value={values.linkedin || ""} onChange={(e) => handleChange("linkedin", e)} />

            <InputControl label="GitHub" placeholder="GitHub profile link"
              value={values.github || ""} onChange={(e) => handleChange("github", e)} />
          </>
        );

      case sections.workExp:
        return (
          <>
            <InputControl label="Job Title" value={values.title || ""} onChange={(e) => handleChange("title", e)} />
            <InputControl label="Company" value={values.companyName || ""} onChange={(e) => handleChange("companyName", e)} />
            <InputControl label="Location" value={values.location || ""} onChange={(e) => handleChange("location", e)} />
            <InputControl label="Start Date" type="month" value={values.startDate || ""} onChange={(e) => handleChange("startDate", e)} />
            <InputControl label="End Date" type="month" value={values.endDate || ""} onChange={(e) => handleChange("endDate", e)} />
          </>
        );

      case sections.project:
        return (
          <>
            <InputControl label="Project Title" value={values.title || ""} onChange={(e) => handleChange("title", e)} />
            <InputControl label="Overview" value={values.overview || ""} onChange={(e) => handleChange("overview", e)} />
            <InputControl label="Link" value={values.link || ""} onChange={(e) => handleChange("link", e)} />
            <InputControl label="GitHub" value={values.github || ""} onChange={(e) => handleChange("github", e)} />
          </>
        );

      case sections.education:
        return (
          <>
            <InputControl label="Degree" value={values.title || ""} onChange={(e) => handleChange("title", e)} />
            <InputControl label="College" value={values.college || ""} onChange={(e) => handleChange("college", e)} />
            <InputControl label="Start Date" type="month" value={values.startDate || ""} onChange={(e) => handleChange("startDate", e)} />
            <InputControl label="End Date" type="month" value={values.endDate || ""} onChange={(e) => handleChange("endDate", e)} />
          </>
        );

      case sections.achievements:
        return (
          <>
            <InputControl
              label="Achievement"
              placeholder="e.g. Won Hackathon"
              value={values.text || ""}
              onChange={(e) =>
                setValues((prev) => ({
                  ...(typeof prev === "object" ? prev : {}),
                  text: e?.target?.value ?? e,
                }))
              }
            />

            <button className={styles.primaryBtn} onClick={handleAddAchievement}>
              + Add Achievement
            </button>

            <div className={styles.chips}>
              {activeData?.points?.map((item, index) => (
                <div key={index} className={styles.chip}>
                  {item}
                  <span onClick={() => handleDeleteAchievement(index)}>✕</span>
                </div>
              ))}
            </div>
          </>
        );

      case sections.summary:
      case sections.others:
        return (
          <textarea
            className={styles.textarea}
            placeholder="Write something about yourself..."
            value={typeof values === "string" ? values : ""}
            onChange={(e) => handleSimpleChange(e)}
          />
        );

      default:
        return null;
    }
  };

  return (
    <div className={styles.container}>
      <div className={styles.tabs}>
        {sectionKeys.map((key) => (
          <div
            key={key}
            className={`${styles.tab} ${activeSectionKey === key ? styles.activeTab : ""}`}
            onClick={() => {
              setActiveSectionKey(key);
              setActiveIndex(0);
            }}
          >
            {sections[key]}
          </div>
        ))}
      </div>

      <div className={styles.card}>
        {activeData?.details && (
          <div className={styles.chips}>
            {activeData.details.map((_, i) => (
              <div
                key={i}
                className={`${styles.chip} ${activeIndex === i ? styles.activeChip : ""}`}
                onClick={() => setActiveIndex(i)}
              >
                {i + 1}
                <span onClick={(e) => { e.stopPropagation(); handleDelete(i); }}>✕</span>
              </div>
            ))}
            <button className={styles.primaryBtn} onClick={handleAdd}>
              + Add
            </button>
          </div>
        )}

        <div className={styles.form}>
          {renderFields()}
        </div>
      </div>
    </div>
  );
};

export default Editor;