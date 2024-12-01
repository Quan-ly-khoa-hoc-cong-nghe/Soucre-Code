import React, { useState, useEffect } from "react";
import { FaEye, FaCheck, FaTimes } from "react-icons/fa";
import axios from "axios";

const ApprovalOfSchoolTopicSciTech = () => {
  const [applications, setApplications] = useState([]);
  const [departments, setDepartments] = useState([]);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [selectedApp, setSelectedApp] = useState(null);
  const [loaiHinhOptions, setLoaiHinhOptions] = useState([]);
  const [tenDeTai, setTenDeTai] = useState("");
  const [moTa, setMoTa] = useState("");
  const [fileHopDong, setFileHopDong] = useState(null);
  const [loaiHinh, setLoaiHinh] = useState("");

  // Fetch dữ liệu khi component mount
  useEffect(() => {
    fetchApplications();
    fetchDepartments();
    fetchLoaiHinh();
  }, []);

  // Lấy thông tin Loại Hình
  const fetchLoaiHinh = () => {
    axios
      .get(
        "http://localhost/Soucre-Code/BackEnd/Api/DeTaiCapCoSo_Api/HoSoDTCS_Api.php?action=get"
      )
      .then((response) => {
        setLoaiHinhOptions(response.data);
      })
      .catch((error) => {
        console.error("Error fetching LoaiHinh:", error);
      });
  };

  // Lấy danh sách các ứng dụng (hồ sơ)
  const fetchApplications = () => {
    axios
      .get(
        "http://localhost/Soucre-Code/BackEnd/Api/DeTaiCapCoSo_Api/HoSoDTCS_Api.php?action=get"
      )
      .then((response) => {
        setApplications(response.data || []); // Cập nhật theo cấu trúc dữ liệu API mới
      })
      .catch((error) => {
        console.error("Error fetching applications:", error);
      });
  };

  // Lấy thông tin các phòng ban (khoa)
  const fetchDepartments = () => {
    axios
      .get(
        "http://localhost/Soucre-Code/BackEnd/Api/DeTaiCapCoSo_Api/Khoa_Api.php?action=get"
      )
      .then((response) => {
        setDepartments(response.data.Khoa || []); // Cập nhật theo cấu trúc dữ liệu API mới
      })
      .catch((error) => {
        console.error("Error fetching departments:", error);
      });
  };

  // Hàm duyệt ứng dụng (xác nhận hồ sơ)
  const approveApplication = (app) => {
    const requestData = {
      MaHoSo: app.MaHoSo, // Mã hồ sơ
      TrangThai: "Đã duyệt", // Trạng thái mới
    };

    // Cập nhật trạng thái hồ sơ
    axios
      .put(
        "http://localhost/Soucre-Code/BackEnd/Api/DeTaiCapCoSo_Api/HoSoDTCS_Api.php?action=updateTrangThai",
        requestData
      )
      .then((response) => {
        if (response.data.message === "Cập nhật trạng thái hồ sơ thành công") {
          alert("Cập nhật trạng thái thành công!");
          fetchApplications(); // Lấy lại danh sách ứng dụng sau khi cập nhật
        } else {
          alert("Có lỗi xảy ra: " + response.data.message);
        }
      })
      .catch((error) => {
        console.error("Error approving application:", error);
        alert("Đã xảy ra lỗi khi gửi yêu cầu: " + error.message);
      });
  };

  // Hàm hủy duyệt ứng dụng
  const approveApplicationCancel = (app) => {
    const requestData = {
      MaHoSo: app.MaHoSo, // Mã hồ sơ
      TrangThai: "Hủy", // Trạng thái mới
    };

    axios
      .put(
        "http://localhost/Soucre-Code/BackEnd/Api/DeTaiCapCoSo_Api/HoSoDTCS_Api.php?action=updateTrangThai",
        requestData
      )
      .then((response) => {
        if (response.data.message === "Cập nhật trạng thái hồ sơ thành công") {
          alert("Cập nhật trạng thái thành công!");
          fetchApplications(); // Lấy lại danh sách ứng dụng sau khi cập nhật
        } else {
          alert("Có lỗi xảy ra: " + response.data.message);
        }
      })
      .catch((error) => {
        console.error("Error cancelling application approval:", error);
        alert("Đã xảy ra lỗi khi gửi yêu cầu: " + error.message);
      });
  };

  // Hàm mở modal để thêm đề tài
  const openModal = (app) => {
    setSelectedApp(app);
    setIsModalOpen(true);
  };

  // Hàm đóng modal
  const closeModal = () => {
    setIsModalOpen(false);
    setSelectedApp(null);
    setTenDeTai("");
    setMoTa("");
    setFileHopDong(null);
    setLoaiHinh("");
  };

  // Hàm xử lý khi người dùng chọn loại hình
  const handleLoaiHinhChange = (event) => {
    const selectedLoaiHinh = event.target.value;
    setLoaiHinh(selectedLoaiHinh);

    // Tìm đối tượng LoaiHinh dựa trên TenLoaiHinh và lấy MaLoaiHinhNCKH
    const selectedLoaiHinhObj = loaiHinhOptions.find(
      (item) => item.MaLoaiHinhNCKH === selectedLoaiHinh
    );

    // Kiểm tra xem có tìm thấy không, nếu có thì set thêm dữ liệu cần thiết
    if (selectedLoaiHinhObj) {
      console.log("Selected Loai Hinh:", selectedLoaiHinhObj);
      // Bạn có thể lưu thêm các thông tin cần thiết từ đối tượng vào state nếu cần
    }
  };

  return (
    <div className="bg-white shadow-lg rounded-lg p-6">
      <table className="w-full border-collapse border border-gray-200">
        <thead className="bg-gray-100">
          <tr>
            <th className="px-4 py-2 border">Application ID</th>
            <th className="px-4 py-2 border">Submission Date</th>
            <th className="px-4 py-2 border">File</th>
            <th className="px-4 py-2 border">Status</th>
            <th className="px-4 py-2 border">Department Name</th>
            <th className="px-4 py-2 border">Actions</th>
          </tr>
        </thead>
        <tbody>
          {applications.length > 0 ? (
            applications.map((app) => {
              const department = departments.find(
                (dept) => dept.MaKhoa === app.MaKhoa
              );
              const departmentName = department ? department.TenKhoa : "Không có khoa";

              return (
                <tr key={app.MaHoSo} className="hover:bg-gray-50">
                  <td className="px-4 py-2 border">{app.MaHoSo}</td>
                  <td className="px-4 py-2 border">{app.NgayNop}</td>
                  <td className="px-4 py-2 border">
                    <a
                      href={`http://localhost/uploads/${app.FileHoSo}`}
                      target="_blank"
                      rel="noopener noreferrer"
                      className="text-blue-500 hover:underline"
                    >
                      {app.FileHoSo}
                    </a>
                  </td>
                  <td className="px-4 py-2 border">
                    <span
                      className={`px-2 py-1 rounded-full text-sm ${
                        app.TrangThai === "Khoa đã duyệt"
                          ? "bg-blue-100 text-blue-800"
                          : app.TrangThai === "Đã duyệt"
                          ? "bg-green-100 text-green-800"
                          : "bg-red-100 text-red-800"
                      }`}
                    >
                      {app.TrangThai}
                    </span>
                  </td>
                  <td className="px-4 py-2 border">{departmentName}</td>
                  <td className="py-4 px-2 text-right">
                    <div className="flex justify-end space-x-2">
                      {app.TrangThai !== "Đã duyệt" && app.TrangThai !== "Hủy" ? (
                        <>
                          <button
                            className="p-2 text-green-600 hover:bg-green-100 rounded-full"
                            title="Approve"
                            onClick={() => approveApplication(app)}
                          >
                            <FaCheck className="w-5 h-5" />
                          </button>

                          <button
                            className="p-2 text-red-600 hover:bg-red-100 rounded-full"
                            title="Reject"
                            onClick={() => approveApplicationCancel(app)}
                          >
                            <FaTimes className="w-5 h-5" />
                          </button>
                        </>
                      ) : null}

                      {app.TrangThai !== "Khoa đã duyệt" && app.TrangThai !== "Hủy" && (
                        <button
                          className="text-green-600 hover:underline text-sm font-medium"
                          onClick={() => openModal(app)}
                        >
                          Thêm đề tài
                        </button>
                      )}

                      <button
                        className="p-2 text-blue-600 hover:bg-blue-100 rounded-full"
                        title="View Details"
                      >
                        <FaEye className="w-5 h-5" />
                      </button>
                    </div>
                  </td>
                </tr>
              );
            })
          ) : (
            <tr>
              <td colSpan="6" className="text-center py-4">
                Không có hồ sơ nào.
              </td>
            </tr>
          )}
        </tbody>
      </table>

      {isModalOpen && selectedApp && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-gray-600 bg-opacity-50">
          <div className="bg-white rounded-lg p-6 w-1/2">
            <h2 className="text-xl font-bold mb-4">Chi tiết hồ sơ</h2>
            <div className="mb-4">
              <label className="block text-sm font-medium">Tên đề tài</label>
              <input
                type="text"
                value={tenDeTai}
                onChange={(e) => setTenDeTai(e.target.value)}
                className="w-full border border-gray-300 rounded-md p-2"
              />
            </div>
            <div className="mb-4">
              <label className="block text-sm font-medium">Mô tả</label>
              <textarea
                value={moTa}
                onChange={(e) => setMoTa(e.target.value)}
                className="w-full border border-gray-300 rounded-md p-2"
                rows="4"
              />
            </div>
            <div className="mb-4">
              <label className="block text-sm font-medium">Loại hình</label>
              <select
                value={loaiHinh}
                onChange={handleLoaiHinhChange}
                className="w-full border border-gray-300 rounded-md p-2"
              >
                <option value="">Chọn loại hình</option>
                {loaiHinhOptions.map((item) => (
                  <option key={item.MaLoaiHinhNCKH} value={item.MaLoaiHinhNCKH}>
                    {item.TenLoaiHinh}
                  </option>
                ))}
              </select>
            </div>
            <div className="mb-4">
              <label className="block text-sm font-medium">Hợp đồng</label>
              <input
                type="file"
                onChange={(e) => setFileHopDong(e.target.files[0])}
                className="w-full border border-gray-300 rounded-md p-2"
              />
            </div>
            <div className="flex justify-end space-x-2">
              <button
                onClick={closeModal}
                className="px-4 py-2 bg-gray-500 text-white rounded-lg"
              >
                Đóng
              </button>
              <button
                onClick={() => {
                  // Thực hiện lưu dữ liệu ở đây
                  closeModal();
                }}
                className="px-4 py-2 bg-blue-500 text-white rounded-lg"
              >
                Lưu
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};

export default ApprovalOfSchoolTopicSciTech;
