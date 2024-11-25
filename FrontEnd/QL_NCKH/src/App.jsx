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
import ApplicationApproval from './component/StudentTopicApproval/ApplicationApproval';
import ApplicationApprovalAdmin from './component/StudentTopicApproval/ApplicationApproval_Admin';
import LecturerApplicationApprovalList from './component/LecturerTopicApproval/LecturerApplicationApprovalList';
import LecturerApplicationApprovalListAdmin from './component/LecturerTopicApproval/LecturerApplicationApprovalList_Admin';

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
        
        {/* Các route có Layout */}
        <Route path="/admin" element={<PrivateRoute element={<Statistics />} />} />
        <Route path="/admin/lecturer-topic-approval" element={<PrivateRoute element={<LecturerTopicApproval />} />} />
        <Route path="/admin/student-topic-approval" element={<PrivateRoute element={<StudentTopicApproval />} />} />
        <Route path="/admin/article-review" element={<PrivateRoute element={<ArticleReview />} />} />
        <Route path="/admin/science-seminar" element={<PrivateRoute element={<ScienceSeminar />} />} />
        <Route path="/admin/edit-student" element={<PrivateRoute element={<StudentManager />} />} />
        <Route path="/admin/product-manager" element={<PrivateRoute element={<Product />} />} />
        <Route path="/admin/application-approval" element={<PrivateRoute element={<ApplicationApproval />} />} />
        <Route path="/admin/application-approval-admin" element={<PrivateRoute element={<ApplicationApprovalAdmin />} />} />
        <Route path="/admin/lecturer-application-approval-admin" element={<PrivateRoute element={<LecturerApplicationApprovalListAdmin />} />} />
        <Route path="/admin/lecturer-application-approval-list-admin" element={<PrivateRoute element={<LecturerApplicationApprovalList />} />} />

        {/* Các route không cần Layout */}
        <Route path="/department" element={<Department />} />
        <Route path="/scitech" element={<SciTech />} />

        <Route path="/authcallback" element={<LecturerApplicationApprovalList element={<AuthCallback />} />} />
      </Routes>
    </Router>
  );
}

export default App;
