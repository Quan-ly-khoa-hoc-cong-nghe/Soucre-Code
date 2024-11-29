import React, { useState, useEffect } from "react";
import { FaEye, FaCheck, FaTimes } from "react-icons/fa";
import axios from "axios";

const ScienceSeminardepartments = () => {
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
        "http://localhost/Soucre-Code/BackEnd/Api/DeTaiNCKHGiangVien_Api/LoaiHinhNCKHGV_Api.php?action=GET"
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
        "http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiGV/HoSoNCKHGV_Api.php?action=get"
      )
      .then((response) => {
        setApplications(response.data.HoSoNCKHGV || []);
      })
      .catch((error) => {
        console.error("Error fetching applications:", error);
      });
  };

  // Lấy thông tin các phòng ban (khoa)
  const fetchDepartments = () => {
    axios
      .get(
        "http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiSV/Khoa_Api.php?action=get"
      )
      .then((response) => {
        setDepartments(response.data.Khoa || []);
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
        "http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiGV/HoSoNCKHGV_Api.php?action=updateTrangThai",
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
        "http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiGV/HoSoNCKHGV_Api.php?action=updateTrangThai",
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
  // Hàm lưu đề tài mới
  // Hàm lưu đề tài mới
  const handleSaveTopic = () => {
    // Kiểm tra dữ liệu đã đầy đủ chưa
    if (!tenDeTai || !moTa || !fileHopDong || !loaiHinh || !selectedApp) {
      alert("Vui lòng điền đầy đủ thông tin!");
      return;
    }
  
    // Kiểm tra định dạng file (ví dụ: chỉ cho phép tệp PDF)
    if (fileHopDong && !fileHopDong.name.endsWith('.pdf')) {
      alert("Vui lòng chọn file hợp lệ (PDF).");
      return;
    }
  
    // Kiểm tra độ dài mô tả (tối thiểu 10 ký tự)
    if (moTa.length < 10) {
      alert("Mô tả phải dài ít nhất 10 ký tự.");
      return;
    }
  
    // Kiểm tra độ dài tên đề tài (tối thiểu 5 ký tự)
    if (tenDeTai.length < 5) {
      alert("Tên đề tài phải dài ít nhất 5 ký tự.");
      return;
    }
  
    // Tạo FormData để gửi dữ liệu
    const formData = new FormData();
    formData.append("MaHoSo", selectedApp.MaHoSo);
    formData.append("TenDeTai", tenDeTai);
    formData.append("MoTa", moTa);
    formData.append("LoaiHinh", loaiHinh);
  
    // Lấy tên tệp và thêm vào formData
    const fileName = fileHopDong.name;
    formData.append("FileHopDong", fileHopDong); // Gửi tệp tin thay vì chỉ tên tệp
  
    // Log dữ liệu gửi lên server (để debug)
    console.log("Sending data to server:", {
      MaHoSo: selectedApp.MaHoSo,
      TenDeTai: tenDeTai,
      MoTa: moTa,
      LoaiHinh: loaiHinh,
      FileHopDong: fileName,
    });
  
    // Gửi dữ liệu lên server
    axios
      .post(
        "http://localhost/Soucre-Code/BackEnd/Api/DeTaiNCKHGiangVien_Api/DeTaiNCKHGiangVien_Api.php?action=POST",
        formData
      )
      .then((response) => {
        console.log("API Response:", response); // Log phản hồi từ API
        if (response.data.message === "Thêm đề tài thành công") {
          alert("Đề tài đã được thêm thành công!");
          closeModal();
        } else {
          // Kiểm tra nếu có lỗi từ server
          if (response.data.errors) {
            let errorMessage = "Có lỗi xảy ra khi thêm đề tài:";
            for (const [field, error] of Object.entries(response.data.errors)) {
              errorMessage += `\n- ${field}: ${error}`;
            }
            alert(errorMessage);
          } else {
            alert(
              "Có lỗi xảy ra khi thêm đề tài. " +
                (response.data.message || "Vui lòng kiểm tra lại.")
            );
          }
        }
      })
      .catch((error) => {
        console.error("Error saving topic:", error); // Log lỗi chi tiết
  
        if (error.response) {
          // Lỗi từ server
          console.error("Response error:", error.response.data);
          alert(
            "Đã xảy ra lỗi khi gửi yêu cầu: " +
              (error.response.data.message || error.response.status)
          );
        } else if (error.request) {
          // Lỗi khi yêu cầu không được gửi (có thể do lỗi mạng)
          console.error("Request error:", error.request);
          alert("Không nhận được phản hồi từ server. Kiểm tra kết nối mạng.");
        } else {
          // Lỗi khác trong quá trình setup request
          console.error("Error message:", error.message);
          alert("Đã xảy ra lỗi: " + error.message);
        }
      });
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
            <th className="px-4 py-2 border">Actions</th>
          </tr>
        </thead>
        <tbody>
          {applications.length > 0 ? (
            applications.map((app) => (
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
                <td className="py-4 px-2 text-right">
                  <div className="flex justify-end space-x-2">
                    {app.TrangThai !== "Đã duyệt" && app.TrangThai !== "Hủy" ? (
                      <>
                        <button
                          className="p-2 text-green-600 hover:bg-green-100 rounded-full"
                          title="Approve"
                          onClick={() => approveApplication(app)} // Gọi hàm approveApplication với hồ sơ app
                        >
                          <FaCheck className="w-5 h-5" />
                        </button>

                        <button
                          className="p-2 text-red-600 hover:bg-red-100 rounded-full"
                          title="Reject"
                          onClick={() => approveApplicationCancel(app)} // Gọi hàm approveApplication với hồ sơ app
                        >
                          <FaTimes className="w-5 h-5" />
                        </button>
                      </>
                    ) : null}

                    {/* Only show "Add Topic" button if the status is not "Khoa đã duyệt" or "Hủy" */}
                    {app.TrangThai !== "Khoa đã duyệt" &&
                      app.TrangThai !== "Hủy" && (
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
            ))
          ) : (
            <tr>
              <td
                colSpan="5"
                className="px-4 py-2 border text-center text-gray-500"
              >
                No applications found.
              </td>
            </tr>
          )}
        </tbody>
      </table>

      {isModalOpen && selectedApp && (
        <div className="fixed inset-0 bg-gray-500 bg-opacity-75 flex justify-center items-center z-50">
          <div className="bg-white p-6 rounded-lg shadow-lg w-1/2 max-h-[80vh] overflow-y-auto">
            <h2 className="text-xl font-semibold mb-4">Thêm Đề Tài</h2>
            <p>
              <strong>Application ID:</strong> {selectedApp.MaHoSo}
            </p>
            <p>
              <strong>Submission Date:</strong> {selectedApp.NgayNop}
            </p>

            {/* Tên Đề Tài */}
            <div className="mt-4">
              <label
                className="block text-sm font-medium text-gray-700"
                htmlFor="TenDeTai"
              >
                Tên Đề Tài
              </label>
              <input
                type="text"
                id="TenDeTai"
                className="w-full p-2 mt-1 border border-gray-300 rounded-lg"
                placeholder="Nhập tên đề tài"
                value={tenDeTai}
                onChange={(e) => setTenDeTai(e.target.value)}
              />
            </div>

            {/* Mô Tả */}
            <div className="mt-4">
              <label
                className="block text-sm font-medium text-gray-700"
                htmlFor="MoTa"
              >
                Mô Tả
              </label>
              <textarea
                id="MoTa"
                className="w-full h-32 p-2 mt-1 border border-gray-300 rounded-lg"
                placeholder="Nhập mô tả"
                value={moTa}
                onChange={(e) => setMoTa(e.target.value)}
              />
            </div>

            {/* File Hợp Đồng */}
            <div className="mt-4">
              <label
                className="block text-sm font-medium text-gray-700"
                htmlFor="FileHopDong"
              >
                File Hợp Đồng
              </label>
              <input
                type="file"
                id="FileHopDong"
                className="w-full p-2 mt-1 border border-gray-300 rounded-lg"
                onChange={(e) => setFileHopDong(e.target.files[0])}
              />
            </div>
            {/* Combo Box: Chọn Loại Hình */}
            <div className="mt-4">
              <label
                className="block text-sm font-medium text-gray-700"
                htmlFor="LoaiHinh"
              >
                Chọn Loại Hình
              </label>
              <select
                id="LoaiHinh"
                className="w-full p-2 mt-1 border border-gray-300 rounded-lg"
                value={loaiHinh}
                onChange={handleLoaiHinhChange} // Gọi hàm xử lý khi chọn
              >
                <option value="">-- Chọn Loại Hình --</option>
                {loaiHinhOptions.map((item) => (
                  <option key={item.MaLoaiHinhNCKH} value={item.MaLoaiHinhNCKH}>
                    {item.TenLoaiHinh}
                  </option>
                ))}
              </select>
            </div>
            {/* Combo Box: Chọn Khoa */}
            <div className="mt-4">
              <label
                className="block text-sm font-medium text-gray-700"
                htmlFor="giangvien"
              >
                Chọn giảng viên
              </label>
              <select
                id="Khoa"
                className="w-full p-2 mt-1 border border-gray-300 rounded-lg"
              >
                <option value="">-- Chọn giảng viên --</option>
                <option value="Khoa1">Giảng viên 1</option>
                <option value="Khoa2">Giảng viên 2</option>
              </select>
            </div>

            {/* Kế Hoạch NCKH */}
            <div className="mt-4 grid grid-cols-2 gap-4">
              {/* Ngày Bắt Đầu */}
              <div>
                <label
                  className="block text-sm font-medium text-gray-700"
                  htmlFor="NgayBatDau"
                >
                  Ngày Bắt Đầu
                </label>
                <input
                  type="date"
                  id="NgayBatDau"
                  className="w-full p-2 mt-1 border border-gray-300 rounded-lg"
                />
              </div>

              {/* Ngày Kết Thúc */}
              <div>
                <label
                  className="block text-sm font-medium text-gray-700"
                  htmlFor="NgayKetThuc"
                >
                  Ngày Kết Thúc
                </label>
                <input
                  type="date"
                  id="NgayKetThuc"
                  className="w-full p-2 mt-1 border border-gray-300 rounded-lg"
                />
              </div>

              {/* Kinh Phí */}
              <div>
                <label
                  className="block text-sm font-medium text-gray-700"
                  htmlFor="KinhPhi"
                >
                  Kinh Phí
                </label>
                <input
                  type="number"
                  id="KinhPhi"
                  className="w-full p-2 mt-1 border border-gray-300 rounded-lg"
                  placeholder="Nhập kinh phí"
                />
              </div>

              {/* File Kế Hoạch */}
              <div>
                <label
                  className="block text-sm font-medium text-gray-700"
                  htmlFor="FileKeHoach"
                >
                  File Kế Hoạch
                </label>
                <input
                  type="file"
                  id="FileKeHoach"
                  className="w-full p-2 mt-1 border border-gray-300 rounded-lg"
                />
              </div>
            </div>

            {/* Close and Save Buttons */}
            <div className="mt-4 flex justify-end space-x-2">
              <button
                onClick={closeModal}
                className="px-4 py-2 bg-gray-300 text-gray-700 rounded-md"
              >
                Close
              </button>
              <button
                onClick={handleSaveTopic}
                className="px-4 py-2 bg-blue-600 text-white rounded-md"
              >
                Save Topic
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};

export default ScienceSeminardepartments;
