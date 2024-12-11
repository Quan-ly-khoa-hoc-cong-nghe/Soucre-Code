import React, { useState, useEffect } from "react";
import axios from "axios";
import { FiEdit, FiTrash2 } from "react-icons/fi";


const NguoiThamGia = () => {
  const [nguoiThamGiaList, setNguoiThamGiaList] = useState([]); // State để lưu danh sách người tham gia
  const [loading, setLoading] = useState(true); // Trạng thái loading
  const [error, setError] = useState(null); // Trạng thái lỗi

  // Hàm lấy dữ liệu người tham gia từ API
  const fetchNguoiThamGia = async () => {
    try {
      const response = await axios.get(
        "http://localhost/Soucre-Code/BackEnd/Api/HoiThaoKhoaHocApi/NguoiThamGia_Api.php?action=get"
      );
      setNguoiThamGiaList(response.data); // Lưu dữ liệu vào state
    } catch (error) {
      setError("Error fetching participant data. Please try again.");
      console.error("Error fetching participant data:", error);
    } finally {
      setLoading(false); // Đặt trạng thái loading thành false sau khi API đã hoàn tất
    }
  };

  useEffect(() => {
    fetchNguoiThamGia(); // Gọi API khi component được mount
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
          <h1 className="text-2xl font-semibold">Danh sách Người Tham Gia</h1>
        </div>
        <button
          onClick={() => setIsCreateModalOpen(true)}
          className="bg-green-500 text-white px-4 py-2 rounded-lg mb-4"
        >
          Thêm người tham gia
        </button>

        {/* Hiển thị danh sách người tham gia */}
        <div className="bg-white rounded-lg shadow-lg p-6">
          <table className="w-full table-auto">
            <thead>
              <tr className="bg-gray-200 border-b border-gray-300 text-sm">
                <th className="py-3 px-4 text-left font-semibold text-gray-700 uppercase tracking-wider">
                  Mã Người Tham Gia
                </th>
                <th className="py-3 px-4 text-left font-semibold text-gray-700 uppercase tracking-wider">
                  Tên Người Tham Gia
                </th>
                <th className="py-3 px-4 text-left font-semibold text-gray-700 uppercase tracking-wider">
                  Số Điện Thoại
                </th>
                <th className="py-3 px-4 text-left font-semibold text-gray-700 uppercase tracking-wider">
                  Email
                </th>
                <th className="py-3 px-4 text-left font-semibold text-gray-700 uppercase tracking-wider">
                  Học Hàm
                </th>
                <th className="py-3 px-4 text-left font-semibold text-gray-700 uppercase tracking-wider">
                  Học Vị
                </th>
                <th className="py-3 px-4 text-left font-semibold text-gray-700 uppercase tracking-wider">
                  Thao tác
                </th>
              </tr>
            </thead>

            <tbody>
              {nguoiThamGiaList.length > 0 ? (
                nguoiThamGiaList.map((nguoi) => (
                  <tr
                    key={nguoi.MaNguoiThamGia}
                    className="border-b border-gray-300 hover:bg-gray-50 transition-all"
                  >
                    <td className="py-3 px-4 border-r border-gray-200">
                      {nguoi.MaNguoiThamGia}
                    </td>
                    <td className="py-3 px-4 border-r border-gray-200">
                      {nguoi.TenNguoiThamGia}
                    </td>
                    <td className="py-3 px-4 border-r border-gray-200">
                      {nguoi.Sdt}
                    </td>
                    <td className="py-3 px-4 border-r border-gray-200">
                      {nguoi.Email}
                    </td>
                    <td className="py-3 px-4 border-r border-gray-200">
                      {nguoi.HocHam}
                    </td>
                    <td className="py-3 px-4 border-r border-gray-200">
                      {nguoi.HocVi}
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
                ))
              ) : (
                <tr>
                  <td colSpan="7" className="py-3 px-4 text-center">
                    Không có dữ liệu người tham gia
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

export default NguoiThamGia;
