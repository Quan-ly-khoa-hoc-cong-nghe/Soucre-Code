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
    setIsEditModalOpen(true); // Open edit modal
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
    // Tự động gán MaHoSo là 1 và trạng thái là "Khoa đã duyệt"
    const formDataWithAutoId = {
      ...createFormData,
      MaHoSo: "1",  // Gán MaHoSo là 1
      TrangThai: "Khoa đã duyệt", // Gán trạng thái mặc định
      FileHoSo: createFormData.FileHoSo, // Lấy tên file đã chọn từ input
    };
  
    axios
      .post(
        "http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiSV/HoSoNCKHSV_Api.php?action=add",
        formDataWithAutoId
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
  
  // Helper function to get department name by MaKhoa
  const getDepartmentName = (maKhoa) => {
    const department = departments.find((dept) => dept.MaKhoa === maKhoa);
    return department ? department.TenKhoa : "Unknown Department";
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
            <th className="px-4 py-2 border">Department</th>{" "}
            {/* Changed to display department name */}
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
                  {getDepartmentName(app.MaKhoa)}{" "}
                  {/* Display department name */}
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
                colSpan="6"
                className="px-4 py-2 border text-center text-gray-500"
              >
                No applications found.
              </td>
            </tr>
          )}
        </tbody>
      </table>

      {/* Edit Modal */}
      {isEditModalOpen && editFormData && (
        <div className="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
          <div className="bg-white rounded-lg p-6 max-w-2xl w-full shadow-lg">
            <h2 className="text-2xl font-bold mb-4">Edit Application</h2>
            <form onSubmit={handleEditSubmit} className="space-y-4">
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label className="block text-sm font-medium mb-1">
                    Application ID
                  </label>
                  <input
                    type="text"
                    value={editFormData.MaHoSo}
                    readOnly
                    className="w-full px-4 py-2 border rounded-lg bg-gray-100"
                  />
                </div>
                <div>
                  <label className="block text-sm font-medium mb-1">
                    Submission Date
                  </label>
                  <input
                    type="date"
                    value={editFormData.NgayNop}
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
  <label className="block text-sm font-medium mb-1">File</label>
  <input
    type="file"
    onChange={(e) => {
      const fileName = e.target.files[0]?.name || ""; // Lấy tên file
      setCreateFormData((prev) => ({
        ...prev,
        FileHoSo: fileName, // Lưu tên file vào state
      }));
    }}
    className="w-full px-4 py-2 border rounded-lg"
  />
</div>

                <div>
                  <label className="block text-sm font-medium mb-1">
                    Status
                  </label>
                  <input
                    type="text"
                    value={editFormData.TrangThai}
                    readOnly
                    className="w-full px-4 py-2 border rounded-lg bg-gray-100"
                  />
                </div>
                <div>
                  <label className="block text-sm font-medium mb-1">
                    Department
                  </label>
                  <select
                    value={editFormData.MaKhoa}
                    onChange={(e) =>
                      setEditFormData((prev) => ({
                        ...prev,
                        MaKhoa: e.target.value,
                      }))
                    }
                    className="w-full px-4 py-2 border rounded-lg"
                  >
                    {departments.map((dept) => (
                      <option key={dept.MaKhoa} value={dept.MaKhoa}>
                        {dept.TenKhoa}
                      </option>
                    ))}
                  </select>
                </div>
              </div>
              <div className="flex justify-end gap-4 mt-4">
                <button
                  type="button"
                  className="bg-gray-500 text-white px-4 py-2 rounded-lg"
                  onClick={() => setIsEditModalOpen(false)}
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  className="bg-blue-500 text-white px-4 py-2 rounded-lg"
                >
                  Save Changes
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

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
                    readOnly
                    className="w-full px-4 py-2 border rounded-lg bg-gray-100"
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
  <label className="block text-sm font-medium mb-1">File</label>
  <input
    type="file"
    onChange={(e) => {
      const fileName = e.target.files[0]?.name || ""; // Lấy tên file
      setCreateFormData((prev) => ({
        ...prev,
        FileHoSo: fileName, // Lưu tên file vào state
      }));
    }}
    className="w-full px-4 py-2 border rounded-lg"
  />
</div>

                <div>
                  <label className="block text-sm font-medium mb-1">
                    Status
                  </label>
                  <input
                    type="text"
                    value={createFormData.TrangThai}
                    readOnly
                    className="w-full px-4 py-2 border rounded-lg bg-gray-100"
                  />
                </div>
                <div>
                  <label className="block text-sm font-medium mb-1">
                    Department
                  </label>
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
                    {departments.map((dept) => (
                      <option key={dept.MaKhoa} value={dept.MaKhoa}>
                        {dept.TenKhoa}
                      </option>
                    ))}
                  </select>
                </div>
              </div>
              <div className="flex justify-end gap-4 mt-4">
                <button
                  type="button"
                  className="bg-gray-500 text-white px-4 py-2 rounded-lg"
                  onClick={() => setIsCreateModalOpen(false)}
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  className="bg-green-500 text-white px-4 py-2 rounded-lg"
                >
                  Save
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
