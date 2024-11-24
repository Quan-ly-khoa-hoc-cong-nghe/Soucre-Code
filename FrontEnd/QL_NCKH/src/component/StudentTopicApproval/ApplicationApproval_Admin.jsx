import React from "react";
import ApplicationApprovalListAdmin from "./ApplicationApprovalList_Admin";

const ApplicationApprovalAdmin = () => {
  return (
    <div className="min-h-screen bg-gray-50 p-8">
      <div className="container mx-auto">
        <div className="flex items-center justify-between mb-6">
          <h1 className="text-2xl font-semibold">Đuyệt hồ sơ thêm đè tài</h1>
          <nav className="text-sm">
            <span className="text-gray-500">Dashboard</span>
            <span className="mx-2">/</span>
            <span>Application Approval</span>
          </nav>
        </div>
      </div>
      <ApplicationApprovalListAdmin/>
    </div>
  );
};

export default ApplicationApprovalAdmin;
