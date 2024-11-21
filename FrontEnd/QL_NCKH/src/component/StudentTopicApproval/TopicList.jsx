import React, { useEffect, useState } from "react";
import axios from "axios";
import { FaCheck, FaTimes, FaEye } from "react-icons/fa";
// Base API URL
const API_BASE = "http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiSV";

// API Endpoints
const API_ENDPOINTS = {
  deTaiNCKHSV: `${API_BASE}/DeTaiNCKHSV_Api.php?action=get`,
  giangVien: `${API_BASE}/GiangVien_Api.php?action=get`,
  giangVienNCKHSV: `${API_BASE}/GiangVienNCKHSV_Api.php?action=get`,
  hoSoNCKHSV: `${API_BASE}/HoSoNCKHSV_Api.php?action=get`,
  keHoachNCKHSV: `${API_BASE}/KeHoachNCKHSV_Api.php?action=get`,
  khoa: `${API_BASE}/Khoa_Api.php?action=get`,
  nhomNCKHSV: `${API_BASE}/NhomNCKHSV_Api.php?action=get`,
  sanPhamNCKHSV: `${API_BASE}/SanPhamNCKHSV_Api.php?action=get`,
  sinhVien: `${API_BASE}/SinhVien_Api.php?action=get`,
  sinhVienNCKHSV: `${API_BASE}/SinhVienNCKHSV_Api.php?action=get`,
};

// Modal Components

// Student Detail Modal
const StudentDetailModal = ({ student, onClose }) => (
  <div className="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div className="bg-white rounded-lg p-6 max-w-lg w-full shadow-lg">
      <h2 className="text-2xl font-bold mb-4">
        {student.name} (ID: {student.id})
      </h2>
      <p>
        <strong>Phone:</strong> {student.phone}
      </p>
      <p>
        <strong>Email:</strong> {student.email}
      </p>
      <div className="mt-4 flex justify-center">
        <button
          onClick={onClose}
          className="mt-4 p-2 bg-blue-500 text-white rounded"
        >
          Close
        </button>
      </div>
    </div>
  </div>
);

// Advisor Detail Modal
const AdvisorDetailModal = ({ advisor, onClose }) => (
  <div className="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div className="bg-white rounded-lg p-6 max-w-lg w-full shadow-lg">
      <h2 className="text-2xl font-bold mb-4">
        {advisor.name} (ID: {advisor.id})
      </h2>
      <p>
        <strong>Department:</strong> {advisor.department}
      </p>
      <p>
        <strong>Email:</strong> {advisor.email}
      </p>
      <p>
        <strong>Address:</strong> {advisor.address}
      </p>
      <div className="mt-4 flex justify-center">
        <button
          onClick={onClose}
          className="mt-4 p-2 bg-blue-500 text-white rounded"
        >
          Close
        </button>
      </div>
    </div>
  </div>
);

