import React from "react";
import Header from "../components/Header";
import Body from "../components/Body";

function Builder() {
  const sections = {
    basicInfo: "basicInfo",
    workExp: "workExp",
    project: "project",
    education: "education",
    achievements: "achievements",
    summary: "summary",
    others: "others",
  };

  return (
    <>
      <Header />
      <Body sections={sections} />
    </>
  );
}

export default Builder;