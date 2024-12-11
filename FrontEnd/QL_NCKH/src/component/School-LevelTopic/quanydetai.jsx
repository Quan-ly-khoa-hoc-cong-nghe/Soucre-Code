import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { FaEye, FaEdit, FaTrash } from 'react-icons/fa'; // Import các icon từ react-icons

const Quanlydetaicapso = () => {
  const [deTaiCapSo, setDeTaiCapSo] = useState([]); // State lưu danh sách đề tài cấp sở

  // Hàm lấy dữ liệu từ API
  const fetchDeTaiCapSo = async () => {
    try {
      const response = await axios.get("http://localhost/Soucre-Code/BackEnd/Api/DeTaiCapCoSo_Api/DeTaiCapSo_Api.php?action=get");
      setDeTaiCapSo(response.data); // Lưu dữ liệu vào state
    } catch (error) {
      console.error("Error fetching data:", error);
    }
  };

  // Gọi API khi component được render
  useEffect(() => {
    fetchDeTaiCapSo();
  }, []);

  return (
    <div className="min-h-screen bg-gray-50 p-8">
      <div className="container mx-auto">
        <div className="flex items-center justify-between mb-6">
          <h1 className="text-2xl font-semibold">Quản lý đề tài cấp sở</h1>
        </div>

        {/* Bảng hiển thị danh sách đề tài cấp sở */}
        <div className="bg-white rounded-lg shadow-lg p-6">
          <table className="w-full table-auto">
            <thead>
              <tr className="bg-gray-200 border-b border-gray-300 text-sm">
                <th className="py-3 px-4 text-left font-semibold text-gray-700 uppercase tracking-wider">
                  Tên Đề Tài
                </th>
                <th className="py-3 px-4 text-left font-semibold text-gray-700 uppercase tracking-wider">
                  Mã Hồ Sơ
                </th>
                <th className="py-3 px-4 text-left font-semibold text-gray-700 uppercase tracking-wider">
                  Ngày Bắt Đầu
                </th>
                <th className="py-3 px-4 text-left font-semibold text-gray-700 uppercase tracking-wider">
                  Ngày Kết Thúc
                </th>
                <th className="py-3 px-4 text-left font-semibold text-gray-700 uppercase tracking-wider">
                  Trạng Thái
                </th>
                <th className="py-3 px-4 text-right font-semibold text-gray-700 uppercase tracking-wider">
                  Hành Động
                </th>
              </tr>
            </thead>

            <tbody>
              {deTaiCapSo.map((deTai) => (
                <tr key={deTai.MaDTCS} className="border-b border-gray-300 hover:bg-gray-50 transition-all">
                  <td className="py-3 px-4 border-r border-gray-200">{deTai.TenDeTai}</td>
                  <td className="py-3 px-4 border-r border-gray-200">{deTai.MaHoSo}</td>
                  <td className="py-3 px-4 border-r border-gray-200">{deTai.NgayBatDau}</td>
                  <td className="py-3 px-4 border-r border-gray-200">{deTai.NgayKetThuc}</td>
                  <td className="py-3 px-4 border-r border-gray-200">
                    {/* Dynamic status color */}
                    <span
                      className={`px-3 py-1 rounded-full text-sm ${
                        deTai.TrangThai === "Hoàn thành"
                          ? "bg-green-100 text-green-800"
                          : deTai.TrangThai === "Hủy"
                          ? "bg-red-100 text-red-800"
                          : "bg-blue-100 text-blue-800"
                      }`}
                    >
                      {deTai.TrangThai}
                    </span>
                  </td>
                  <td className="py-3 px-4 text-right">
                    {/* Các hành động như xem chi tiết, chỉnh sửa, xóa */}
                    <div className="flex justify-end space-x-2">
                      {/* Xem chi tiết */}
                      <button className="p-2 text-blue-600 hover:bg-blue-100 rounded-full" title="Xem chi tiết">
                        <FaEye className="w-5 h-5" />
                      </button>
                      {/* Sửa */}
                      <button className="p-2 text-green-600 hover:bg-green-100 rounded-full" title="Sửa">
                        <FaEdit className="w-5 h-5" />
                      </button>
                      {/* Xóa */}
                      <button className="p-2 text-red-600 hover:bg-red-100 rounded-full" title="Xóa">
                        <FaTrash className="w-5 h-5" />
                      </button>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
};

export default Quanlydetaicapso;
