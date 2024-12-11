import React, { useState, useEffect } from "react";
import { FaEye, FaCheck, FaPlus, FaTimes } from "react-icons/fa";
import axios from "axios";

const Quanlydondathang = () => {
  const [orders, setOrders] = useState([]); // State lưu trữ dữ liệu đơn đặt hàng
  const [partners, setPartners] = useState([]); // State lưu trữ dữ liệu đối tác

  // Fetch dữ liệu từ API khi component được mount
  useEffect(() => {
    const fetchData = async () => {
      try {
        const ordersResponse = await axios.get(
          "http://localhost/Soucre-Code/BackEnd/Api/DuAnNCNT_Api/DonDatHang_Api.php?action=get"
        );
        const partnersResponse = await axios.get(
          "http://localhost/Soucre-Code/BackEnd/Api/DuAnNCNT_Api/DonViDoiTac_Api.php?action=get"
        );

        setOrders(ordersResponse.data); // Lưu dữ liệu đơn đặt hàng vào state
        setPartners(partnersResponse.data); // Lưu dữ liệu đối tác vào state
      } catch (error) {
        console.error("Error fetching data:", error);
      }
    };

    fetchData();
  }, []); // Chạy một lần khi component được mount

  // Hàm để lấy tên đối tác từ MaDoiTac
  const getPartnerName = (MaDoiTac) => {
    const partner = partners.find((p) => p.MaDoiTac === MaDoiTac);
    return partner ? partner.TenDoiTac : "Không tìm thấy đối tác";
  };

  return (
    <div className="bg-white shadow-lg rounded-lg p-6">
      <h2 className="text-2xl font-semibold mb-4 text-center">
        Quản lý Đơn Đặt Hàng
      </h2>

      {/* Nút Thêm Đơn Đặt */}
      <div className="mb-4 text-right">
        <button className="px-6 py-2 bg-green-500 text-white rounded-lg flex items-center hover:bg-green-600 transition-all duration-200">
          <FaPlus className="mr-2" /> Thêm đơn đặt
        </button>
      </div>

      {/* Bảng hiển thị dữ liệu */}
      <table className="min-w-full border-collapse table-auto">
        <thead>
          <tr className="bg-gray-100 text-left text-sm font-semibold text-gray-700">
            <th className="px-4 py-2 border-b">Mã Đặt Hàng</th>
            <th className="px-4 py-2 border-b">Ngày Đặt</th>
            <th className="px-4 py-2 border-b">File Đặt Hàng</th>
            <th className="px-4 py-2 border-b">Tên Đối Tác</th>
            <th className="px-4 py-2 border-b">Thao Tác</th>
          </tr>
        </thead>
        <tbody>
          {orders.map((order) => (
            <tr key={order.MaDatHang} className="hover:bg-gray-50">
              <td className="px-4 py-2 border-b">{order.MaDatHang}</td>
              <td className="px-4 py-2 border-b">{order.NgayDat}</td>
              <td className="px-4 py-2 border-b">
                <a
                  href={`/path/to/files/${order.FileDatHang}`}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="text-blue-500 hover:underline"
                >
                  {order.FileDatHang}
                </a>
              </td>
              <td className="px-4 py-2 border-b">
                {getPartnerName(order.MaDoiTac)}
              </td>
              <td className="px-4 py-2">{getPartnerName(order.MaDoiTac)}</td>{" "}
              {/* Hiển thị tên đối tác */}
              <td className="px-4 py-2">
                <button className="text-blue-500 hover:text-blue-700 mr-4 text-lg">
                  <FaEye />
                </button>
                <button className="text-green-500 hover:text-green-700 mr-4 text-lg">
                  Sửa đơn đặt
                </button>
                <button className="text-red-500 hover:text-red-700 text-lg">
                  Xóa
                </button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
};

export default Quanlydondathang;
