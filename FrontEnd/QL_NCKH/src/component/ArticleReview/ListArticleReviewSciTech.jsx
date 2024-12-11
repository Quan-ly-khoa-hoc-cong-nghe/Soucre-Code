import React, { useState, useEffect } from "react";
import { FaEye, FaCheck, FaTimes } from "react-icons/fa";
import axios from "axios";

const ListArticleReviewSciTech = () => {
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
        "http://localhost/Soucre-Code/BackEnd/Api/ThamDinhBaiBaoApi/HoSoBaiBaoKH_Api.php?action=get"
      )
      .then((response) => {
        setApplications(response.data || []); // Make sure the response matches the structure
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

  // Function to get the department name by MaKhoa
  const getDepartmentName = (maKhoa) => {
    const department = departments.find(dep => dep.MaKhoa === maKhoa);
    return department ? department.TenKhoa : "Unknown Department";
  };

  const approveApplication = (app) => {
    const requestData = {
      MaHoSo: app.MaHoSo, // Mã hồ sơ
      TrangThai: "Đã duyệt", // Trạng thái mới
    };

    axios
      .put(
        "http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiGV/HoSoNCKHGV_Api.php?action=updateTrangThai",
        requestData
      )
      .then((response) => {
        if (response.data.message === "Cập nhật trạng thái hồ sơ thành công") {
          alert("Cập nhật trạng thái thành công!");
        } else {
          alert("Có lỗi xảy ra: " + response.data.message);
        }

        fetchApplications();
      })
      .catch((error) => {
        console.error("Error approving application:", error);
        alert("Đã xảy ra lỗi khi gửi yêu cầu: " + error.message);
      });
  };

  const approveApplicationCancel = (app) => {
    const requestData = {
      MaHoSo: app.MaHoSo, // Mã hồ sơ
      TrangThai: "Hủy", // Trạng thái mới
    };

    axios
      .put(
        "http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiGV/HoSoNCKHGV_Api.php?action=updateTrangThai",
        requestData
      )
      .then((response) => {
        if (response.data.message === "Cập nhật trạng thái hồ sơ thành công") {
          alert("Cập nhật trạng thái thành công!");
        } else {
          alert("Có lỗi xảy ra: " + response.data.message);
        }

        fetchApplications();
      })
      .catch((error) => {
        console.error("Error rejecting application:", error);
        alert("Đã xảy ra lỗi khi gửi yêu cầu: " + error.message);
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
            <th className="px-4 py-2 border">Department</th> {/* New column for department */}
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
                    href={`http://localhost/uploads/${app.fileHoSo}`} // Make sure to use 'fileHoSo'
                    target="_blank"
                    rel="noopener noreferrer"
                    className="text-blue-500 hover:underline"
                  >
                    {app.fileHoSo} {/* Display the file name */}
                  </a>
                </td>
                <td className="px-4 py-2 border">{getDepartmentName(app.MaKhoa)}</td> {/* Show department name */}

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
                          onClick={() => approveApplication(app)} // Call approveApplication
                        >
                          <FaCheck className="w-5 h-5" />
                        </button>

                        <button
                          className="p-2 text-red-600 hover:bg-red-100 rounded-full"
                          title="Reject"
                          onClick={() => approveApplicationCancel(app)} // Call approveApplicationCancel
                        >
                          <FaTimes className="w-5 h-5" />
                        </button>
                      </>
                    ) : null}

                    {app.TrangThai !== "Khoa đã duyệt" && app.TrangThai !== "Hủy" && (
                      <button
                        className="text-green-600 hover:underline text-sm font-medium"
                        onClick={() => openModal(app)}
                      >
                        Thêm bài báo
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
                colSpan="6"
                className="px-4 py-2 border text-center text-gray-500"
              >
                No applications found.
              </td>
            </tr>
          )}
        </tbody>
      </table>

      {isModalOpen && selectedApp && (
        <div className="fixed inset-0 bg-gray-500 bg-opacity-75 flex justify-center items-center z-50">
          <div className="bg-white p-6 rounded-lg shadow-lg w-1/2">
            <h2 className="text-xl font-semibold mb-4">Thêm Đề Tài</h2>
            <p>
              <strong>Application ID:</strong> {selectedApp.MaHoSo}
            </p>
            <p>
              <strong>Submission Date:</strong> {selectedApp.NgayNop}
            </p>
            <div className="mt-4">
              <textarea
                className="w-full h-32 p-2 border border-gray-300 rounded-lg"
                placeholder="Enter topic details here..."
              ></textarea>
            </div>
            <div className="mt-4 flex justify-end space-x-2">
              <button
                className="px-4 py-2 bg-green-600 text-white rounded-md"
                onClick={closeModal}
              >
                Save
              </button>
              <button
                className="px-4 py-2 bg-gray-400 text-white rounded-md"
                onClick={closeModal}
              >
                Cancel
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};

export default ListArticleReviewSciTech;
