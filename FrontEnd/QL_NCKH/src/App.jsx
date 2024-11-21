import React from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import Layout from './component/Layout';
import Admin from "./pages/Admin";
import Department from "./pages/Department";
import SciTech from "./pages/SciTech";
import Login from "./pages/Login";
import AuthCallback from './pages/AuthCallback';
import ArticleReview from './/component/ArticleReview/ArticleReview';
import LecturerTopicApproval from './component/LecturerTopicApproval/TopicApproval';
import StudentTopicApproval from './component/StudentTopicApproval/StudentTopicApproval';
import ScienceSeminar from './component/ScienceSeminar/ScienceSeminar';
import StudentManager from './component/StudentTopicApproval/StudentManager';
import Statistics from './pages/Statistics';
import Product from './component/StudentTopicApproval/Product';
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
        <Route path="/admin" element={<PrivateRoute element={<Statistics />} />} />
        <Route path="/admin/lecturer-topic-approval" element={<PrivateRoute element={<LecturerTopicApproval />} />} />
        <Route path="/admin/student-topic-approval" element={<PrivateRoute element={<StudentTopicApproval />} />} />
        <Route path="/admin/article-review" element={<PrivateRoute element={<ArticleReview />} />} />
        <Route path="/admin/science-seminar" element={<PrivateRoute element={<ScienceSeminar />} />} />
        <Route path="/admin/edit-student" element={<PrivateRoute element={<StudentManager />} />} />
        <Route path="/department" element={<PrivateRoute element={<Department />} />} />
        <Route path="/authcallback" element={<PrivateRoute element={<AuthCallback />} />} />
        <Route path="/admin/product-manager" element={<PrivateRoute element={<Product />} />} />

        <Route path="/scitech" element={<PrivateRoute element={<SciTech />} />} />
      </Routes>
    </Router>
  );
}

export default App;
