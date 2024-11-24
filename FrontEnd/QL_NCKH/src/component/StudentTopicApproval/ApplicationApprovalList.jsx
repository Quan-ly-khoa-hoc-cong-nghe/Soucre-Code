import React, { useState, useEffect } from "react";
import axios from "axios";

const ApplicationApprovalList = () => {
  const [applications, setApplications] = useState([]);
  const [departments, setDepartments] = useState([]);
  const [editFormData, setEditFormData] = useState(null);
  const [createFormData, setCreateFormData] = useState({
    MaHoSo: "",
    NgayNop: "",
    FileHoSo: "",
    TrangThai: "",
    MaKhoa: "",
  });

  const [isEditModalOpen, setIsEditModalOpen] = useState(false);
  const [isCreateModalOpen, setIsCreateModalOpen] = useState(false);

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

  const handleEdit = (application) => {
    setEditFormData({ ...application });
    setIsEditModalOpen(true); // Mở modal chỉnh sửa
  };

  const handleEditSubmit = (e) => {
    e.preventDefault();
    axios
      .post(
        "http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiSV/HoSoNCKHSV_Api.php?action=update",
        editFormData
      )
      .then((response) => {
        alert(response.data.message || "Application updated successfully!");
        setIsEditModalOpen(false);
        fetchApplications(); // Refresh the list
      })
      .catch((error) => {
        console.error("Error updating application:", error);
      });
  };

  const handleDelete = (maHoSo) => {
    if (window.confirm("Are you sure you want to delete this application?")) {
      axios
        .post(
          "http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiSV/HoSoNCKHSV_Api.php?action=delete",
          { MaHoSo: maHoSo }
        )
        .then((response) => {
          alert(response.data.message || "Application deleted successfully!");
          fetchApplications(); // Refresh the list
        })
        .catch((error) => {
          console.error("Error deleting application:", error);
        });
    }
  };

  const handleCreateSubmit = (e) => {
    e.preventDefault();
    axios
      .post(
        "http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiSV/HoSoNCKHSV_Api.php?action=add",
        createFormData
      )
      .then((response) => {
        alert(response.data.message || "New application added successfully!");
        setIsCreateModalOpen(false);
        fetchApplications(); // Refresh the list
      })
      .catch((error) => {
        console.error("Error adding application:", error);
      });
  };

  return (
    <div className="bg-white shadow-lg rounded-lg p-6">
      <button
        onClick={() => setIsCreateModalOpen(true)}
        className="bg-green-500 text-white px-4 py-2 rounded-lg mb-4"
      >
        Add New Application
      </button>
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
                <td className="px-4 py-2 border">
                  <button
                    className="text-blue-500 hover:underline mr-2"
                    onClick={() => handleEdit(app)}
                  >
                    Edit
                  </button>
                  <button
                    className="text-red-500 hover:underline"
                    onClick={() => handleDelete(app.MaHoSo)}
                  >
                    Delete
                  </button>
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

      {/* Create Modal */}
      {isCreateModalOpen && (
        <div className="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
          <div className="bg-white rounded-lg p-6 max-w-2xl w-full shadow-lg">
            <h2 className="text-2xl font-bold mb-4">Add New Application</h2>
            <form onSubmit={handleCreateSubmit} className="space-y-4">
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label className="block text-sm font-medium mb-1">
                    Application ID
                  </label>
                  <input
                    type="text"
                    value={createFormData.MaHoSo}
                    onChange={(e) =>
                      setCreateFormData((prev) => ({
                        ...prev,
                        MaHoSo: e.target.value,
                      }))
                    }
                    className="w-full px-4 py-2 border rounded-lg"
                  />
                </div>
                <div>
                  <label className="block text-sm font-medium mb-1">
                    Submission Date
                  </label>
                  <input
                    type="date"
                    value={createFormData.NgayNop}
                    onChange={(e) =>
                      setCreateFormData((prev) => ({
                        ...prev,
                        NgayNop: e.target.value,
                      }))
                    }
                    className="w-full px-4 py-2 border rounded-lg"
                  />
                </div>
                <div>
                  <label className="block text-sm font-medium mb-1">Status</label>
                  <input
                    type="text"
                    value={createFormData.TrangThai}
                    onChange={(e) =>
                      setCreateFormData((prev) => ({
                        ...prev,
                        TrangThai: e.target.value,
                      }))
                    }
                    className="w-full px-4 py-2 border rounded-lg"
                  />
                </div>
                <div>
                  <label className="block text-sm font-medium mb-1">File Name</label>
                  <input
                    type="text"
                    value={createFormData.FileHoSo}
                    onChange={(e) =>
                      setCreateFormData((prev) => ({
                        ...prev,
                        FileHoSo: e.target.value,
                      }))
                    }
                    className="w-full px-4 py-2 border rounded-lg"
                  />
                </div>
                <div>
                  <label className="block text-sm font-medium mb-1">Department</label>
                  <select
                    value={createFormData.MaKhoa}
                    onChange={(e) =>
                      setCreateFormData((prev) => ({
                        ...prev,
                        MaKhoa: e.target.value,
                      }))
                    }
                    className="w-full px-4 py-2 border rounded-lg"
                  >
                    <option value="">Select Department</option>
                    {departments.map((department) => (
                      <option key={department.MaKhoa} value={department.MaKhoa}>
                        {department.TenKhoa}
                      </option>
                    ))}
                  </select>
                </div>
              </div>
              <div className="flex justify-end mt-6">
                <button
                  type="submit"
                  className="bg-blue-500 text-white px-4 py-2 rounded-lg mr-2"
                >
                  Save
                </button>
                <button
                  type="button"
                  onClick={() => setIsCreateModalOpen(false)}
                  className="bg-gray-500 text-white px-4 py-2 rounded-lg"
                >
                  Cancel
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* Edit Modal */}
      {isEditModalOpen && (
        <div className="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
          <div className="bg-white rounded-lg p-6 max-w-2xl w-full shadow-lg">
            <h2 className="text-2xl font-bold mb-4">Edit Application</h2>
            <form onSubmit={handleEditSubmit} className="space-y-4">
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label className="block text-sm font-medium mb-1">
                    Submission Date
                  </label>
                  <input
                    type="date"
                    value={editFormData.NgayNop || ""}
                    onChange={(e) =>
                      setEditFormData((prev) => ({
                        ...prev,
                        NgayNop: e.target.value,
                      }))
                    }
                    className="w-full px-4 py-2 border rounded-lg"
                  />
                </div>
                <div>
                  <label className="block text-sm font-medium mb-1">Status</label>
                  <input
                    type="text"
                    value={editFormData.TrangThai || ""}
                    onChange={(e) =>
                      setEditFormData((prev) => ({
                        ...prev,
                        TrangThai: e.target.value,
                      }))
                    }
                    className="w-full px-4 py-2 border rounded-lg"
                  />
                </div>
                <div>
                  <label className="block text-sm font-medium mb-1">File Name</label>
                  <input
                    type="text"
                    value={editFormData.FileHoSo || ""}
                    onChange={(e) =>
                      setEditFormData((prev) => ({
                        ...prev,
                        FileHoSo: e.target.value,
                      }))
                    }
                    className="w-full px-4 py-2 border rounded-lg"
                  />
                </div>
                <div>
                  <label className="block text-sm font-medium mb-1">Department</label>
                  <select
                    value={editFormData.MaKhoa || ""}
                    onChange={(e) =>
                      setEditFormData((prev) => ({
                        ...prev,
                        MaKhoa: e.target.value,
                      }))
                    }
                    className="w-full px-4 py-2 border rounded-lg"
                  >
                    <option value="">Select Department</option>
                    {departments.map((department) => (
                      <option key={department.MaKhoa} value={department.MaKhoa}>
                        {department.TenKhoa}
                      </option>
                    ))}
                  </select>
                </div>
              </div>
              <div className="flex justify-end mt-6">
                <button
                  type="submit"
                  className="bg-blue-500 text-white px-4 py-2 rounded-lg mr-2"
                >
                  Save
                </button>
                <button
                  type="button"
                  onClick={() => setIsEditModalOpen(false)}
                  className="bg-gray-500 text-white px-4 py-2 rounded-lg"
                >
                  Cancel
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
};

export default ApplicationApprovalList;
