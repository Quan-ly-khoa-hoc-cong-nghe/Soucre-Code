import React, { useState, useEffect } from "react";
import { FaEye, FaCheck, FaTimes } from "react-icons/fa";
import axios from "axios";

const LecturerApplicationApprovalListAdmin = () => {
  const [applications, setApplications] = useState([]);
  const [departments, setDepartments] = useState([]);
  const [researchTypes, setResearchTypes] = useState([]);
  const [lecturers, setLecturers] = useState([]);
  const [selectedApp, setSelectedApp] = useState(null);
  const [selectedResearchType, setSelectedResearchType] = useState("");
  const [topicName, setTopicName] = useState(""); // Tên đề tài
  const [description, setDescription] = useState(""); // Mô tả
  const [contractFile, setContractFile] = useState(null); // File hợp đồng
  const [selectedLecturers, setSelectedLecturers] = useState([]); // Giảng viên đã chọn
  const [loading, setLoading] = useState(false); // Loading state khi gửi yêu cầu
  const [isModalOpen, setIsModalOpen] = useState(false);
  

  useEffect(() => {
    fetchApplications();
    fetchDepartments();
    fetchResearchTypes();
    fetchLecturers(); // Gọi fetchLecturers để lấy giảng viên
  }, []);

  const fetchApplications = () => {
    axios
      .get("http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiGV/HoSoNCKHGV_Api.php?action=get")
      .then((response) => setApplications(response.data.HoSoNCKHGV || []))
      .catch((error) => console.error("Error fetching applications:", error));
  };

  const fetchDepartments = () => {
    axios
      .get("http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiSV/Khoa_Api.php?action=get")
      .then((response) => setDepartments(response.data.Khoa || []))
      .catch((error) => console.error("Error fetching departments:", error));
  };

  const fetchLecturers = () => {
    axios
      .get("http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiSv/GiangVien_Api.php?action=get")
      .then((response) => setLecturers(response.data.GiangVien || []))
      .catch((error) => console.error("Error fetching lecturers:", error));
  };

  const fetchResearchTypes = () => {
    axios
      .get("http://localhost/Soucre-Code/BackEnd/Api/DeTaiNCKHGiangVien_Api/LoaiHinhNCKHGV_Api.php?action=get")
      .then((response) => setResearchTypes(response.data || []))
      .catch((error) => console.error("Error fetching research types:", error));
  };

 const saveTopic = () => {
    // Kiểm tra tính hợp lệ của dữ liệu
    if (!topicName || !description || !selectedResearchType) {
        alert("Vui lòng điền đầy đủ thông tin.");
        return;
    }

    setLoading(true);

    // Tạo đối tượng dữ liệu theo cấu trúc JSON mong muốn
    const data = {
        MaDeTaiNCKHGV: "DTNCGV3", // Bạn nên tạo mã đề tài một cách duy nhất, có thể từ backend hoặc một hàm tạo mã riêng
        TenDeTai: topicName,
        MoTa: description,
        FileHopDong: contractFile ? contractFile.name : "", // Chỉ gửi tên file
        MaHoSo: selectedApp.MaHoSo,
        MaLoaiHinhNCKH: parseInt(selectedResearchType, 10) // Đảm bảo đây là số nguyên
    };

    // Gửi yêu cầu POST với dữ liệu JSON
    axios.post(
        "http://localhost/Soucre-Code/BackEnd/Api/DeTaiNCKHGiangVien_Api/DeTaiNCKHGiangVien_Api.php?action=POST",
        data,
        {
            headers: {
                'Content-Type': 'application/json'
            }
        }
    )
    .then((response) => {
        console.log(response); // Kiểm tra dữ liệu phản hồi
        if (response.data && response.data.success) { // Giả sử API trả về `success`
            alert("Đề tài đã được lưu thành công!");
            setLoading(false);
            closeModal();
            fetchApplications(); // Cập nhật lại danh sách ứng dụng nếu cần
        } else {
            alert("Có lỗi xảy ra khi lưu đề tài: " + (response.data.message || "Không có thông báo lỗi"));
            setLoading(false);
        }
    })
    .catch((error) => {
        console.error("Có lỗi khi gửi yêu cầu:", error); // In lỗi ra console để debug
        alert("Có lỗi xảy ra khi lưu đề tài: " + error.message);
        setLoading(false);
    });
};


  const approveApplication = (app) => {
    const requestData = { MaHoSo: app.MaHoSo, TrangThai: "Đã duyệt" };

    axios
      .put("http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiGV/HoSoNCKHGV_Api.php?action=updateTrangThai", requestData)
      .then((response) => {
        if (response.data.message === "Cập nhật trạng thái hồ sơ thành công") {
          alert("Cập nhật trạng thái thành công!");
        } else {
          alert("Có lỗi xảy ra: " + response.data.message);
        }
        fetchApplications(); // Cập nhật lại danh sách ứng dụng
      })
      .catch((error) => {
        alert("Đã xảy ra lỗi khi gửi yêu cầu: " + error.message);
      });
  };

  const approveApplicationCancel = (app) => {
    const requestData = { MaHoSo: app.MaHoSo, TrangThai: "Hủy" };

    axios
      .put("http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiGV/HoSoNCKHGV_Api.php?action=updateTrangThai", requestData)
      .then((response) => {
        if (response.data.message === "Cập nhật trạng thái hồ sơ thành công") {
          alert("Cập nhật trạng thái thành công!");
        } else {
          alert("Có lỗi xảy ra: " + response.data.message);
        }
        fetchApplications(); // Cập nhật lại danh sách ứng dụng
      })
      .catch((error) => {
        alert("Đã xảy ra lỗi khi gửi yêu cầu: " + error.message);
      });
  };

  const openModal = (app) => {
    setSelectedApp(app);
    setIsModalOpen(true);
  };

  const closeModal = () => {
    setIsModalOpen(false);
    setSelectedApp(null);
    setTopicName("");
    setDescription("");
    setContractFile(null);
    setSelectedResearchType("");
  };

  const handleResearchTypeSelect = (e) => {
    setSelectedResearchType(e.target.value);
  };

  return (
    <div className="bg-white shadow-lg rounded-lg p-6">
      <table className="w-full border-collapse border border-gray-200">
        <thead className="bg-gray-100">
          <tr>
            <th className="px-4 py-2 border">Mã hồ sơ</th>
            <th className="px-4 py-2 border">Ngày nộp</th>
            <th className="px-4 py-2 border">File hồ sơ</th>
            <th className="px-4 py-2 border">Trạng thái</th>
            <th className="px-4 py-2 border">Khoa</th>
            <th className="px-4 py-2 border">Thao tác</th>
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
                <td className="px-4 py-2 border">
                  {
                    departments.find((dept) => dept.MaKhoa === app.MaKhoa)
                      ?.TenKhoa || "Chưa có"
                  }
                </td>
                <td className="py-4 px-2 text-right">
  <div className="flex justify-end space-x-2">
    {/* Kiểm tra trạng thái và hiển thị các nút tương ứng */}
    {app.TrangThai === "Đã duyệt" ? (
      // Trạng thái Đã duyệt, chỉ hiển thị nút "Thêm đề tài"
      <button
        className="text-green-600 hover:underline text-sm font-medium"
        onClick={() => openModal(app)}
      >
        Thêm đề tài
      </button>
    ) : app.TrangThai !== "Hủy" ? (
      // Trạng thái không phải Hủy, hiển thị các nút "Approve" và "Reject"
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
    
    {/* Nút "View Details" luôn hiển thị */}
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
              <td colSpan="5" className="px-4 py-2 border text-center text-gray-500">
                No applications found.
              </td>
            </tr>
          )}
        </tbody>
      </table>

      {/* Modal */}
      {isModalOpen && selectedApp && (
        <div
          className="fixed inset-0 bg-gray-500 bg-opacity-75 flex justify-center items-center z-50"
          role="dialog"
          aria-modal="true"
          aria-labelledby="modal-title"
        >
          <div className="bg-white p-6 rounded-lg shadow-lg w-1/2 max-h-[80vh] overflow-y-auto">
            <h2 id="modal-title" className="text-xl font-semibold mb-4">
              Thêm Đề Tài
            </h2>
            <p>
              <strong>Application ID:</strong> {selectedApp.MaHoSo}
            </p>
            <p>
              <strong>Submission Date:</strong> {selectedApp.NgayNop}
            </p>
            <div className="mt-4">
              {/* Tên đề tài */}
              <label className="block text-gray-700 mb-1">Tên đề tài:</label>
              <input
                type="text"
                value={topicName}
                onChange={(e) => setTopicName(e.target.value)}
                className="w-full p-2 border border-gray-300 rounded-lg mb-4"
                placeholder="Nhập tên đề tài..."
              />
              {/* Mô tả */}
              <label className="block text-gray-700 mb-1">Mô tả:</label>
              <textarea
                value={description}
                onChange={(e) => setDescription(e.target.value)}
                className="w-full h-24 p-2 border border-gray-300 rounded-lg mb-4"
                placeholder="Nhập mô tả đề tài..."
              ></textarea>

              {/* File hợp đồng */}
              <label className="block text-gray-700 mb-1">File hợp đồng:</label>
              <input
                type="file"
                onChange={(e) => setContractFile(e.target.files[0])}
                className="w-full p-2 border border-gray-300 rounded-lg mb-4"
              />
            </div>

            {/* Loại hình NCKH */}
            <label className="block text-gray-700 mb-1">Loại hình NCKH:</label>
            <select
              value={selectedResearchType}
              onChange={handleResearchTypeSelect}
              className="w-full p-2 border border-gray-300 rounded-lg mb-4"
            >
              <option value="">-- Chọn loại hình NCKH --</option>
              {researchTypes.map((type) => (
                <option key={type.MaLoaiHinhNCKH} value={type.MaLoaiHinhNCKH}>
                  {type.TenLoaiHinh}
                </option>
              ))}
            </select>
            <div className="mt-4 flex justify-end space-x-2">
              <button
                onClick={closeModal}
                className="px-4 py-2 bg-gray-300 text-gray-700 rounded-md"
              >
                Close
              </button>
              <button
                onClick={saveTopic}
                disabled={loading}
                className="px-4 py-2 bg-blue-600 text-white rounded-md"
              >
                {loading ? "Saving..." : "Save Topic"}
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};

export default LecturerApplicationApprovalListAdmin;
