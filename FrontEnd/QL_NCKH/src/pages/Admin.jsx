import React from 'react'
import Layout from '../component/Layout'
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import ArticleReview from '../component/ArticleReview/ArticleReview';
import TopicApproval from '../component/TopicApproval/TopicApproval';
import Login from '../component/Login/LoginLayout';

function Admin() {
    return (
        <>
            <Layout>
                <Routes>
                    <Route path='/article-review' element={<ArticleReview/>} />
                    <Route path='/topic-approal' element={<TopicApproval/>} />
                </Routes>
            </Layout>
        </>
    )
}

export default Admin