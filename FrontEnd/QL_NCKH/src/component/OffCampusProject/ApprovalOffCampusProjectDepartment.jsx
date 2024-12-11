import React, { useState, useEffect } from "react";
import { FaCheck, FaTimes, FaEye } from "react-icons/fa";
import axios from "axios";

const ApprovalOffCampusProjectDepartment = () => {
  const [articles, setArticles] = useState([]);
  const [departments, setDepartments] = useState([]); // For storing Khoa data
  const [editFormData, setEditFormData] = useState(null);
  const [createFormData, setCreateFormData] = useState({
    MaHoSo: "",
    NgayNop: "",
    fileHoSo: "",
    TrangThai: "",
  });
  const [isEditModalOpen, setIsEditModalOpen] = useState(false);
  const [isCreateModalOpen, setIsCreateModalOpen] = useState(false);

  // Fetch articles and departments when the component mounts
  useEffect(() => {
    const fetchArticles = async () => {
      try {
        const response = await fetch(
          "http://localhost/Soucre-Code/BackEnd/Api/DuAnNCNT_Api/HoSoNCNT_Api.php?action=get"
        );
        const data = await response.json();
        setArticles(data); // Store articles data
      } catch (error) {
        console.error("Error fetching articles:", error);
      }
    };

    const fetchDepartments = async () => {
      try {
        const response = await fetch(
          "http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiSV/Khoa_Api.php?action=get"
        );
        const data = await response.json();
        setDepartments(data.Khoa); // Store department data
      } catch (error) {
        console.error("Error fetching departments:", error);
      }
    };

    fetchArticles();
    fetchDepartments();
  }, []);

  // Function to get the department name by MaKhoa
  const getDepartmentName = (maKhoa) => {
    const department = departments.find((dept) => dept.MaKhoa === maKhoa);
    return department ? department.TenKhoa : "Unknown Department";
  };

  const handleEdit = (article) => {
    setEditFormData({ ...article });
    setIsEditModalOpen(true); // Open the edit modal
  };

  const handleEditSubmit = async (e) => {
    e.preventDefault();
    try {
      const response = await axios.post(
        "http://localhost/Soucre-Code/BackEnd/Api/DeTaiCapCoSo_Api/HoSoDTCS_Api.php?action=update",
        editFormData
      );
      alert(response.data.message || "Article updated successfully!");
      setIsEditModalOpen(false);
      fetchArticles(); // Refresh the list
    } catch (error) {
      console.error("Error updating article:", error);
    }
  };

  const handleDelete = async (maHoSo) => {
    if (window.confirm("Are you sure you want to delete this article?")) {
      try {
        const response = await axios.post(
          "http://localhost/Soucre-Code/BackEnd/Api/DeTaiCapCoSo_Api/HoSoDTCS_Api.php?action=delete",
          { MaHoSo: maHoSo }
        );
        alert(response.data.message || "Article deleted successfully!");
        fetchArticles(); // Refresh the list
      } catch (error) {
        console.error("Error deleting article:", error);
      }
    }
  };

  const handleCreateSubmit = async (e) => {
    e.preventDefault();
    try {
      const response = await axios.post(
        "http://localhost/Soucre-Code/BackEnd/Api/DeTaiCapCoSo_Api/HoSoDTCS_Api.php?action=add",
        createFormData
      );
      alert(response.data.message || "New article added successfully!");
      setIsCreateModalOpen(false);
      fetchArticles(); // Refresh the list
    } catch (error) {
      console.error("Error adding article:", error);
    }
  };

  return (
    <div className="bg-white rounded-lg shadow p-6">
      <button
        onClick={() => setIsCreateModalOpen(true)}
        className="bg-green-500 text-white px-4 py-2 rounded-lg mb-4"
      >
        Thêm hồ sơ
      </button>
      <table className="min-w-full">
        <thead>
          <tr className="border-b">
            <th className="text-left py-4 px-2">Mã hồ sơ</th>
            <th className="text-left py-4 px-2">Ngày Nộp</th>
            <th className="text-left py-4 px-2">File Hồ Sơ</th>
            <th className="text-left py-4 px-2">Khoa</th>
            <th className="text-left py-4 px-2">Mã Đặt Hàng</th>{" "}
            {/* Add Mã Đặt Hàng column */}
            <th className="text-left py-4 px-2">Trạng Thái</th>
            <th className="text-right py-4 px-2">Thao Tác</th>
          </tr>
        </thead>

        <tbody>
          {articles.map((article, index) => (
            <tr key={index} className="border-b">
              <td className="py-4 px-2">{article.MaHoSo}</td>
              <td className="py-4 px-2">{article.NgayNop}</td>
              <td className="py-4 px-2">
                <a
                  href={`http://localhost/uploads/${article.FileHoSo}`}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="text-blue-500 hover:underline"
                >
                  {article.FileHoSo}
                </a>
              </td>
              <td className="py-4 px-2">{getDepartmentName(article.MaKhoa)}</td>
              <td className="py-4 px-2">{article.MaDatHang}</td>{" "}
              {/* Display MaDatHang */}
              <td className="px-4 py-2 border">
                <span
                  className={`px-2 py-1 rounded-full text-sm ${
                    article.TrangThai === "Khoa đã duyệt"
                      ? "bg-blue-100 text-blue-800"
                      : article.TrangThai === "Đã duyệt"
                      ? "bg-green-100 text-green-800"
                      : article.TrangThai === "Chưa duyệt"
                      ? "bg-yellow-100 text-yellow-800"
                      : "bg-red-100 text-red-800"
                  }`}
                >
                  {article.TrangThai}
                </span>
              </td>
              <td className="py-4 px-2 text-right">
                <button
                  onClick={() => handleEdit(article)}
                  className="text-blue-500 hover:underline mr-2"
                >
                  Sửa hồ sơ
                </button>
                <button
                  onClick={() => handleDelete(article.MaHoSo)}
                  className="text-red-500 hover:underline"
                >
                  Xóa
                </button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>

      {/* Create Modal */}
      {isCreateModalOpen && (
        <div className="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
          <div className="bg-white rounded-lg p-6 max-w-2xl w-full shadow-lg">
            <h2 className="text-2xl font-bold mb-4">Add New Article</h2>
            <form onSubmit={handleCreateSubmit} className="space-y-4">
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label className="block text-sm font-medium mb-1">
                    Mã hồ sơ
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
                    Ngày Nộp
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
                  <label className="block text-sm font-medium mb-1">
                    Trạng Thái
                  </label>
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
                  <label className="block text-sm font-medium mb-1">
                    File Hồ Sơ
                  </label>
                  <input
                    type="file"
                    onChange={(e) =>
                      setCreateFormData((prev) => ({
                        ...prev,
                        fileHoSo: e.target.files[0]?.name,
                      }))
                    }
                    className="w-full px-4 py-2 border rounded-lg"
                  />
                </div>
              </div>
              <button
                type="submit"
                className="bg-green-500 text-white px-4 py-2 rounded-lg mt-4"
              >
                Add Article
              </button>
            </form>
            <button
              onClick={() => setIsCreateModalOpen(false)}
              className="absolute top-2 right-2 text-gray-500 hover:text-gray-800"
            >
              &times;
            </button>
          </div>
        </div>
      )}

      {/* Edit Modal */}
      {isEditModalOpen && (
        <div className="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
          <div className="bg-white rounded-lg p-6 max-w-2xl w-full shadow-lg">
            <h2 className="text-2xl font-bold mb-4">Edit Article</h2>
            <form onSubmit={handleEditSubmit} className="space-y-4">
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label className="block text-sm font-medium mb-1">
                    Mã hồ sơ
                  </label>
                  <input
                    type="text"
                    value={editFormData.MaHoSo}
                    onChange={(e) =>
                      setEditFormData((prev) => ({
                        ...prev,
                        MaHoSo: e.target.value,
                      }))
                    }
                    className="w-full px-4 py-2 border rounded-lg"
                  />
                </div>
                <div>
                  <label className="block text-sm font-medium mb-1">
                    Ngày Nộp
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
                  <label className="block text-sm font-medium mb-1">
                    Trạng Thái
                  </label>
                  <input
                    type="text"
                    value={editFormData.TrangThai}
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
                  <label className="block text-sm font-medium mb-1">
                    File Hồ Sơ
                  </label>
                  <input
                    type="file"
                    onChange={(e) =>
                      setEditFormData((prev) => ({
                        ...prev,
                        fileHoSo: e.target.files[0]?.name,
                      }))
                    }
                    className="w-full px-4 py-2 border rounded-lg"
                  />
                </div>
              </div>
              <button
                type="submit"
                className="bg-blue-500 text-white px-4 py-2 rounded-lg mt-4"
              >
                Update Article
              </button>
            </form>
            <button
              onClick={() => setIsEditModalOpen(false)}
              className="absolute top-2 right-2 text-gray-500 hover:text-gray-800"
            >
              &times;
            </button>
          </div>
        </div>
      )}
    </div>
  );
};

export default ApprovalOffCampusProjectDepartment;
