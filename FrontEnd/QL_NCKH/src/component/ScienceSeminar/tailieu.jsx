import React, { useState, useEffect } from "react";
import axios from "axios";

const Quanlytailieu = () => {
  const [hoiThaoList, setHoiThaoList] = useState([]); // State lưu danh sách hội thảo
  const [taiLieuList, setTaiLieuList] = useState([]); // State lưu danh sách tài liệu
  const [loading, setLoading] = useState(true); // Loading state
  const [selectedHoiThao, setSelectedHoiThao] = useState(null); // HoiThao đang được chọn

  const fetchHoiThao = async () => {
    try {
      const response = await axios.get(
        "http://localhost/Soucre-Code/BackEnd/Api/HoiThaoKhoaHocApi/HoiThao_Api.php?action=get"
      );
      setHoiThaoList(response.data); // Lưu danh sách hội thảo vào state
    } catch (error) {
      console.error("Error fetching event data:", error);
    }
  };

  const fetchTaiLieu = async () => {
    try {
      const response = await axios.get(
        "http://localhost/Soucre-Code/BackEnd/Api/HoiThaoKhoaHocApi/TaiLieuHoiThao_Api.php?action=get"
      );
      setTaiLieuList(response.data); // Lưu danh sách tài liệu vào state
    } catch (error) {
      console.error("Error fetching document data:", error);
    }
  };

  useEffect(() => {
    const fetchData = async () => {
      await Promise.all([fetchHoiThao(), fetchTaiLieu()]);
      setLoading(false); // Set loading to false after both API calls complete
    };
    fetchData();
  }, []);

  // Lọc tài liệu theo MaHoiThao
  const filteredTaiLieu = taiLieuList.filter(
    (taiLieu) => taiLieu.MaHoiThao === selectedHoiThao
  );

  // Handle chọn hội thảo
  const handleHoiThaoChange = (event) => {
    setSelectedHoiThao(event.target.value); // Cập nhật hội thảo được chọn
  };

  // Loading state
  if (loading) {
    return <div className="text-center py-4">Loading...</div>;
  }

  return (
    <div className="min-h-screen bg-gray-50 p-8">
      <div className="container mx-auto">
        <div className="flex items-center justify-between mb-6">
          <h1 className="text-2xl font-semibold">Quản lý tài liệu hội thảo</h1>
        </div>
        <button
          onClick={() => setIsCreateModalOpen(true)}
          className="bg-green-500 text-white px-4 py-2 rounded-lg mb-4"
        >
          Thêm tài liệu
        </button>

        {/* Dropdown chọn hội thảo */}
        <div className="mb-6">
          <label
            htmlFor="hoiThao"
            className="block text-sm font-semibold text-gray-700"
          >
            Chọn Hội Thảo
          </label>
          <select
            id="hoiThao"
            className="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md"
            value={selectedHoiThao || ""}
            onChange={handleHoiThaoChange}
          >
            <option value="" disabled>
              Chọn hội thảo
            </option>
            {hoiThaoList.map((event) => (
              <option key={event.MaHoiThao} value={event.MaHoiThao}>
                {event.TenHoiThao}
              </option>
            ))}
          </select>
        </div>

        {/* Hiển thị tài liệu hội thảo */}
        <div className="bg-white rounded-lg shadow-lg p-6">
          {selectedHoiThao && (
            <>
              <h2 className="text-xl font-semibold mb-4">Tài Liệu Hội Thảo</h2>
              <table className="w-full table-auto">
                <thead>
                  <tr className="bg-gray-200 border-b border-gray-300 text-sm">
                    <th className="py-3 px-4 text-left font-semibold text-gray-700 uppercase tracking-wider">
                      Tên Tài Liệu
                    </th>
                    <th className="py-3 px-4 text-left font-semibold text-gray-700 uppercase tracking-wider">
                      File
                    </th>
                    <th className="py-3 px-4 text-left font-semibold text-gray-700 uppercase tracking-wider">
                      Ngày Tạo
                    </th>
                    <th className="py-3 px-4 text-left font-semibold text-gray-700 uppercase tracking-wider">
                      Thao tác
                    </th>
                  </tr>
                </thead>
                <tbody>
                  {filteredTaiLieu.length > 0 ? (
                    filteredTaiLieu.map((taiLieu) => (
                      <tr
                        key={taiLieu.MaTaiLieu}
                        className="border-b border-gray-300 hover:bg-gray-50 transition-all"
                      >
                        <td className="py-3 px-4 border-r border-gray-200">
                          {taiLieu.TenTaiLieu}
                        </td>
                        <td className="py-3 px-4 border-r border-gray-200">
                          {taiLieu.DuongDanFile}
                        </td>
                        <td className="py-3 px-4 border-r border-gray-200">
                          {taiLieu.ThoiGianTao}
                        </td>
                        
                      </tr>
                    ))
                  ) : (
                    <tr>
                      <td colSpan="3" className="py-3 px-4 text-center">
                        Không có tài liệu cho hội thảo này
                      </td>
                    </tr>
                  )}
                </tbody>
              </table>
            </>
          )}
        </div>
      </div>
    </div>
  );
};

export default Quanlytailieu;
