import React, { useState, useEffect } from "react";

const User = () => {
  const [users, setUsers] = useState([]); // State để lưu danh sách nhân viên

  // Fetch API khi component được render
  useEffect(() => {
    fetch("http://localhost/Soucre-Code/BackEnd/Api/User_Api/NhanVien_Api.php?action=getAll")
      .then((response) => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .then((data) => setUsers(data))
      .catch((error) => console.error("Fetch error:", error));
  }, []);

  return (
    <div className="p-6 mb-6 p-4 border border-blue-500 rounded-lg shadow-md bg-white">
      <h2 className="text-xl font-bold mb-4">Danh sách nhân viên</h2>
      <button
            className="bg-green-500 text-white px-4 py-2 rounded-lg shadow-sm hover:bg-green-600"
            onClick={() => handleAddProduct(topic.MaDeTaiNCKHGV)} // Truyền mã đề tài đang chọn
          >
            Thêm sản phẩm
          </button>
      {users.length === 0 ? (
        <p>Không có dữ liệu nhân viên.</p>
      ) : (
        <table className="table-auto w-full border border-gray-300">
          <thead>
            <tr className="bg-gray-200">
              <th className="border border-gray-300 px-4 py-2">Mã Nhân Viên</th>
              <th className="border border-gray-300 px-4 py-2">Tên Nhân Viên</th>
              <th className="border border-gray-300 px-4 py-2">Số Điện Thoại</th>
              <th className="border border-gray-300 px-4 py-2">Email</th>
              <th className="border border-gray-300 px-4 py-2">Phòng Công Tác</th>
              <th className="border border-gray-300 px-4 py-2">Thao tấc</th>

            </tr>
          </thead>
          <tbody>
            {users.map((user) => (
              <tr key={user.MaNhanVien} className="hover:bg-gray-50">
                <td className="border border-gray-300 px-4 py-2">{user.MaNhanVien}</td>
                <td className="border border-gray-300 px-4 py-2">{user.TenNhanVien}</td>
                <td className="border border-gray-300 px-4 py-2">{user.sdtNV}</td>
                <td className="border border-gray-300 px-4 py-2">{user.EmailNV}</td>
                <td className="border border-gray-300 px-4 py-2">{user.PhongCongTac}</td>
                <td className="border border-gray-300 px-4 py-2">  <>
                            <button
                              className="text-blue-500 hover:text-blue-700"
                              onClick={() => handleEditProduct(product)}
                            >
                              Sửa
                            </button>
                            <button
                              className="text-red-500 hover:text-red-700"
                              onClick={() =>
                                handleDeleteProduct(product.MaDeTaiNCKHGV)
                              }
                            >
                              Xóa
                            </button>
                            <button
                              className="text-green-500 hover:text-green-700"
                              onClick={() => handleViewDetails(product)}
                            >
                              Chi tiết
                            </button>
                          </></td>
              </tr>
            ))}
          </tbody>
        </table>
      )}
    </div>
  );
};

export default User;
