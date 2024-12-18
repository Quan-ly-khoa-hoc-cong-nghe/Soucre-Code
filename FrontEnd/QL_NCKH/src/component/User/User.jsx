import React, { useState, useEffect } from "react";

const User = () => {
  const [users, setUsers] = useState([]); // State để lưu danh sách nhân viên
  const [showModal, setShowModal] = useState(false); // State để điều khiển modal
  const [showDeleteModal, setShowDeleteModal] = useState(false);
  const [showDetailModal, setShowDetailModal] = useState(false);
  const [showEditModal, setShowEditModal] = useState(false);
  const [newUser, setNewUser] = useState({
    MaNguoiDung: "",
    VaiTro: "",
    MatKhau: "",
    MaNhanVien: "",
  }); // State để lưu thông tin người dùng mới
  const [userToDelete, setUserToDelete] = useState(null);
  const [allNhanVien, setAllNhanVien] = useState([]); // State để lưu danh sách mã nhân viên
  const [nhanVienDetails, setNhanVienDetails] = useState(null);
  const [userToEdit, setUserToEdit] = useState(null);

  // Fetch API khi component được render
  useEffect(() => {
    fetch(
      "http://localhost/Soucre-Code/BackEnd/Api/User_Api/NguoiDung_Api.php?action=getAll"
    )
      .then((response) => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .then((data) => setUsers(data))
      .catch((error) => console.error("Fetch error:", error));

    // Fetch mã nhân viên để kiểm tra khi thêm
    fetch(
      "http://localhost/Soucre-Code/BackEnd/Api/User_Api/NhanVien_Api.php?action=getAll"
    )
      .then((response) => response.json())
      .then((data) => setAllNhanVien(data)) // Lưu danh sách mã nhân viên
      .catch((error) => console.error("Error fetching employees:", error));
  }, []);

  // Hàm để mở modal
  const handleAddUserClick = () => {
    setShowModal(true);
  };

  // Hàm để đóng modal
  const handleCloseModal = () => {
    setShowModal(false);
    setNewUser({
      MaNguoiDung: "",
      VaiTro: "",
      MatKhau: "",
      MaNhanVien: "",
    });
  };

  // Hàm kiểm tra mã nhân viên có tồn tại không
  const checkMaNhanVienExists = (maNhanVien) => {
    return allNhanVien.some((nv) => nv.MaNhanVien === maNhanVien);
  };

  // Hàm để xử lý khi nhấn "Thêm"
  const handleAddUser = () => {
    // Kiểm tra xem mã nhân viên đã tồn tại hay chưa
    if (!checkMaNhanVienExists(newUser.MaNhanVien)) {
      alert("Mã nhân viên không tồn tại!");
      return;
    }

    if (newUser.VaiTro === "") {
      alert("Vui lòng chọn vai trò!");
      return;
    }
    console.log("data:", newUser);
    // Gửi yêu cầu thêm người dùng (tạo API thêm người dùng ở backend)
    fetch(
      "http://localhost/Soucre-Code/BackEnd/Api/User_Api/NguoiDung_Api.php?action=post",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(newUser),
      }
    )
      .then((response) => response.json())
      .then((data) => {
        console.log(data);
        setUsers((prevUsers) => [...prevUsers, newUser]); // Thêm người dùng mới vào danh sách
        handleCloseModal(); // Đóng modal sau khi thêm thành công
        alert("Người dùng đã được thêm thành công");
      })
      .catch((error) => console.error("Error adding user:", error));
  };

  const handleDeleteClick = (user) => {
    setUserToDelete(user);
    setShowDeleteModal(true); // Hiển thị modal xác nhận xóa
  };

  const handleConfirmDelete = () => {
    // Gửi yêu cầu xóa người dùng
    fetch(
      `http://localhost/Soucre-Code/BackEnd/Api/User_Api/NguoiDung_Api.php?action=delete&MaNguoiDung=${userToDelete.MaNguoiDung}`,
      {
        method: "DELETE",
      }
    )
      .then((response) => response.json())
      .then((data) => {
        setUsers((prevUsers) =>
          prevUsers.filter(
            (user) => user.MaNguoiDung !== userToDelete.MaNguoiDung
          )
        ); // Cập nhật lại danh sách người dùng sau khi xóa
        setShowDeleteModal(false); // Đóng modal xác nhận
        setUserToDelete(null); // Reset userToDelete
        alert("Người dùng đã được xóa thành công");
      })
      .catch((error) => console.error("Error deleting user:", error));
  };

  const handleCloseDeleteModal = () => {
    setShowDeleteModal(false);
    setUserToDelete(null); // Reset userToDelete
  };

  const handleDetailClick = (maNhanVien) => {
    fetch(
      `http://localhost/Soucre-Code/BackEnd/Api/User_Api/NhanVien_Api.php?action=getById&MaNhanVien=${maNhanVien}`
    )
      .then((response) => response.json())
      .then((data) => {
        setNhanVienDetails(data); // Lưu thông tin chi tiết
        setShowDetailModal(true); // Mở modal chi tiết
      })
      .catch((error) => console.error("Error fetching details:", error));
  };

  const handleCloseDetailModal = () => setShowDetailModal(false);

  const handleEditClick = (user) => {
    setUserToEdit(user);
    setShowEditModal(true);
  };

  // Hàm đóng modal sửa
  const handleCloseEditModal = () => {
    setShowEditModal(false);
    setUserToEdit(null);
  };

  const handleUpdatePassword = () => {
    if (!userToEdit?.MatKhau) {
      alert("Mật khẩu không được để trống!");
      return;
    }

    const query = new URLSearchParams({
      MaNguoiDung: userToEdit.MaNguoiDung,
      MatKhau: userToEdit.MatKhau,
    }).toString();

    fetch(
      `http://localhost/Soucre-Code/BackEnd/Api/User_Api/NguoiDung_Api.php?action=put&${query}`,
      {
        method: "PUT",
      }
    )
      .then((response) => response.json())
      .then((data) => {
        setUsers((prevUsers) =>
          prevUsers.map((user) =>
            user.MaNguoiDung === userToEdit.MaNguoiDung
              ? { ...user, MatKhau: userToEdit.MatKhau }
              : user
          )
        );
        handleCloseEditModal();
        alert("Mật khẩu đã được cập nhật thành công!");
      })
      .catch((error) => console.error("Error updating password:", error));
  };

  const handleEditChange = (e) => {
    const { name, value } = e.target;
    setUserToEdit((prevState) => ({
      ...prevState,
      [name]: value,
    }));
  };

  // Hàm để xử lý khi người dùng nhập thông tin
  const handleChange = (e) => {
    const { name, value } = e.target;
    setNewUser((prevState) => ({
      ...prevState,
      [name]: value,
    }));
  };

  return (
    <div className="p-6 mb-6 border border-blue-500 rounded-lg shadow-md bg-white">
      <h2 className="text-xl font-bold mb-4">Danh sách người dùng</h2>
      <button
        className="bg-green-500 text-white px-4 py-2 rounded-lg shadow-sm hover:bg-green-600 mb-2"
        onClick={handleAddUserClick}
      >
        Thêm người dùng
      </button>

      {/* Modal */}
      {showModal && (
        <div className="fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center">
          <div className="bg-white p-6 rounded-lg shadow-md w-96">
            <h2 className="text-xl font-bold mb-4">Thêm người dùng</h2>
            <form>
              <div className="mb-4">
                <label className="block text-sm font-semibold mb-2">
                  Mã Người Dùng
                </label>
                <input
                  required
                  type="text"
                  name="MaNguoiDung"
                  value={newUser.MaNguoiDung}
                  onChange={handleChange}
                  className="border border-gray-300 p-2 w-full rounded-lg"
                />
              </div>
              <div className="mb-4">
                <label className="block text-sm font-semibold mb-2">
                  Vai Trò
                </label>
                <select
                  name="VaiTro"
                  value={newUser.VaiTro}
                  onChange={handleChange}
                  className="border border-gray-300 p-2 w-full rounded-lg"
                >
                  <option value="">Chọn vai trò</option>
                  <option value="GiangVien">Giảng viên</option>
                  <option value="NhanVien">Nhân viên</option>
                </select>
              </div>
              <div className="mb-4">
                <label className="block text-sm font-semibold mb-2">
                  Mật Khẩu
                </label>
                <input
                  required
                  type="password"
                  name="MatKhau"
                  value={newUser.MatKhau}
                  onChange={handleChange}
                  className="border border-gray-300 p-2 w-full rounded-lg"
                />
              </div>
              <div className="mb-4">
                <label className="block text-sm font-semibold mb-2">
                  Mã Nhân Viên
                </label>
                <input
                  required
                  type="text"
                  name="MaNhanVien"
                  value={newUser.MaNhanVien}
                  onChange={handleChange}
                  className="border border-gray-300 p-2 w-full rounded-lg"
                />
              </div>
            </form>
            <div className="flex justify-end">
              <button
                onClick={handleCloseModal}
                className="bg-gray-500 text-white px-4 py-2 rounded-lg shadow-sm mr-2"
              >
                Đóng
              </button>
              <button
                onClick={handleAddUser}
                className="bg-blue-500 text-white px-4 py-2 rounded-lg shadow-sm"
              >
                Thêm
              </button>
            </div>
          </div>
        </div>
      )}

      {/* Modal xác nhận xóa người dùng */}
      {showDeleteModal && (
        <div className="fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center">
          <div className="bg-white p-6 rounded-lg shadow-md w-96">
            <h2 className="text-xl font-bold mb-4">Xác nhận xóa người dùng</h2>
            <p>
              Bạn có chắc chắn muốn xóa người dùng{" "}
              <strong>{userToDelete?.MaNguoiDung}</strong> không?
            </p>
            <div className="flex justify-end mt-4">
              <button
                onClick={handleCloseDeleteModal}
                className="bg-gray-500 text-white px-4 py-2 rounded-lg shadow-sm mr-2"
              >
                Hủy
              </button>
              <button
                onClick={handleConfirmDelete}
                className="bg-red-500 text-white px-4 py-2 rounded-lg shadow-sm"
              >
                Xóa
              </button>
            </div>
          </div>
        </div>
      )}

      {/*Modal chi tiết*/}
      {showDetailModal && (
        <div className="fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center">
          <div className="bg-white p-6 rounded-lg shadow-md w-96">
            <h2 className="text-xl font-bold mb-4">Chi tiết nhân viên</h2>
            {nhanVienDetails ? (
              <div>
                <p>
                  <strong>Mã Nhân Viên:</strong> {nhanVienDetails.MaNhanVien}
                </p>
                <p>
                  <strong>Tên:</strong> {nhanVienDetails.TenNhanVien}
                </p>
                <p>
                  <strong>Số Điện Thoại:</strong> {nhanVienDetails.sdtNV}
                </p>
                <p>
                  <strong>Email:</strong> {nhanVienDetails.EmailNV}
                </p>
                <p>
                  <strong>Phòng Ban:</strong> {nhanVienDetails.PhongCongTac}
                </p>
              </div>
            ) : (
              <p>Đang tải thông tin...</p>
            )}
            <div className="flex justify-end mt-4">
              <button
                onClick={handleCloseDetailModal}
                className="bg-blue-500 text-white px-4 py-2 rounded-lg shadow-sm"
              >
                Đóng
              </button>
            </div>
          </div>
        </div>
      )}

      {/* Modal Sửa */}
      {showEditModal && (
        <div className="fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center">
          <div className="bg-white p-6 rounded-lg shadow-md w-96">
            <h2 className="text-xl font-bold mb-4">Sửa Mật Khẩu</h2>
            <form>
              <div className="mb-4">
                <label className="block text-sm font-semibold mb-2">
                  Mật Khẩu Mới
                </label>
                <input
                  name="MatKhau"
                  value={userToEdit?.MatKhau || ""}
                  onChange={handleEditChange}
                  className="border border-gray-300 p-2 w-full rounded-lg"
                  required
                />
              </div>
            </form>
            <div className="flex justify-end">
              <button
                onClick={handleCloseEditModal}
                className="bg-gray-500 text-white px-4 py-2 rounded-lg shadow-sm mr-2"
              >
                Đóng
              </button>
              <button
                onClick={handleUpdatePassword}
                className="bg-blue-500 text-white px-4 py-2 rounded-lg shadow-sm"
              >
                Cập nhật
              </button>
            </div>
          </div>
        </div>
      )}

      <p></p>
      {users.length === 0 ? (
        <p>Không có dữ liệu người dùng.</p>
      ) : (
        <table className="table-auto w-full border border-gray-300">
          <thead>
            <tr className="bg-gray-200">
              <th className="border border-gray-300 px-3 py-2">
                Mã Người Dùng
              </th>
              <th className="border border-gray-300 px-6 py-2">Vai Trò</th>
              <th className="border border-gray-300 px-6 py-2">Mật Khẩu</th>
            </tr>
          </thead>
          <tbody>
            {users.map((user) => (
              <tr key={user.MaNguoiDung} className="hover:bg-gray-50">
                <td className="border border-gray-300 px-3 py-2 text-center">
                  {user.MaNguoiDung}
                </td>
                <td className="border border-gray-300 px-6 py-2 text-center">
                  {user.VaiTro}
                </td>
                <td className="border border-gray-300 px-6 py-2 text-center">
                  {user.MatKhau}
                </td>
                <td className="border border-gray-300 text-center">
                  <button
                    className="text-blue-500 hover:text-blue-700"
                    onClick={() => handleEditClick(user)}
                  >
                    Sửa
                  </button>
                  &nbsp;&nbsp;&nbsp;
                  <button
                    className="text-red-500 hover:text-red-700"
                    onClick={() => handleDeleteClick(user)}
                  >
                    Xóa
                  </button>
                  &nbsp;&nbsp;&nbsp;
                  <button
                    className="text-green-500 hover:text-green-700"
                    onClick={() => handleDetailClick(user.MaNhanVien)}
                  >
                    Chi tiết
                  </button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      )}
    </div>
  );
};

export default User;
