import React from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import Layout from './component/Layout';
import Admin from "./pages/Admin";
import Department from "./pages/Department";
import SciTech from "./pages/SciTech";
import Login from "./pages/Login";
import ArticleReview from './/component/ArticleReview/ArticleReview';
import LecturerTopicApproval from './component/LecturerTopicApproval/TopicApproval';
import StudentTopicApproval from './component/StudentTopicApproval/StudentTopicApproval';
// Component bảo vệ route
const PrivateRoute = ({ element }) => {
  const isAuthenticated = localStorage.getItem('isAuthenticated');
  return isAuthenticated ? <Layout>{element}</Layout> : <Navigate to="/" />;
};

function App() {
  return (
    <Router>
      <Routes>
        <Route path="/" element={<Login />} />
        <Route path="/admin" element={<PrivateRoute element={<Admin />} />} />
        <Route path="/admin/lecturer-topic-approval" element={<PrivateRoute element={<LecturerTopicApproval />} />} />
        <Route path="/admin/lecturer-topic-approval" element={<PrivateRoute element={<StudentTopicApproval />} />} />
        <Route path="/admin/article-review" element={<PrivateRoute element={<ArticleReview />} />} />
        <Route path="/department" element={<PrivateRoute element={<Department />} />} />
        <Route path="/scitech" element={<PrivateRoute element={<SciTech />} />} />
      </Routes>
    </Router>
  );
}

export default App;
