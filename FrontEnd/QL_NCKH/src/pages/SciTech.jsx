import React from 'react';
import LayoutSciTech from '../component/LayoutSciTech';  // Layout dành cho SciTech
import { Routes, Route } from 'react-router-dom';
import ArticleReview from '../component/ArticleReview/ArticleReview';
import TopicApproval from '../component/LecturerTopicApproval/TopicApproval';
import StudentTopicApproval from '../component/StudentTopicApproval/StudentTopicApproval';
import ScienceSeminar from '../component/ScienceSeminar/ScienceSeminar';

function SciTech() {
  return (
    <LayoutSciTech>
      <Routes>
        <Route path="article-review" element={<ArticleReview />} />
        <Route path="lecturer-topic-approval" element={<TopicApproval />} />
        <Route path="student-topic-approval" element={<StudentTopicApproval />} />
        <Route path="science-seminar" element={<ScienceSeminar />} />
      </Routes>
    </LayoutSciTech>
  );
}

export default SciTech;
