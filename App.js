import React from "react";
import { BrowserRouter as Router, Routes, Route, Navigate } from "react-router-dom";

import Landing from "./pages/Landing";
import Builder from "./pages/Builder";

function App() {
  return (
    <Router>
      <Routes>

        {/* ALWAYS LAND FIRST */}
        <Route path="/" element={<Landing />} />

        {/* REDIRECT EVERYTHING ELSE TO LANDING */}
        <Route path="*" element={<Navigate to="/" />} />

        {/* Builder (only via button navigation) */}
        <Route path="/builder" element={<Builder />} />

      </Routes>
    </Router>
  );
}

export default App;