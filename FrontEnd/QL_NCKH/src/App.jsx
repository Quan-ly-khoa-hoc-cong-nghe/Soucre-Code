import React from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import Admin from "./pages/Admin";
import Department from "./pages/Department";
import SciTech from "./pages/SciTech";
import Login from "./pages/Login";

// Component bảo vệ route
const PrivateRoute = ({ element }) => {
  const isAuthenticated = localStorage.getItem('isAuthenticated'); // Kiểm tra trạng thái đăng nhập
  return isAuthenticated ? element : <Navigate to="/" />;  // Nếu đã đăng nhập thì hiển thị trang, nếu chưa thì chuyển hướng về trang đăng nhập
};

function App() {
  return (
    <Router>
      <Routes>
        {/* Route cho trang đăng nhập */}
        <Route path="/" element={<Login />} />

        {/* Các Route chính, bảo vệ các route này */}
        <Route path="/admin" element={<PrivateRoute element={<Admin />} />} />
        <Route path="/department" element={<PrivateRoute element={<Department />} />} />
        <Route path="/scitech" element={<PrivateRoute element={<SciTech />} />} />
      </Routes>
    </Router>
  );
}

export default App;
