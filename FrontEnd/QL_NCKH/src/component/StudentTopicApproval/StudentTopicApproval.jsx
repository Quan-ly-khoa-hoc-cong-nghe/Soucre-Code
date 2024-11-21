import React from "react";
import TopicList from "./TopicList";

const StudentTopicApproval = () => {
  return (
    <div className="min-h-screen bg-gray-50 p-8">
      <div className="container mx-auto">
        <div className="flex items-center justify-between mb-6">
          <h1 className="text-2xl font-semibold">Topic Approval</h1>
          <nav className="text-sm">
            <span className="text-gray-500">Dashboard</span>
            <span className="mx-2">/</span>
            <span>Topic Approval</span>
          </nav>
        </div>
        <TopicList />
        </div>
    </div>
  );
};

export default StudentTopicApproval;
