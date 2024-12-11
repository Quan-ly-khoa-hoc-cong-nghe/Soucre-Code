import React, { useState, useEffect } from "react";
import axios from "axios";
import { FiEdit, FiTrash2 } from "react-icons/fi";

const PhienHoiThao = () => {
  const [phienHoiThaoList, setPhienHoiThaoList] = useState([]); // State để lưu danh sách phiên hội thảo
  const [loading, setLoading] = useState(true); // Trạng thái loading
  const [error, setError] = useState(null); // Trạng thái lỗi
  const [tenHoiThaoList, setTenHoiThaoList] = useState({}); // State để lưu tên hội thảo theo mã hội thảo

  // Hàm lấy dữ liệu phiên hội thảo từ API
  const fetchPhienHoiThao = async () => {
    try {
      const response = await axios.get(
        "http://localhost/Soucre-Code/BackEnd/Api/HoiThaoKhoaHocApi/PhienHoiThao_Api.php?action=get"
      );
      setPhienHoiThaoList(response.data); // Lưu dữ liệu vào state
    } catch (error) {
      setError("Error fetching session data. Please try again.");
      console.error("Error fetching session data:", error);
    } finally {
      setLoading(false); // Đặt trạng thái loading thành false sau khi API đã hoàn tất
    }
  };

  // Hàm lấy thông tin tên hội thảo từ mã hội thảo
  const fetchTenHoiThao = async (maHoiThao) => {
    try {
      const response = await axios.get(
        `http://localhost/Soucre-Code/BackEnd/Api/HoiThaoKhoaHocApi/HoiThao_Api.php?action=get&maHoiThao=${maHoiThao}`
      );
      if (response.data.length > 0) {
        setTenHoiThaoList((prevList) => ({
          ...prevList,
          [maHoiThao]: response.data[0].TenHoiThao, // Lưu tên hội thảo theo mã hội thảo
        }));
      }
    } catch (error) {
      console.error("Error fetching workshop details:", error);
    }
  };

  useEffect(() => {
    fetchPhienHoiThao();
  }, []);

  useEffect(() => {
    // Khi đã có danh sách phiên hội thảo, lấy tên hội thảo theo mã hội thảo
    phienHoiThaoList.forEach((phienHoi) => {
      fetchTenHoiThao(phienHoi.MaHoiThao);
    });
  }, [phienHoiThaoList]);

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
          <h1 className="text-2xl font-semibold">Phiên hội thảo</h1>
        </div>
        <button
          onClick={() => setIsCreateModalOpen(true)}
          className="bg-green-500 text-white px-4 py-2 rounded-lg mb-4"
        >
          Thêm phiên hội thảo
        </button>

        <div className="relative w-full max-w-sm">
          <input
            type="text"
            className="w-full px-4 py-2 pl-10 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Tìm kiếm..."
          />
        </div>
        {/* Hiển thị danh sách các phiên hội thảo */}
        <div className="bg-white rounded-lg shadow-lg p-6">
          <table className="w-full table-auto">
            <thead>
              <tr className="bg-gray-200 border-b border-gray-300 text-sm">
                <th className="py-3 px-4 text-left font-semibold text-gray-700 uppercase tracking-wider">
                  Tên Phiên Hội Thảo
                </th>
                <th className="py-3 px-4 text-left font-semibold text-gray-700 uppercase tracking-wider">
                  Thời Gian Bắt Đầu
                </th>
                <th className="py-3 px-4 text-left font-semibold text-gray-700 uppercase tracking-wider">
                  Thời Gian Kết Thúc
                </th>
                <th className="py-3 px-4 text-left font-semibold text-gray-700 uppercase tracking-wider">
                  Mô Tả
                </th>
                
                <th className="py-3 px-4 text-left font-semibold text-gray-700 uppercase tracking-wider">
                  Tên hội thảo
                </th>
                <th className="py-3 px-4 text-left font-semibold text-gray-700 uppercase tracking-wider">
                  Thao tác
                </th>
              </tr>
            </thead>

            <tbody>
              {phienHoiThaoList.length > 0 ? (
                phienHoiThaoList.map((phienHoi) => (
                  <tr
                    key={phienHoi.MaPhienHoiThao}
                    className="border-b border-gray-300 hover:bg-gray-50 transition-all"
                  >
                    <td className="py-3 px-4 border-r border-gray-200">
                      {phienHoi.TenPhienHoiThao}
                    </td>
                    <td className="py-3 px-4 border-r border-gray-200">
                      {phienHoi.ThoiGianBatDau}
                    </td>
                    <td className="py-3 px-4 border-r border-gray-200">
                      {phienHoi.ThoiGianKetThuc}
                    </td>
                    <td className="py-3 px-4 border-r border-gray-200">
                      {phienHoi.MoTa}
                    </td>
                   
                    <td className="py-3 px-4 border-r border-gray-200">
                      {/* Hiển thị tên hội thảo từ tên hội thảo list */}
                      {tenHoiThaoList[phienHoi.MaHoiThao] || "Đang tải..."}
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

export default PhienHoiThao;
