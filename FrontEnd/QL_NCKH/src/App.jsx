import React from "react"
import Admin from "./pages/Admin"
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import Login from "./pages/Login";
function App() {

  return (
    <>
      <Router>
        <Login/>
        <Admin></Admin>
      </Router>
    </>
  )
}

export default App
