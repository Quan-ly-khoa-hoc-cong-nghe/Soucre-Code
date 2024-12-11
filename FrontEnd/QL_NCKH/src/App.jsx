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
import ApprovalOffCampusProjectDepartment from './component/OffCampusProject/ApprovalOffCampusProjectDepartment';
import EditLecturer from './component/LecturerTopicApproval/EditLecturer';
import ApprovalOffCampusProjectSciTech from './component/OffCampusProject/ApprovalOffCampusProjectSciTech';
import ProductLecturer from './component/LecturerTopicApproval/Product';
import ReportLecturer from './component/LecturerTopicApproval/ReportLecturer';
import ThamDinhBaiBao from './component/ArticleReview/thamdinhbaibao';
import Quanlydetaicapso from './component/School-LevelTopic/quanydetai';
import Sanphamdtcs from './component/School-LevelTopic/sanphamdtcs';
import Nhomdtcs from './component/School-LevelTopic/Nhomdtcs';
import Quanlydondathang from './component/OffCampusProject/Quanlydondathang';
import Sanphamngoaitruong from './component/OffCampusProject/Sanphamduanngoaitruong';
import Nhomdtnt from './component/OffCampusProject/Nhomdtnt';
import Quanlytailieu from './component/ScienceSeminar/tailieu';
import HoiThaoKhoaHoc from './component/ScienceSeminar/hoithao';
import PhienHoiThao from './component/ScienceSeminar/phienhoithao';
import NguoiThamGia from './component/ScienceSeminar/nguoithamgia';
import VaitroHoiThao from './component/ScienceSeminar/vaitrotronghoithao';

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
        <Route path="/admin/phienhoithao" element={<PrivateRoute element={<PhienHoiThao />} layout={Layout} />} />
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
        <Route path="/admin/approval-off-campus-project-department" element={<PrivateRoute element={< ApprovalOffCampusProjectDepartment/>} layout={Layout} />} />
        <Route path="/admin/approval-off-campus-project-scitech" element={<PrivateRoute element={< ApprovalOffCampusProjectSciTech/>} layout={Layout} />} />
        <Route path="/admin/edit-lecturer" element={<PrivateRoute element={<EditLecturer />} layout={Layout} />} />
        <Route path="/admin/product-lecturer" element={<PrivateRoute element={<ProductLecturer />} layout={Layout} />} />
        <Route path="/admin/report" element={<PrivateRoute element={<ReportLecturer />} layout={Layout} />} />
        <Route path="/admin/thamdinhbaibao" element={<PrivateRoute element={<ThamDinhBaiBao />} layout={Layout} />} />
        <Route path="/admin/quanlydetaicapso" element={<PrivateRoute element={<Quanlydetaicapso />} layout={Layout} />} />
        <Route path="/admin/sanphamcapso" element={<PrivateRoute element={<Sanphamdtcs />} layout={Layout} />} />
        <Route path="/admin/nhomdetaicapso" element={<PrivateRoute element={<Nhomdtcs />} layout={Layout} />} />
        <Route path="/admin/dondathang" element={<PrivateRoute element={<Quanlydondathang />} layout={Layout} />} />
        <Route path="/admin/sanphamngoaitruong" element={<PrivateRoute element={<Sanphamngoaitruong />} layout={Layout} />} />
        <Route path="/admin/nhomduannt" element={<PrivateRoute element={<Nhomdtnt />} layout={Layout} />} />
        <Route path="/admin/tailieu" element={<PrivateRoute element={<Quanlytailieu />} layout={Layout} />} />
        <Route path="/admin/hoithao" element={<PrivateRoute element={<HoiThaoKhoaHoc />} layout={Layout} />} />
        <Route path="/admin/nguoithamgia" element={<PrivateRoute element={<NguoiThamGia />} layout={Layout} />} />
        <Route path="/admin/vaitrohoithao" element={<PrivateRoute element={<VaitroHoiThao />} layout={Layout} />} />



        
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
        <Route path="/department/science-seminar-departments" element={<PrivateRoute element={<ScienceSeminardepartments/>} layout={LayoutDepartment} />} />
        <Route path="/department/science-seminar-sciTech" element={<PrivateRoute element={< ScienceSeminarSciTech/>} layout={LayoutDepartment} />} />
        <Route path="/department/article-review-department" element={<PrivateRoute element={< ListArticleReviewDepartment/>} layout={LayoutDepartment} />} />
        <Route path="/department/article-review-scitech" element={<PrivateRoute element={< ListArticleReviewSciTech/>} layout={LayoutDepartment} />} />
        <Route path="/department/approval-of-school-topic-department" element={<PrivateRoute element={< ApprovalOfSchoolTopicDepartment/>} layout={LayoutDepartment} />} />
        <Route path="/department/approval-of-school-topic-sciTech" element={<PrivateRoute element={< ApprovalOfSchoolTopicSciTech/>} layout={LayoutDepartment} />} />
        <Route path="/department/approval-off-campus-project-department" element={<PrivateRoute element={< ApprovalOffCampusProjectDepartment/>} layout={LayoutDepartment} />} />
        <Route path="/department/approval-off-campus-project-scitech" element={<PrivateRoute element={< ApprovalOffCampusProjectSciTech/>} layout={LayoutDepartment} />} />
        <Route path="/department/phienhoithao" element={<PrivateRoute element={<PhienHoiThao />} layout={LayoutDepartment} />} />

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
        <Route path="/scitech/science-seminar-departments" element={<PrivateRoute element={<ScienceSeminardepartments/>} layout={LayoutSciTech} />} />
        <Route path="/scitech/science-seminar-sciTech" element={<PrivateRoute element={< ScienceSeminarSciTech/>} layout={LayoutSciTech} />} />
        <Route path="/scitech/article-review-department" element={<PrivateRoute element={< ListArticleReviewDepartment/>} layout={LayoutSciTech} />} />
        <Route path="/scitech/article-review-scitech" element={<PrivateRoute element={< ListArticleReviewSciTech/>} layout={LayoutSciTech} />} />
        <Route path="/scitech/approval-of-school-topic-department" element={<PrivateRoute element={< ApprovalOfSchoolTopicDepartment/>} layout={LayoutSciTech} />} />
        <Route path="/scitech/approval-of-school-topic-sciTech" element={<PrivateRoute element={< ApprovalOfSchoolTopicSciTech/>} layout={LayoutSciTech} />} />
        <Route path="/scitech/approval-off-campus-project-department" element={<PrivateRoute element={< ApprovalOffCampusProjectDepartment/>} layout={LayoutSciTech} />} />
        <Route path="/scitech/approval-off-campus-project-scitech" element={<PrivateRoute element={< ApprovalOffCampusProjectSciTech/>} layout={LayoutSciTech} />} />

        {/* Các route không có Layout */}
        <Route path="/authcallback" element={<LecturerApplicationApprovalList element={<AuthCallback />} />} />
      </Routes>
    </Router>
  );
}

export default App;
