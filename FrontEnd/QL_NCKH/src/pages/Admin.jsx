import React from 'react'
import Layout from '../component/Layout'
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import ArticleReview from '../component/ArticleReview/ArticleReview';
import TopicApproval from '../component/LecturerTopicApproval/TopicApproval'
import StudentTopicApproval from '../component/StudentTopicApproval/StudentTopicApproval';
import ScienceSeminar from '../component/ScienceSeminar/ScienceSeminar';
import Login from '../component/Login/LoginLayout';

function Admin() {
    return (
        <>
            <Layout>
                <Routes>
                    <Route path='/article-review' element={<ArticleReview/>} />
                    <Route path='/lecturer-topic-approval' element={<TopicApproval/>} />
                    <Route path='/student-topic-approval' element={<StudentTopicApproval/>} />
                    <Route path='/science-seminar' element={<ScienceSeminar/>} />

                </Routes>
            </Layout>
        </>
    )
}

export default Admin