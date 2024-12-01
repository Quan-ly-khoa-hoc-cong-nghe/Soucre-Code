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
import LayoutDepartment from './component/LayoutDepartment';
import LayoutSciTech from './component/LayoutSciTech';
import ScienceSeminardepartments from './component/ScienceSeminar/ScienceSeminardepartments';
import ScienceSeminarSciTech from './component/ScienceSeminar/ScienceSeminarSciTech';
import ListArticleReviewDepartment from './component/ArticleReview/ListArticleReviewDepartment';
import ListArticleReviewSciTech from './component/ArticleReview/ListArticleReviewSciTech';
import ApprovalOfSchoolTopicDepartment from './component/School-LevelTopic/ApprovalOfSchoolTopicDepartment';
import ApprovalOfSchoolTopicSciTech from './component/School-LevelTopic/ApprovalOfSchoolTopicSciTech';
// Component bảo vệ route
const PrivateRoute = ({ element, layout: LayoutComponent }) => {
  const isAuthenticated = localStorage.getItem('isAuthenticated');
  return isAuthenticated ? <LayoutComponent>{element}</LayoutComponent> : <Navigate to="/" />;
};

function App() {
  return (
    <Router>
      <Routes>
        <Route path="/" element={<Login />} />

        {/* Các route cho Layout */}
        <Route path="/admin" element={<PrivateRoute element={<Statistics />} layout={Layout} />} />
        <Route path="/admin/lecturer-topic-approval" element={<PrivateRoute element={<LecturerTopicApproval />} layout={Layout} />} />
        <Route path="/admin/student-topic-approval" element={<PrivateRoute element={<StudentTopicApproval />} layout={Layout} />} />
        <Route path="/admin/article-review" element={<PrivateRoute element={<ArticleReview />} layout={Layout} />} />
        <Route path="/admin/science-seminar" element={<PrivateRoute element={<ScienceSeminar />} layout={Layout} />} />
        <Route path="/admin/edit-student" element={<PrivateRoute element={<StudentManager />} layout={Layout} />} />
        <Route path="/admin/product-manager" element={<PrivateRoute element={<Product />} layout={Layout} />} />
        <Route path="/admin/application-approval" element={<PrivateRoute element={<ApplicationApproval />} layout={Layout} />} />
        <Route path="/admin/application-approval-admin" element={<PrivateRoute element={<ApplicationApprovalAdmin />} layout={Layout} />} />
        <Route path="/admin/lecturer-application-approval-admin" element={<PrivateRoute element={<LecturerApplicationApprovalListAdmin />} layout={Layout} />} />
        <Route path="/admin/lecturer-application-approval-list-admin" element={<PrivateRoute element={<LecturerApplicationApprovalList />} layout={Layout} />} />
        <Route path="/admin/science-seminar-departments" element={<PrivateRoute element={<ScienceSeminardepartments/>} layout={Layout} />} />
        <Route path="/admin/science-seminar-sciTech" element={<PrivateRoute element={< ScienceSeminarSciTech/>} layout={Layout} />} />
        <Route path="/admin/article-review-department" element={<PrivateRoute element={< ListArticleReviewDepartment/>} layout={Layout} />} />
        <Route path="/admin/article-review-scitech" element={<PrivateRoute element={< ListArticleReviewSciTech/>} layout={Layout} />} />
        <Route path="/admin/approval-of-school-topic-department" element={<PrivateRoute element={< ApprovalOfSchoolTopicDepartment/>} layout={Layout} />} />
        <Route path="/admin/approval-of-school-topic-sciTech" element={<PrivateRoute element={< ApprovalOfSchoolTopicSciTech/>} layout={Layout} />} />

        
        {/* Các route cho LayoutDepartment */}
        <Route path="/department" element={<PrivateRoute element={<Statistics />} layout={LayoutDepartment} />} />
        <Route path="/department/lecturer-topic-approval" element={<PrivateRoute element={<LecturerTopicApproval />} layout={LayoutDepartment} />} />
        <Route path="/department/student-topic-approval" element={<PrivateRoute element={<StudentTopicApproval />} layout={LayoutDepartment} />} />
        <Route path="/department/article-review" element={<PrivateRoute element={<ArticleReview />} layout={LayoutDepartment} />} />
        <Route path="/department/science-seminar" element={<PrivateRoute element={<ScienceSeminar />} layout={LayoutDepartment} />} />
        <Route path="/department/edit-student" element={<PrivateRoute element={<StudentManager />} layout={LayoutDepartment} />} />
        <Route path="/department/product-manager" element={<PrivateRoute element={<Product />} layout={LayoutDepartment} />} />
        <Route path="/department/application-approval" element={<PrivateRoute element={<ApplicationApproval />} layout={LayoutDepartment} />} />
        <Route path="/department/application-approval-admin" element={<PrivateRoute element={<ApplicationApprovalAdmin />} layout={LayoutDepartment} />} />
        <Route path="/department/lecturer-application-approval-admin" element={<PrivateRoute element={<LecturerApplicationApprovalListAdmin />} layout={LayoutDepartment} />} />
        <Route path="/department/lecturer-application-approval-list-admin" element={<PrivateRoute element={<LecturerApplicationApprovalList />} layout={LayoutDepartment} />} />

        {/* Các route cho LayoutSciTech */}
        <Route path="/scitech" element={<PrivateRoute element={<Statistics />} layout={LayoutSciTech} />} />
        <Route path="/scitech/lecturer-topic-approval" element={<PrivateRoute element={<LecturerTopicApproval />} layout={LayoutSciTech} />} />
        <Route path="/scitech/student-topic-approval" element={<PrivateRoute element={<StudentTopicApproval />} layout={LayoutSciTech} />} />
        <Route path="/scitech/article-review" element={<PrivateRoute element={<ArticleReview />} layout={LayoutSciTech} />} />
        <Route path="/scitech/science-seminar" element={<PrivateRoute element={<ScienceSeminar />} layout={LayoutSciTech} />} />
        <Route path="/scitech/edit-student" element={<PrivateRoute element={<StudentManager />} layout={LayoutSciTech} />} />
        <Route path="/scitech/product-manager" element={<PrivateRoute element={<Product />} layout={LayoutSciTech} />} />
        <Route path="/scitech/application-approval" element={<PrivateRoute element={<ApplicationApproval />} layout={LayoutSciTech} />} />
        <Route path="/scitech/application-approval-admin" element={<PrivateRoute element={<ApplicationApprovalAdmin />} layout={LayoutSciTech} />} />
        <Route path="/scitech/lecturer-application-approval-admin" element={<PrivateRoute element={<LecturerApplicationApprovalListAdmin />} layout={LayoutSciTech} />} />
        <Route path="/scitech/lecturer-application-approval-list-admin" element={<PrivateRoute element={<LecturerApplicationApprovalList />} layout={LayoutSciTech} />} />

        {/* Các route không có Layout */}
        <Route path="/authcallback" element={<LecturerApplicationApprovalList element={<AuthCallback />} />} />
      </Routes>
    </Router>
  );
}

export default App;
