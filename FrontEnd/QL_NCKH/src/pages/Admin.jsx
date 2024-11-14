import React from 'react';
import LayoutAdmin from '../component/Layout';  // Layout dành cho Admin
import { Routes, Route } from 'react-router-dom';
import ArticleReview from '../component/ArticleReview/ArticleReview';
import TopicApproval from '../component/LecturerTopicApproval/TopicApproval';
import StudentTopicApproval from '../component/StudentTopicApproval/StudentTopicApproval';
import ScienceSeminar from '../component/ScienceSeminar/ScienceSeminar';

function Admin() {
  return (
    <LayoutAdmin>
      <Routes>
        <Route path="article-review" element={<ArticleReview />} />
        <Route path="lecturer-topic-approval" element={<TopicApproval />} />
        <Route path="student-topic-approval" element={<StudentTopicApproval />} />
        <Route path="science-seminar" element={<ScienceSeminar />} />
      </Routes>
    </LayoutAdmin>
  );
}

export default Admin;
