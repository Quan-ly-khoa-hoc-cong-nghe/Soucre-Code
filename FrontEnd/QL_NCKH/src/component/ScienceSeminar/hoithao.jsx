import React, { useState, useEffect } from "react";
import axios from "axios";
import { FiEdit, FiTrash2 } from "react-icons/fi";

const HoiThaoKhoaHoc = () => {
  const [hoiThaoList, setHoiThaoList] = useState([]); // State để lưu danh sách hội thảo
  const [loading, setLoading] = useState(true); // Trạng thái loading
  const [error, setError] = useState(null); // Trạng thái lỗi

  // Hàm lấy dữ liệu hội thảo từ API
  const fetchHoiThao = async () => {
    try {
      const response = await axios.get(
        "http://localhost/Soucre-Code/BackEnd/Api/HoiThaoKhoaHocApi/HoiThao_Api.php?action=get"
      );
      setHoiThaoList(response.data); // Lưu dữ liệu vào state
    } catch (error) {
      setError("Error fetching workshop data. Please try again.");
      console.error("Error fetching workshop data:", error);
    } finally {
      setLoading(false); // Đặt trạng thái loading thành false sau khi API đã hoàn tất
    }
  };

  useEffect(() => {
    fetchHoiThao(); // Gọi API khi component được mount
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
          <h1 className="text-2xl font-semibold">Danh sách Hội Thảo</h1>
        </div>
        <div className="relative w-full max-w-sm">
          <input
            type="text"
            className="w-full px-4 py-2 pl-10 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Tìm kiếm..."
          />
        </div>
        {/* Hiển thị danh sách các hội thảo */}
        <div className="bg-white rounded-lg shadow-lg p-6">
          <table className="w-full table-auto">
            <thead>
              <tr className="bg-gray-200 border-b border-gray-300 text-sm">
                <th className="py-3 px-4 text-left font-semibold text-gray-700 uppercase tracking-wider">
                  Mã Hội Thảo
                </th>
                <th className="py-3 px-4 text-left font-semibold text-gray-700 uppercase tracking-wider">
                  Tên Hội Thảo
                </th>
                <th className="py-3 px-4 text-left font-semibold text-gray-700 uppercase tracking-wider">
                  Ngày Bắt Đầu
                </th>
                <th className="py-3 px-4 text-left font-semibold text-gray-700 uppercase tracking-wider">
                  Ngày Kết Thúc
                </th>
                <th className="py-3 px-4 text-left font-semibold text-gray-700 uppercase tracking-wider">
                  Địa Điểm
                </th>
                <th className="py-3 px-4 text-left font-semibold text-gray-700 uppercase tracking-wider">
                  Số Lượng Tham Dự
                </th>
               
                <th className="py-3 px-4 text-left font-semibold text-gray-700 uppercase tracking-wider">
                  Thao Tác
                </th>
              </tr>
            </thead>

            <tbody>
              {hoiThaoList.length > 0 ? (
                hoiThaoList.map((hoiThao) => (
                  <tr
                    key={hoiThao.MaHoiThao}
                    className="border-b border-gray-300 hover:bg-gray-50 transition-all"
                  >
                    <td className="py-3 px-4 border-r border-gray-200">
                      {hoiThao.MaHoiThao}
                    </td>
                    <td className="py-3 px-4 border-r border-gray-200">
                      {hoiThao.TenHoiThao}
                    </td>
                    <td className="py-3 px-4 border-r border-gray-200">
                      {hoiThao.NgayBatDau}
                    </td>
                    <td className="py-3 px-4 border-r border-gray-200">
                      {hoiThao.NgayKetThuc}
                    </td>
                    <td className="py-3 px-4 border-r border-gray-200">
                      {hoiThao.DiaDiem}
                    </td>
                    <td className="py-3 px-4 border-r border-gray-200">
                      {hoiThao.SoLuongThamDu}
                    </td>
                    
                    <td className="px-4 py-2 border">
                      <button
                        className="text-blue-500 hover:underline mr-2"
                        onClick={() => handleEdit(hoiThao)}
                      >
                        <FiEdit className="inline-block mr-1" /> {/* Sử dụng icon sửa */}
                      </button>
                      <button
                        className="text-red-500 hover:underline"
                        onClick={() => handleDelete(hoiThao.MaHoiThao)}
                      >
                        <FiTrash2 className="inline-block mr-1" /> {/* Sử dụng icon xóa */}
                      </button>
                    </td>
                  </tr>
                ))
              ) : (
                <tr>
                  <td colSpan="8" className="py-3 px-4 text-center">
                    Không có dữ liệu hội thảo
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

export default HoiThaoKhoaHoc;