// Main TopicList Component
const TopicList = () => {
  // State Variables
  const [topics, setTopics] = useState([]);
  const [selectedTopic, setSelectedTopic] = useState(null);
  const [showStudentDetail, setShowStudentDetail] = useState(false);
  const [showAdvisorDetail, setShowAdvisorDetail] = useState(false);
  const [selectedStudent, setSelectedStudent] = useState(null);
  const [isEditing, setIsEditing] = useState(false);
  const [editedTopic, setEditedTopic] = useState({});
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  // Fetch Data from APIs
  useEffect(() => {
    const getData = async () => {
      setLoading(true);
      setError(null);
      try {
        // Fetch all data concurrently
        const [
          deTaiResponse,
          giangVienResponse,
          giangVienNCKHSVResponse,
          hoSoNCKHSVResponse,
          keHoachNCKHSVResponse,
          khoaResponse,
          nhomNCKHSVResponse,
          sanPhamNCKHSVResponse,
          sinhVienResponse,
          sinhVienNCKHSVResponse,
        ] = await Promise.all([
          axios.get(API_ENDPOINTS.deTaiNCKHSV),
          axios.get(API_ENDPOINTS.giangVien),
          axios.get(API_ENDPOINTS.giangVienNCKHSV),
          axios.get(API_ENDPOINTS.hoSoNCKHSV),
          axios.get(API_ENDPOINTS.keHoachNCKHSV),
          axios.get(API_ENDPOINTS.khoa),
          axios.get(API_ENDPOINTS.nhomNCKHSV),
          axios.get(API_ENDPOINTS.sanPhamNCKHSV),
          axios.get(API_ENDPOINTS.sinhVien),
          axios.get(API_ENDPOINTS.sinhVienNCKHSV),
        ]);

        // Extract data with safe defaults
        const deTaiData = deTaiResponse.data.DeTaiNCKHSV || [];
        const giangVienData = giangVienResponse.data.GiangVien || [];
        const giangVienNCKHSVData =
          giangVienNCKHSVResponse.data.GiangVienNCKHSV || [];
        const hoSoNCKHSVData = hoSoNCKHSVResponse.data.HoSoNCKHSV || [];
        const keHoachNCKHSVData =
          keHoachNCKHSVResponse.data.KeHoachNCKHSV || [];
        const khoaData = khoaResponse.data.Khoa || [];
        const nhomNCKHSVData = nhomNCKHSVResponse.data.NhomNCKHSV || [];
        const sanPhamNCKHSVData =
          sanPhamNCKHSVResponse.data.SanPhamNCKHSV || [];
        const sinhVienData = sinhVienResponse.data.SinhVien || [];
        const sinhVienNCKHSVData =
          sinhVienNCKHSVResponse.data.SinhVienNCKHSV || [];

        // Create Maps for easy lookup
        const giangVienMap = {};
        giangVienData.forEach((gv) => {
          giangVienMap[gv.MaGV] = gv;
        });

        const sinhVienMap = {};
        sinhVienData.forEach((sv) => {
          sinhVienMap[sv.MaSinhVien] = sv;
        });

        const hoSoMap = {};
        hoSoNCKHSVData.forEach((hs) => {
          hoSoMap[hs.MaHoSo] = hs;
        });

        const khoaMap = {};
        khoaData.forEach((kh) => {
          khoaMap[kh.MaKhoa] = kh;
        });

        const giangVienNhomMap = {};
        giangVienNCKHSVData.forEach((item) => {
          giangVienNhomMap[item.MaNhomNCKHSV] = item.MaGV;
        });

        const sinhVienNhomMap = {};
        sinhVienNCKHSVData.forEach((item) => {
          if (!sinhVienNhomMap[item.MaNhomNCKHSV]) {
            sinhVienNhomMap[item.MaNhomNCKHSV] = [];
          }
          const student = sinhVienMap[item.MaSinhVien];
          if (student) {
            sinhVienNhomMap[item.MaNhomNCKHSV].push({
              id: student.MaSinhVien,
              name: student.TenSinhVien,
              birthDate: student.NgaySinhSV || student.birthDate,
              phone: student.sdtSV || student.phone,
              email: student.EmailSV || student.email,
              major: khoaMap[student.MaKhoa]?.TenKhoa || student.major, // Map MaKhoa to TenKhoa
            });
          }
        });

        // Combine Data into Topics
        const combinedTopics = deTaiData.map((deTai) => {
          const groupId = deTai.MaNhomNCKHSV;
          const advisorId = giangVienNhomMap[groupId];
          const advisor = giangVienMap[advisorId] || {};
          const students = sinhVienNhomMap[groupId] || [];

          // Get corresponding research plan
          const keHoach =
            keHoachNCKHSVData.find((kh) => kh.MaDeTaiSV === deTai.MaDeTaiSV) ||
            {};

          // Get corresponding research products
          const sanPham = sanPhamNCKHSVData.filter(
            (sp) => sp.MaDeTaiSV === deTai.MaDeTaiSV
          );

          // Get corresponding hoSo
          const hoSo = hoSoMap[deTai.MaHoSo] || {};

          return {
            id: deTai.MaDeTaiSV, // Use MaDeTaiSV as ID
            name: deTai.TenDeTai,
            description: deTai.MoTa, // Ensure description is set
            status: deTai.TrangThai,
            groupId: groupId,
            fileHopDong: deTai.FileHopDong,
            hoSo: hoSo,
            keHoach: keHoach,
            sanPham: sanPham,
            students: students,
            advisor: {
              id: advisor.MaGV || "N/A",
              name: advisor.HoTenGV || "N/A",
              department: khoaMap[advisor.MaKhoa]?.TenKhoa || "N/A",
              email: advisor.EmailGV || "N/A",
              address: advisor.DiaChiGV || "Chưa có địa chỉ", // Ensure correct field
            },
          };
        });

        setTopics(combinedTopics);
      } catch (error) {
        console.error("Error fetching data:", error);
        setError("Đã xảy ra lỗi khi tải dữ liệu. Vui lòng thử lại sau.");
      } finally {
        setLoading(false);
      }
    };

    getData();
  }, []);

  // Handle Edit Topic
  const handleEdit = (topic) => {
    setIsEditing(true);
    setEditedTopic({ ...topic }); // Clone topic for editing
  };

  // Handle Input Change for Topic Fields
  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setEditedTopic((prev) => ({
      ...prev,
      [name]: value,
    }));
  };

  // Handle Input Change for Research Plan Fields
  const handleKeHoachChange = (e) => {
    const { name, value } = e.target;
    setEditedTopic((prev) => ({
      ...prev,
      keHoach: {
        ...prev.keHoach,
        [name]: value,
      },
    }));
  };

  // Handle Input Change for Research Products
  const handleSanPhamChange = (e, index) => {
    const { name, value } = e.target;
    const updatedSanPham = [...editedTopic.sanPham];
    updatedSanPham[index] = { ...updatedSanPham[index], [name]: value };
    setEditedTopic((prev) => ({
      ...prev,
      sanPham: updatedSanPham,
    }));
  };

  // Handle Input Change for Student Fields
  const handleStudentInputChange = (e, index) => {
    const { name, value } = e.target;
    const updatedStudents = [...editedTopic.students];
    updatedStudents[index] = { ...updatedStudents[index], [name]: value };
    setEditedTopic((prev) => ({
      ...prev,
      students: updatedStudents,
    }));
  };

  const handleUpdate = async (e) => {
    e.preventDefault();

    try {
      // Payload cho Đề Tài
      const deTaiPayload = {
        maDeTaiSV: editedTopic.id,
        tenDeTai: editedTopic.name,
        moTa: editedTopic.description,
        trangThai: editedTopic.status,
        fileHopDong: editedTopic.FileHopDong,
        maHoSo: editedTopic.hoSo.MaHoSo || null,
        maNhomNCKHSV: editedTopic.groupId,
      };

      // Payload cho Kế Hoạch Nghiên Cứu
      const keHoachPayload = {
        MaDeTaiSV: editedTopic.id,
        NgayBatDau: editedTopic.keHoach.NgayBatDau,
        NgayKetThuc: editedTopic.keHoach.NgayKetThuc,
        KinhPhi: editedTopic.keHoach.KinhPhi,
        FileKeHoach: editedTopic.keHoach.FileKeHoach,
      };

      // Gửi yêu cầu cập nhật đề tài
      const deTaiResponse = await axios.post(
        `${API_BASE}/DeTaiNCKHSV_Api.php?action=update`,
        deTaiPayload
      );

      if (!deTaiResponse.data.success) {
        alert(deTaiResponse.data.message || "Cập nhật đề tài thất bại!");
        return;
      }

      // Gửi yêu cầu cập nhật kế hoạch
      const keHoachResponse = await axios.post(
        `${API_BASE}/KeHoachNCKHSV_Api.php?action=update`,
        keHoachPayload
      );

      if (!keHoachResponse.data.success) {
        alert(keHoachResponse.data.message || "Cập nhật kế hoạch thất bại!");
        return;
      }

      // Cập nhật danh sách topics trên giao diện
      setTopics((prevTopics) =>
        prevTopics.map((topic) =>
          topic.id === editedTopic.id
            ? { ...topic, ...editedTopic, keHoach: editedTopic.keHoach }
            : topic
        )
      );

      alert("Cập nhật thành công!");
      setIsEditing(false);
      setSelectedTopic(null);
    } catch (error) {
      console.error("Error updating topic or research plan:", error);
      alert("Lỗi kết nối đến server!");
    }
  };

  // Delete Topic
  const handleDelete = async (id) => {
    if (!window.confirm("Bạn có chắc chắn muốn xóa đề tài này?")) return;

    try {
      // Send delete request to backend
      await axios.post(`${API_BASE}/DeTaiNCKHSV_Api.php?action=delete`, {
        MaDeTaiSV: id,
      });

      // Update state by removing deleted topic
      setTopics((prev) => prev.filter((topic) => topic.id !== id));
    } catch (error) {
      console.error("Error deleting topic:", error);
      alert("Xóa đề tài thất bại. Vui lòng thử lại.");
    }
  };

  // View Topic Details
  const handleViewDetails = (topic) => {
    setSelectedTopic(topic);
  };

  // View Student Details
  const handleStudentDetail = (student) => {
    setSelectedStudent(student);
    setShowStudentDetail(true);
  };

  // View Advisor Details
  const handleAdvisorDetail = () => {
    setShowAdvisorDetail(true);
  };

  // Close All Modals
  const closeDetailView = () => {
    setSelectedTopic(null);
    setShowStudentDetail(false);
    setShowAdvisorDetail(false);
    setSelectedStudent(null);
    setIsEditing(false);
  };
  return (
    <div className="bg-white rounded-lg shadow p-6">
      {/* Loading Indicator */}
      {loading && <p className="text-center">Đang tải dữ liệu...</p>}

      {/* Error Message */}
      {error && <p className="text-center text-red-500">{error}</p>}
      {/* Topics Table */}
      {!loading && !error && (
        <table className="w-full table-auto">
          <thead>
            <tr className="border-b bg-gray-100">
              <th className="text-left py-4 px-2">Tên Đề Tài</th>
              <th className="text-left py-4 px-2">Tên Giảng Viên</th>
              <th className="text-left py-4 px-2">Group ID</th>
              <th className="text-left py-4 px-2">Trạng Thái</th>
              <th className="text-right py-4 px-2">Actions</th>
            </tr>
          </thead>
          <tbody>
            {topics.map((topic) => (
              <tr key={topic.id} className="border-b hover:bg-gray-50">
                <td className="py-4 px-2">{topic.name}</td>
                <td className="py-4 px-2">{topic.advisor.name || "N/A"}</td>
                <td className="py-4 px-2">{topic.groupId || "N/A"}</td>
                <td className="py-4 px-2">
                  <span
                    className={`px-2 py-1 rounded-full text-sm ${
                      topic.status === "Đã duyệt"
                        ? "bg-green-100 text-green-800"
                        : topic.status === "Hủy"
                        ? "bg-red-100 text-red-800"
                        : "bg-blue-100 text-blue-800"
                    }`}
                  >
                    {topic.status}
                  </span>
                </td>
                <td className="py-4 px-2 text-right">
                  <div className="flex justify-end space-x-2">
                    {topic.status === "Khoa đã duyệt" && (
                      <>
                        <button
                          onClick={() => handleApprove(topic.id)}
                          className="p-2 text-green-600 hover:bg-green-100 rounded-full"
                          title="Chấp nhận"
                        >
                          <FaCheck className="w-5 h-5" />
                        </button>
                        <button
                          onClick={() => handleReject(topic.id)}
                          className="p-2 text-red-600 hover:bg-red-100 rounded-full"
                          title="Hủy"
                        >
                          <FaTimes className="w-5 h-5" />
                        </button>
                      </>
                    )}
                    <button
                      onClick={() => handleViewDetails(topic)}
                      className="p-2 text-blue-600 hover:bg-blue-100 rounded-full"
                      title="Xem chi tiết"
                    >
                      <FaEye className="w-5 h-5" />
                    </button>
                  </div>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      )}

      {/* Detail View Modal */}
      {selectedTopic && (
        <div className="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-40">
          <div className="bg-white rounded-lg p-6 max-w-4xl w-full shadow-lg overflow-y-auto max-h-[90vh]">
            <h2 className="text-2xl font-bold mb-4 text-center">
              {selectedTopic.name}
            </h2>

            {/* Description Section */}
            <div className="mb-4">
              <h3 className="text-xl font-semibold mb-2">Mô Tả Đề Tài</h3>
              <p>{selectedTopic.description || "Không có mô tả."}</p>
            </div>

            {/* Information Grid */}
            <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
              {/* Student Details */}
              <div className="p-4 border rounded-lg shadow-sm bg-gray-50">
                <h3 className="font-semibold mb-2">Thông Tin Sinh Viên</h3>
                {selectedTopic.students.length > 0 ? (
                  <ul className="list-disc list-inside">
                    {selectedTopic.students.map((student) => (
                      <li key={student.id}>
                        <span className="font-medium">{student.name}</span> (ID:{" "}
                        {student.id}){" "}
                        <button
                          onClick={() => handleStudentDetail(student)}
                          className="text-blue-500 underline ml-2"
                        >
                          Xem Chi Tiết
                        </button>
                      </li>
                    ))}
                  </ul>
                ) : (
                  <p>Không có sinh viên nào.</p>
                )}
              </div>

              {/* Advisor Details */}
              <div className="p-4 border rounded-lg shadow-sm bg-gray-50">
                <h3 className="font-semibold mb-2">Thông Tin Giảng Viên</h3>
                {selectedTopic.advisor.name !== "N/A" ? (
                  <>
                    <p>
                      <strong>ID:</strong> {selectedTopic.advisor.id}
                    </p>
                    <p>
                      <strong>Tên:</strong> {selectedTopic.advisor.name}
                    </p>
                    <p>
                      <strong>Khoa:</strong> {selectedTopic.advisor.department}
                    </p>
                    <p>
                      <strong>Email:</strong> {selectedTopic.advisor.email}
                    </p>
                    <p>
                      <strong>Địa Chỉ:</strong> {selectedTopic.advisor.address}
                    </p>
                    <button
                      onClick={handleAdvisorDetail}
                      className="text-blue-500 underline mt-2"
                    >
                      Xem Chi Tiết
                    </button>
                  </>
                ) : (
                  <p>Không có giảng viên nào.</p>
                )}
              </div>

              {/* Research Plan Details */}
              <div className="p-4 border rounded-lg shadow-sm bg-gray-50">
                <h3 className="font-semibold mb-2">
                  Thông Tin Kế Hoạch Nghiên Cứu
                </h3>
                {selectedTopic.keHoach.NgayBatDau ? (
                  <>
                    <p>
                      <strong>Ngày Bắt Đầu:</strong>{" "}
                      {selectedTopic.keHoach.NgayBatDau}
                    </p>
                    <p>
                      <strong>Ngày Kết Thúc:</strong>{" "}
                      {selectedTopic.keHoach.NgayKetThuc}
                    </p>
                    <p>
                      <strong>Kinh Phí:</strong>{" "}
                      {selectedTopic.keHoach.KinhPhi.toLocaleString()} VND
                    </p>
                    {selectedTopic.keHoach.FileKeHoach && (
                      <p>
                        <strong>File Kế Hoạch:</strong>{" "}
                        <a
                          href={`path/to/files/${selectedTopic.keHoach.FileKeHoach}`}
                          target="_blank"
                          rel="noopener noreferrer"
                          className="text-blue-500 underline"
                        >
                          Download
                        </a>
                      </p>
                    )}
                  </>
                ) : (
                  <p>Không có kế hoạch nghiên cứu.</p>
                )}
              </div>
            </div>

            {/* Research Products Section */}
            <div className="p-4 border rounded-lg shadow-sm bg-gray-50 mb-4">
              <h3 className="font-semibold mb-2">Sản Phẩm Nghiên Cứu</h3>
              {selectedTopic.sanPham.length > 0 ? (
                <div className="overflow-x-auto">
                  <table className="min-w-full bg-white">
                    <thead>
                      <tr>
                        <th className="py-2 px-4 border-b">Tên Sản Phẩm</th>
                        <th className="py-2 px-4 border-b">Ngày Hoàn Thành</th>
                        <th className="py-2 px-4 border-b">Trạng Thái</th>
                      </tr>
                    </thead>
                    <tbody>
                      {selectedTopic.sanPham.map((sp, index) => (
                        <tr key={index} className="text-center">
                          <td className="py-2 px-4 border-b">
                            {sp.TenSanPham}
                          </td>
                          <td className="py-2 px-4 border-b">
                            {sp.NgayHoanThanh}
                          </td>
                          <td className="py-2 px-4 border-b">{sp.KetQua}</td>
                        </tr>
                      ))}
                    </tbody>
                  </table>
                </div>
              ) : (
                <p>Không có sản phẩm nghiên cứu.</p>
              )}
            </div>

            {/* Action Buttons */}
            <div className="flex justify-center space-x-4">
              <button
                onClick={() => handleEdit(selectedTopic)}
                className="h-10 px-4 bg-yellow-500 text-white rounded transition duration-300 ease-in-out hover:bg-yellow-600"
              >
                Chỉnh Sửa Đề Tài
              </button>
              <button
                onClick={() => handleDelete(selectedTopic.id)}
                className="h-10 px-4 bg-red-500 text-white rounded transition duration-300 ease-in-out hover:bg-red-600"
              >
                Xóa Đề Tài
              </button>
              <button
                onClick={closeDetailView}
                className="h-10 px-4 bg-blue-500 text-white rounded"
              >
                Đóng
              </button>
            </div>
          </div>
        </div>
      )}

      {/* Edit Topic Modal */}
      {isEditing && editedTopic && (
        <div className="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
          <div className="bg-white rounded-lg p-6 max-w-4xl w-full shadow-lg overflow-y-auto max-h-[90vh]">
            <h2 className="text-2xl font-bold mb-4 text-center">
              Chỉnh Sửa Đề Tài
            </h2>
            <form onSubmit={handleUpdate} className="space-y-6">
              {/* Topic Information */}
              <div className="border-b-2 border-gray-400 pb-4">
                <h3 className="text-xl font-semibold mb-2">Thông Tin Đề Tài</h3>
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label className="block text-sm font-medium mb-1">
                      Tên Đề Tài
                    </label>
                    <input
                      type="text"
                      name="name"
                      value={editedTopic.name}
                      onChange={handleInputChange}
                      className="w-full px-4 py-2 border rounded-lg"
                      required
                    />
                  </div>
                  <div>
                    <label className="block text-sm font-medium mb-1">
                      Mô Tả
                    </label>
                    <textarea
                      name="description"
                      value={editedTopic.description}
                      onChange={handleInputChange}
                      className="w-full px-4 py-2 border rounded-lg"
                      required
                    />
                  </div>
                  <div>
                    <label className="block text-sm font-medium mb-1">
                      File Hợp Đồng
                    </label>
                    <input
                      type="file"
                      name="FileHopDong"
                      onChange={(e) => {
                        const file = e.target.files[0];
                        if (file) {
                          const formData = new FormData();
                          formData.append("file", file);

                          // Gửi file lên API để upload
                          axios
                            .post(
                              `${API_BASE}/UploadFile.php?action=uploadHopDong`,
                              formData,
                              {
                                headers: {
                                  "Content-Type": "multipart/form-data",
                                },
                              }
                            )
                            .then((response) => {
                              const filePath = response.data.filePath;
                              setEditedTopic((prev) => ({
                                ...prev,
                                FileHopDong: filePath,
                              }));
                            })
                            .catch((error) => {
                              console.error("Error uploading file:", error);
                              alert("Upload file hợp đồng thất bại.");
                            });
                        }
                      }}
                      className="w-full px-4 py-2 border rounded-lg"
                    />
                    {editedTopic.FileHopDong && (
                      <p className="mt-2">
                        <strong>File hiện tại:</strong>{" "}
                        <a
                          href={`path/to/files/${editedTopic.FileHopDong}`}
                          target="_blank"
                          rel="noopener noreferrer"
                          className="text-blue-500 underline"
                        >
                          {editedTopic.FileHopDong}
                        </a>
                      </p>
                    )}
                  </div>
                </div>
              </div>

              {/* Research Plan Information */}
              {/* Edit Research Plan */}
              <div className="border-b-2 border-gray-400 pb-4 my-4">
                <h3 className="text-xl font-semibold mb-2">
                  Chỉnh Sửa Kế Hoạch Nghiên Cứu
                </h3>
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label className="block text-sm font-medium">
                      Ngày Bắt Đầu
                    </label>
                    <input
                      type="date"
                      name="NgayBatDau"
                      value={editedTopic.keHoach.NgayBatDau || ""}
                      onChange={(e) =>
                        setEditedTopic((prev) => ({
                          ...prev,
                          keHoach: {
                            ...prev.keHoach,
                            NgayBatDau: e.target.value,
                          },
                        }))
                      }
                      className="w-full px-4 py-2 border rounded-lg"
                      required
                    />
                  </div>
                  <div>
                    <label className="block text-sm font-medium">
                      Ngày Kết Thúc
                    </label>
                    <input
                      type="date"
                      name="NgayKetThuc"
                      value={editedTopic.keHoach.NgayKetThuc || ""}
                      onChange={(e) =>
                        setEditedTopic((prev) => ({
                          ...prev,
                          keHoach: {
                            ...prev.keHoach,
                            NgayKetThuc: e.target.value,
                          },
                        }))
                      }
                      className="w-full px-4 py-2 border rounded-lg"
                      required
                    />
                  </div>
                  <div>
                    <label className="block text-sm font-medium">
                      Kinh Phí
                    </label>
                    <input
                      type="number"
                      name="KinhPhi"
                      value={editedTopic.keHoach.KinhPhi || ""}
                      onChange={(e) =>
                        setEditedTopic((prev) => ({
                          ...prev,
                          keHoach: { ...prev.keHoach, KinhPhi: e.target.value },
                        }))
                      }
                      className="w-full px-4 py-2 border rounded-lg"
                      required
                    />
                  </div>
                  <div>
                    <label className="block text-sm font-medium">
                      File Kế Hoạch
                    </label>
                    <input
                      type="file"
                      name="FileKeHoach"
                      onChange={(e) => {
                        const file = e.target.files[0];
                        if (file) {
                          const formData = new FormData();
                          formData.append("file", file);
                          axios
                            .post(
                              `${API_BASE}/UploadFile.php?action=uploadKeHoach`,
                              formData,
                              {
                                headers: {
                                  "Content-Type": "multipart/form-data",
                                },
                              }
                            )
                            .then((response) => {
                              const filePath = response.data.filePath;
                              setEditedTopic((prev) => ({
                                ...prev,
                                keHoach: {
                                  ...prev.keHoach,
                                  FileKeHoach: filePath,
                                },
                              }));
                            })
                            .catch((error) =>
                              console.error("Error uploading file:", error)
                            );
                        }
                      }}
                      className="w-full px-4 py-2 border rounded-lg"
                    />
                    {editedTopic.keHoach.FileKeHoach && (
                      <p className="mt-2">
                        <strong>File hiện tại:</strong>{" "}
                        <a
                          href={`path/to/files/${editedTopic.keHoach.FileKeHoach}`}
                          target="_blank"
                          rel="noopener noreferrer"
                          className="text-blue-500 underline"
                        >
                          {editedTopic.keHoach.FileKeHoach}
                        </a>
                      </p>
                    )}
                  </div>
                </div>
              </div>
              <button
                type="submit"
                className="w-full px-4 py-2 bg-blue-600 text-white rounded-lg transition duration-300 ease-in-out hover:bg-blue-700"
              >
                Cập Nhật
              </button>
              {/* Research Products */}
              <div className="border-b-2 border-gray-400 pb-4 my-4">
                <h3 className="text-xl font-semibold mb-2">
                  Sản Phẩm Nghiên Cứu
                </h3>
                <div className="space-y-4">
                  {editedTopic.sanPham.map((sp, index) => (
                    <div
                      key={index}
                      className="border-2 border-gray-400 rounded-lg p-4"
                    >
                      <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                          <label className="block text-sm font-medium">
                            Tên Sản Phẩm
                          </label>
                          <input
                            type="text"
                            name="TenSanPham"
                            value={sp.TenSanPham}
                            onChange={(e) => handleSanPhamChange(e, index)}
                            className="w-full px-4 py-2 border rounded-lg"
                            required
                          />
                        </div>
                        <div>
                          <label className="block text-sm font-medium">
                            Ngày Hoàn Thành
                          </label>
                          <input
                            type="date"
                            name="NgayHoanThanh"
                            value={sp.NgayHoanThanh || ""}
                            onChange={(e) => handleSanPhamChange(e, index)}
                            className="w-full px-4 py-2 border rounded-lg"
                            required
                          />
                        </div>
                        <div>
                          <label className="block text-sm font-medium">
                            Kết Quả
                          </label>
                          <input
                            type="text"
                            name="KetQua"
                            value={sp.KetQua}
                            onChange={(e) => handleSanPhamChange(e, index)}
                            className="w-full px-4 py-2 border rounded-lg"
                            required
                          />
                        </div>
                      </div>
                    </div>
                  ))}
                  {/* Add New Product Button */}
                </div>
              </div>

              {/* Student Information */}
              {/* Student Information */}
              <div className="border-b-2 border-gray-400 pb-4 my-4">
                <h3 className="text-xl font-semibold mb-2">
                  Thông Tin Sinh Viên
                </h3>
                <div className="space-y-4">
                  {editedTopic.students.map((student, index) => (
                    <div
                      key={student.id}
                      className="border-2 border-gray-400 rounded-lg p-4"
                    >
                      <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                          <label className="block text-sm font-medium">
                            ID Sinh Viên
                          </label>
                          <input
                            type="text"
                            name="id"
                            value={student.id}
                            readOnly
                            className="w-full px-4 py-2 border rounded-lg bg-gray-100"
                          />
                        </div>
                        <div>
                          <label className="block text-sm font-medium">
                            Tên Sinh Viên
                          </label>
                          <input
                            type="text"
                            name="name"
                            value={student.name}
                            onChange={(e) => handleStudentInputChange(e, index)}
                            className="w-full px-4 py-2 border rounded-lg"
                            required
                          />
                        </div>
                        <div>
                          <label className="block text-sm font-medium">
                            Điện Thoại
                          </label>
                          <input
                            type="text"
                            name="phone"
                            value={student.phone}
                            onChange={(e) => handleStudentInputChange(e, index)}
                            className="w-full px-4 py-2 border rounded-lg"
                            required
                          />
                        </div>
                        <div>
                          <label className="block text-sm font-medium">
                            Email
                          </label>
                          <input
                            type="email"
                            name="email"
                            value={student.email}
                            onChange={(e) => handleStudentInputChange(e, index)}
                            className="w-full px-4 py-2 border rounded-lg"
                            required
                          />
                        </div>
                      </div>
                    </div>
                  ))}
                </div>
              </div>

              {/* Advisor Information */}
              {/* Advisor Information */}
              <div className="border-b-2 border-gray-400 pb-4 my-4">
                <h3 className="text-xl font-semibold mb-2">
                  Thông Tin Giảng Viên
                </h3>
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label className="block text-sm font-medium">
                      ID Giảng Viên
                    </label>
                    <input
                      type="text"
                      name="advisorId"
                      value={editedTopic.advisor.id}
                      readOnly
                      className="w-full px-4 py-2 border rounded-lg bg-gray-100"
                    />
                  </div>
                  <div>
                    <label className="block text-sm font-medium">
                      Tên Giảng Viên
                    </label>
                    <input
                      type="text"
                      name="advisorName"
                      value={editedTopic.advisor.name}
                      onChange={(e) =>
                        setEditedTopic((prev) => ({
                          ...prev,
                          advisor: { ...prev.advisor, name: e.target.value },
                        }))
                      }
                      className="w-full px-4 py-2 border rounded-lg"
                      required
                    />
                  </div>
                  <div>
                    <label className="block text-sm font-medium">Khoa</label>
                    <input
                      type="text"
                      name="department"
                      value={editedTopic.advisor.department}
                      onChange={(e) =>
                        setEditedTopic((prev) => ({
                          ...prev,
                          advisor: {
                            ...prev.advisor,
                            department: e.target.value,
                          },
                        }))
                      }
                      className="w-full px-4 py-2 border rounded-lg"
                      required
                    />
                  </div>
                  <div>
                    <label className="block text-sm font-medium">Email</label>
                    <input
                      type="email"
                      name="advisorEmail"
                      value={editedTopic.advisor.email}
                      onChange={(e) =>
                        setEditedTopic((prev) => ({
                          ...prev,
                          advisor: { ...prev.advisor, email: e.target.value },
                        }))
                      }
                      className="w-full px-4 py-2 border rounded-lg"
                      required
                    />
                  </div>
                  <div>
                    <label className="block text-sm font-medium">Địa Chỉ</label>
                    <input
                      type="text"
                      name="advisorAddress"
                      value={editedTopic.advisor.address || ""}
                      onChange={(e) =>
                        setEditedTopic((prev) => ({
                          ...prev,
                          advisor: { ...prev.advisor, address: e.target.value },
                        }))
                      }
                      className="w-full px-4 py-2 border rounded-lg"
                      required
                    />
                  </div>
                </div>
              </div>

              {/* Action Buttons */}
              <div className="flex justify-center space-x-10">
                <button
                  type="button"
                  onClick={() => setIsEditing(false)}
                  className="w-full px-4 py-2 bg-red-600 text-white rounded-lg transition duration-300 ease-in-out hover:bg-red-700"
                >
                  Hủy
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* Student Detail Modal */}
      {showStudentDetail && selectedStudent && (
        <StudentDetailModal
          student={selectedStudent}
          onClose={() => setShowStudentDetail(false)}
        />
      )}

      {/* Advisor Detail Modal */}
      {showAdvisorDetail && selectedTopic && (
        <AdvisorDetailModal
          advisor={selectedTopic.advisor}
          onClose={() => setShowAdvisorDetail(false)}
        />
      )}
    </div>
  );
};

export default TopicList;
