import React from "react";
import styles from "./InputControl.module.css";

const InputControls = ({
  label,
  description,
  value,
  onChange,
  ...props
}) => {
  return (
    <div className={styles.container}>
      
      {label && <label className={styles.label}>{label}</label>}

      {description && (
        <p className={styles.description}>{description}</p>
      )}

      <input
        className={styles.input}
        value={value || ""}
        onChange={(e) => onChange && onChange(e.target.value)}
        {...props}
      />
    </div>
  );
};

export default InputControls;