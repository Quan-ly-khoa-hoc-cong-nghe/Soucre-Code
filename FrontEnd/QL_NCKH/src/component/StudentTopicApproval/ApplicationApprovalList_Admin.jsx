import React, { useState, useEffect } from "react";
import { FaEye, FaCheck, FaTimes } from 'react-icons/fa';
import axios from "axios";

const ApplicationApprovalListAdmin = () => {
  const [applications, setApplications] = useState([]);
  const [departments, setDepartments] = useState([]);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [selectedApp, setSelectedApp] = useState(null);

  useEffect(() => {
    fetchApplications();
    fetchDepartments();
  }, []);

  const fetchApplications = () => {
    axios
      .get(
        "http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiSV/HoSoNCKHSV_Api.php?action=get"
      )
      .then((response) => {
        setApplications(response.data.HoSoNCKHSV || []);
      })
      .catch((error) => {
        console.error("Error fetching applications:", error);
      });
  };

  const fetchDepartments = () => {
    axios
      .get(
        "http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiSV/Khoa_Api.php?action=get"
      )
      .then((response) => {
        setDepartments(response.data.Khoa || []);
      })
      .catch((error) => {
        console.error("Error fetching departments:", error);
      });
  };
  const approveApplication = (app) => {
    const requestData = {
      MaHoSo: app.MaHoSo,    // Mã hồ sơ
      TrangThai: "Đã duyệt", // Trạng thái mới
    };
  
    // Log thông tin gửi đi để kiểm tra
    console.log("Gửi yêu cầu cập nhật trạng thái:", requestData);
  
    axios
      .put(
        "http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiSV/HoSoNCKHSV_Api.php?action=updateTrangThai", 
        requestData
      )
      .then((response) => {
        console.log("Phản hồi từ server:", response.data);
  
        if (response.data.message === "Cập nhật trạng thái hồ sơ thành công") {
          alert("Cập nhật trạng thái thành công!"); // Hiển thị thông báo thành công
        } else {
          alert("Có lỗi xảy ra: " + response.data.message); // Thông báo lỗi nếu có
        }
  
        fetchApplications(); // Lấy lại danh sách ứng dụng sau khi cập nhật
      })
      .catch((error) => {
        console.error("Error approving application:", error);
        alert("Đã xảy ra lỗi khi gửi yêu cầu: " + error.message); // Thông báo lỗi khi gặp sự cố
      });
  };
  

  const openModal = (app) => {
    setSelectedApp(app);
    setIsModalOpen(true);
  };

  const closeModal = () => {
    setIsModalOpen(false);
    setSelectedApp(null);
  };

  return (
    <div className="bg-white shadow-lg rounded-lg p-6">
      <table className="w-full border-collapse border border-gray-200">
        <thead className="bg-gray-100">
          <tr>
            <th className="px-4 py-2 border">Application ID</th>
            <th className="px-4 py-2 border">Submission Date</th>
            <th className="px-4 py-2 border">File</th>
            <th className="px-4 py-2 border">Status</th>
            <th className="px-4 py-2 border">Actions</th>
          </tr>
        </thead>
        <tbody>
          {applications.length > 0 ? (
            applications.map((app) => (
              <tr key={app.MaHoSo} className="hover:bg-gray-50">
                <td className="px-4 py-2 border">{app.MaHoSo}</td>
                <td className="px-4 py-2 border">{app.NgayNop}</td>
                <td className="px-4 py-2 border">
                  <a
                    href={`http://localhost/uploads/${app.FileHoSo}`}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="text-blue-500 hover:underline"
                  >
                    {app.FileHoSo}
                  </a>
                </td>
                <td className="px-4 py-2 border">
                  <span
                    className={`px-2 py-1 rounded-full text-sm ${
                      app.TrangThai === "Khoa đã duyệt"
                        ? "bg-blue-100 text-blue-800"
                        : app.TrangThai === "Đã duyệt"
                        ? "bg-green-100 text-green-800"
                        : "bg-red-100 text-red-800"
                    }`}
                  >
                    {app.TrangThai}
                  </span>
                </td>
                <td className="py-4 px-2 text-right">
  <div className="flex justify-end space-x-2">
    {app.TrangThai !== "Đã duyệt" && app.TrangThai !== "Hủy" ? (
      <>
       <button
  className="p-2 text-green-600 hover:bg-green-100 rounded-full"
  title="Approve"
  onClick={() => approveApplication(app)} // Gọi hàm approveApplication với hồ sơ app
>
  <FaCheck className="w-5 h-5" />
</button>


        <button
          className="p-2 text-red-600 hover:bg-red-100 rounded-full"
          title="Reject"
        >
          <FaTimes className="w-5 h-5" />
        </button>
      </>
    ) : null}

    {/* Only show "Add Topic" button if the status is not "Khoa đã duyệt" or "Hủy" */}
    {app.TrangThai !== "Khoa đã duyệt" && app.TrangThai !== "Hủy" && (
      <button
        className="text-green-600 hover:underline text-sm font-medium"
        onClick={() => openModal(app)}
      >
        Thêm đề tài
      </button>
    )}

    <button
      className="p-2 text-blue-600 hover:bg-blue-100 rounded-full"
      title="View Details"
    >
      <FaEye className="w-5 h-5" />
    </button>
  </div>
</td>

              </tr>
            ))
          ) : (
            <tr>
              <td
                colSpan="5"
                className="px-4 py-2 border text-center text-gray-500"
              >
                No applications found.
              </td>
            </tr>
          )}
        </tbody>
      </table>

      {/* Modal */}
      {isModalOpen && selectedApp && (
        <div className="fixed inset-0 bg-gray-500 bg-opacity-75 flex justify-center items-center z-50">
          <div className="bg-white p-6 rounded-lg shadow-lg w-1/2">
            <h2 className="text-xl font-semibold mb-4">Thêm Đề Tài</h2>
            <p><strong>Application ID:</strong> {selectedApp.MaHoSo}</p>
            <p><strong>Submission Date:</strong> {selectedApp.NgayNop}</p>
            {/* Here you can add more fields to show details or a form for "Adding Topic" */}
            <div className="mt-4">
              <textarea
                className="w-full h-32 p-2 border border-gray-300 rounded-lg"
                placeholder="Enter topic details here..."
              ></textarea>
            </div>
            <div className="mt-4 flex justify-end space-x-2">
              <button
                onClick={closeModal}
                className="px-4 py-2 bg-gray-300 text-gray-700 rounded-md"
              >
                Close
              </button>
              <button className="px-4 py-2 bg-blue-600 text-white rounded-md">
                Save Topic
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};

export default ApplicationApprovalListAdmin;
