import React, { useState, useEffect } from "react";
import axios from "axios";
import { FiEdit, FiTrash2 } from "react-icons/fi";


const VaitroHoiThao = () => {
  const [phienHoiThaoList, setPhienHoiThaoList] = useState([]); // Phiên hội thảo
  const [chuTriList, setChuTriList] = useState([]); // Danh sách chủ trì
  const [dienGiaList, setDienGiaList] = useState([]); // Danh sách diễn giả
  const [loading, setLoading] = useState(true); // Trạng thái loading
  const [error, setError] = useState(null); // Trạng thái lỗi

  // Hàm lấy dữ liệu các phiên hội thảo từ API
  const fetchPhienHoiThao = async () => {
    try {
      const response = await axios.get(
        "http://localhost/Soucre-Code/BackEnd/Api/HoiThaoKhoaHocApi/PhienHoiThao_Api.php?action=get"
      );
      setPhienHoiThaoList(response.data); // Lưu dữ liệu phiên hội thảo
    } catch (error) {
      setError("Error fetching session data. Please try again.");
      console.error("Error fetching session data:", error);
    }
  };

  // Hàm lấy dữ liệu chủ trì từ API
  const fetchChuTri = async () => {
    try {
      const response = await axios.get(
        "http://localhost/Soucre-Code/BackEnd/Api/HoiThaoKhoaHocApi/ChuTriPhien_Api.php?action=get"
      );
      setChuTriList(response.data); // Lưu dữ liệu chủ trì
    } catch (error) {
      setError("Error fetching session leader data. Please try again.");
      console.error("Error fetching session leader data:", error);
    }
  };

  // Hàm lấy dữ liệu diễn giả từ API
  const fetchDienGia = async () => {
    try {
      const response = await axios.get(
        "http://localhost/Soucre-Code/BackEnd/Api/HoiThaoKhoaHocApi/DienGiaPhien_Api.php?action=get"
      );
      setDienGiaList(response.data); // Lưu dữ liệu diễn giả
    } catch (error) {
      setError("Error fetching speaker data. Please try again.");
      console.error("Error fetching speaker data:", error);
    }
  };

  // Gọi API khi component được mount
  useEffect(() => {
    const fetchData = async () => {
      await Promise.all([fetchPhienHoiThao(), fetchChuTri(), fetchDienGia()]);
      setLoading(false); // Set loading to false once all data is fetched
    };
    fetchData();
  }, []);

  // Hiển thị loading hoặc lỗi nếu có
  if (loading) {
    return <div className="text-center py-4">Loading...</div>;
  }

  if (error) {
    return <div className="text-red-500 text-center py-4">{error}</div>;
  }

  return (
    <div className="min-h-screen bg-gray-50 p-8">
      <div className="container mx-auto">
        <div className="flex items-center justify-between mb-6">
          <h1 className="text-2xl font-semibold">Danh sách chỉ trì và diễn giả </h1>
        </div>
        <button
          onClick={() => setIsCreateModalOpen(true)}
          className="bg-green-500 text-white px-4 py-2 rounded-lg mb-4"
        >
          Thêm mới
        </button>
        {/* Hiển thị bảng thông tin phiên hội thảo */}
        <div className="bg-white rounded-lg shadow-lg p-6">
          <table className="w-full table-auto">
            <thead>
              <tr className="bg-gray-200 border-b border-gray-300 text-sm">
                <th className="py-3 px-4 text-left font-semibold text-gray-700 uppercase tracking-wider">
                  Mã Phiên Hội Thảo
                </th>
                <th className="py-3 px-4 text-left font-semibold text-gray-700 uppercase tracking-wider">
                  Tên Phiên Hội Thảo
                </th>
                <th className="py-3 px-4 text-left font-semibold text-gray-700 uppercase tracking-wider">
                  Chủ trì hội thảo
                </th>
                <th className="py-3 px-4 text-left font-semibold text-gray-700 uppercase tracking-wider">
                  Diễn giả hội thảo
                </th>
                <th className="py-3 px-4 text-left font-semibold text-gray-700 uppercase tracking-wider">
                  Thao tác
                </th>
              </tr>
            </thead>

            <tbody>
              {phienHoiThaoList.length > 0 ? (
                phienHoiThaoList.map((phienHoiThao) => {
                  // Tìm vai trò chủ trì cho mỗi phiên hội thảo
                  const chuTri = chuTriList.filter(
                    (item) => item.MaPhienHoiThao === phienHoiThao.MaPhienHoiThao
                  );
                  const dienGia = dienGiaList.filter(
                    (item) => item.MaPhienHoiThao === phienHoiThao.MaPhienHoiThao
                  );

                  return (
                    <tr
                      key={phienHoiThao.MaPhienHoiThao}
                      className="border-b border-gray-300 hover:bg-gray-50 transition-all"
                    >
                      <td className="py-3 px-4 border-r border-gray-200">
                        {phienHoiThao.MaPhienHoiThao}
                      </td>
                      <td className="py-3 px-4 border-r border-gray-200">
                        {phienHoiThao.TenPhienHoiThao}
                      </td>
                      <td className="py-3 px-4 border-r border-gray-200">
                        {chuTri.length > 0 ? chuTri[0].MaNguoiThamGia : "N/A"}
                      </td>
                      <td className="py-3 px-4 border-r border-gray-200">
                        {dienGia.length > 0 ? dienGia[0].MaNguoiThamGia : "N/A"}
                      </td>
                      <td className="px-4 py-2 border">
                      <button
                        className="text-blue-500 hover:underline mr-2"
                        onClick={() => handleEdit(phienHoi)}
                      >
                        <FiEdit className="inline-block mr-1" /> {/* Sử dụng icon sửa */}
                      </button>
                      <button
                        className="text-red-500 hover:underline"
                        onClick={() => handleDelete(phienHoi.MaPhienHoiThao)}
                      >
                        <FiTrash2 className="inline-block mr-1" /> {/* Sử dụng icon xóa */}
                      </button>
                    </td>
                    </tr>
                  );
                })
              ) : (
                <tr>
                  <td colSpan="5" className="py-3 px-4 text-center">
                    Không có dữ liệu phiên hội thảo
                  </td>
                </tr>
              )}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
};

export default VaitroHoiThao;
