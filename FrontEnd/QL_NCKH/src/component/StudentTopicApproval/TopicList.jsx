import React, { useEffect, useState } from "react";
import axios from "axios";
import { FaEye, FaEdit, FaTrash } from "react-icons/fa";
// Base API URL
const API_BASE = "http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiSV";

const TopicList = () => {
  // State Variables
  const [topics, setTopics] = useState([]);
  const [selectedTopic, setSelectedTopic] = useState(null);
  const [showStudentDetail, setShowStudentDetail] = useState(false);
  const [showAdvisorDetail, setShowAdvisorDetail] = useState(false);
  const [selectedStudent, setSelectedStudent] = useState(null);
  const [selectedAdvisor, setSelectedAdvisor] = useState(null);
  const [isEditing, setIsEditing] = useState(false);
  const [editedTopic, setEditedTopic] = useState({});
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  // Fetch Data for Topics (changed to use getDetailedInfo)
  const fetchTopics = async () => {
    setLoading(true);
    setError(null);
    try {
      const deTaiResponse = await axios.get(
        `${API_BASE}/DeTaiNCKHSV_Api.php?action=getDetailedInfo`
      );
      const deTaiData = deTaiResponse.data.DeTaiNCKHSV || [];
      setTopics(deTaiData);
    } catch (error) {
      console.error("Error fetching topics:", error);
      setError("Đã xảy ra lỗi khi tải dữ liệu. Vui lòng thử lại sau.");
    } finally {
      setLoading(false);
    }
  };

  // Handle Edit Topic
  const handleEdit = (topic) => {
    setIsEditing(true);
    setEditedTopic({ ...topic });
  };

  // Delete Topic
  const handleDelete = async (id) => {
    if (!window.confirm("Bạn có chắc chắn muốn xóa đề tài này?")) return;

    try {
      await axios.post(`${API_BASE}/DeTaiNCKHSV_Api.php?action=delete`, {
        MaDeTaiSV: id,
      });
      setTopics((prev) => prev.filter((topic) => topic.id !== id));
    } catch (error) {
      console.error("Error deleting topic:", error);
      alert("Xóa đề tài thất bại. Vui lòng thử lại.");
    }
  };

  const handleViewDetails = (topic) => {
    setSelectedTopic(topic); // Cập nhật topic đã chọn
    fetchTopicDetails(topic.MaDeTaiSV); // Sau đó fetch thông tin chi tiết
  };

  const fetchTopicDetails = async (maDeTaiSV) => {
    try {
      // Fetch thông tin đề tài
      const topicDetailsResponse = await axios.get(
        `${API_BASE}/DeTaiNCKHSV_Api.php?action=getInfoByMaDeTaiSV&MaDeTaiSV=${maDeTaiSV}`
      );
      const topicDetails = topicDetailsResponse.data.DeTaiNCKHSV || [];
      console.log("Thông tin đề tài:", topicDetails); // Debugging line
      if (topicDetails.length > 0) {
        setSelectedTopic(topicDetails); // Cập nhật thông tin đề tài
      } else {
        alert("Không tìm thấy chi tiết đề tài");
        return;
      }

      // Fetch thông tin sinh viên
      const studentResponse = await axios.get(
        `${API_BASE}/SinhVienNCKHSV_Api.php?action=readByDeTai&MaDeTaiSV=${maDeTaiSV}`
      );
      const studentData = studentResponse.data.SinhVienNCKHSV || [];
      console.log("Dữ liệu sinh viên:", studentData); // Debugging line
      if (studentData.length > 0) {
        setSelectedStudent(studentData); // Lưu thông tin sinh viên vào state (mảng)
      } else {
        setSelectedStudent([]); // Set mảng trống nếu không có sinh viên
      }

      // Fetch thông tin giảng viên
      const advisorResponse = await axios.get(
        `${API_BASE}/GiangVienNCKHSV_Api.php?action=readByDeTai&MaDeTaiSV=${maDeTaiSV}`
      );
      const advisorData = advisorResponse.data.GiangVienNCKHSV || [];
      console.log("Dữ liệu giảng viên:", advisorData); // Debugging line
      if (advisorData.length > 0) {
        setSelectedAdvisor(advisorData); // Lưu thông tin giảng viên vào state (mảng)
      } else {
        setSelectedAdvisor([]); // Set mảng trống nếu không có giảng viên
      }
    } catch (error) {
      console.error("Lỗi khi gọi API chi tiết đề tài:", error);
      alert("Đã xảy ra lỗi khi tải chi tiết đề tài.");
    }
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

  useEffect(() => {
    fetchTopics(); // Fetch topics when the component mounts
  }, []);

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
              <th className="text-left py-4 px-2">Tên Chủ Nhiệm Đề Tài</th>
              <th className="text-left py-4 px-2">Trạng Thái</th>
              <th className="text-right py-4 px-2">Thao tác</th>
            </tr>
          </thead>
          <tbody>
            {topics.map((topic) => (
              <tr key={topic.id} className="border-b hover:bg-gray-50">
                <td className="py-4 px-2">{topic.TenDeTai}</td>
                <td className="py-4 px-2">{topic.HoTenGV || "N/A"}</td>
                <td className="py-4 px-2">
                  <span
                    className={`px-2 py-1 rounded-full text-sm ${
                      topic.TrangThai === "Hoàn thành"
                        ? "bg-green-100 text-green-800"
                        : topic.TrangThai === "Hủy"
                        ? "bg-red-100 text-red-800"
                        : "bg-blue-100 text-blue-800"
                    }`}
                  >
                    {topic.TrangThai}
                  </span>
                </td>
                <td className="py-4 px-2 text-right">
                  <div className="flex justify-end space-x-2">
                    {/* Icon mắt để xem chi tiết */}
                    <button
                      onClick={() => handleViewDetails(topic)}
                      className="p-2 text-blue-600 hover:bg-blue-100 rounded-full"
                      title="Xem chi tiết"
                    >
                      <FaEye className="w-5 h-5" />
                    </button>

                    {/* Icon xóa để xóa đề tài */}
                    <button
                      onClick={() => handleDeleteTopic(topic.MaDeTaiNCKHGV)} // Hàm xóa sẽ gọi với mã đề tài
                      className="p-2 text-red-600 hover:bg-red-100 rounded-full"
                      title="Xóa"
                    >
                      <FaTrash className="w-5 h-5" />
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
              {selectedTopic && selectedTopic[0]
                ? selectedTopic[0].TenDeTai
                : "Tên Đề Tài"}
            </h2>
            {/* Description Section */}
            <div className="mb-4">
              <h3 className="text-xl font-semibold mb-2">Mô Tả Đề Tài</h3>
              <p>
                {selectedTopic && selectedTopic[0]
                  ? selectedTopic[0].MoTa
                  : "Không có mô tả."}
              </p>
            </div>
            Information Grid
            <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
              {/* Thông Tin Sinh Viên */}
              <div className="p-4 border rounded-lg shadow-sm bg-gray-50">
                <h3 className="font-semibold mb-2">Thông Tin Sinh Viên</h3>
                {selectedStudent && selectedStudent.length > 0 ? (
                  <ul className="list-none p-0">
                    {selectedStudent.map((student) => (
                      <li
                        key={student.MaGiangVien}
                        className="flex justify-between items-center p-4 mb-4 border rounded-lg bg-white shadow-sm"
                      >
                        <div>
                          <strong>Mã GV:</strong> {student.MaSinhVien}
                          <p>
                            <strong>Tên:</strong> {student.TenSinhVien}
                          </p>
                          <p>
                            <strong>Email:</strong> {student.EmailSV}
                          </p>
                          <p>
                            <strong>SĐT:</strong> {student.sdtSV}
                          </p>
                        </div>
                      </li>
                    ))}
                  </ul>
                ) : (
                  <p>Không có sinh viên nào.</p>
                )}
              </div>

              {/* Thông Tin Giảng Viên */}
              <div className="p-4 border rounded-lg shadow-sm bg-gray-50">
                <h3 className="font-semibold mb-2">Thông Tin Giảng Viên</h3>
                {selectedAdvisor && selectedAdvisor.length > 0 ? (
                  <ul className="list-none p-0">
                    {selectedAdvisor.map((advisor) => (
                      <li
                        key={advisor.MaGiangVien}
                        className="flex justify-between items-center p-4 mb-4 border rounded-lg bg-white shadow-sm"
                      >
                        <div>
                          <strong>Mã GV:</strong> {advisor.MaGV}
                          <p>
                            <strong>Tên:</strong> {advisor.HoTenGV}
                          </p>
                          <p>
                            <strong>Vai trò:</strong> {advisor.VaiTro}
                          </p>
                          <p>
                            <strong>Email:</strong> {advisor.EmailGV}
                          </p>
                        </div>
                      </li>
                    ))}
                  </ul>
                ) : (
                  <p>Không có giảng viên nào.</p>
                )}
              </div>

              {/* Research Plan Details */}
              <div className="p-4 border rounded-lg shadow-sm bg-gray-50">
                <h3 className="font-semibold mb-2">
                  Thông Tin Kế Hoạch Nghiên Cứu
                </h3>

                {selectedTopic[0] ? (
                  <>
                    {selectedTopic[0].NgayBatDau && (
                      <p>
                        <strong>Ngày Bắt Đầu:</strong>{" "}
                        {selectedTopic[0].NgayBatDau}
                      </p>
                    )}
                    {selectedTopic[0].NgayKetThuc && (
                      <p>
                        <strong>Ngày Kết Thúc:</strong>{" "}
                        {selectedTopic[0].NgayKetThuc}
                      </p>
                    )}
                    {selectedTopic[0].KinhPhi && (
                      <p>
                        <strong>Kinh Phí:</strong>{" "}
                        {selectedTopic[0].KinhPhi.toLocaleString()} VND
                      </p>
                    )}
                    {selectedTopic[0].FileKeHoach && (
                      <p>
                        <strong>File Kế Hoạch:</strong>{" "}
                        <a
                          href={`path/to/files/${selectedTopic[0].FileKeHoach}`}
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

              {selectedTopic[0]?.TenSanPham ? (
                <>
                  <p>
                    <strong>Tên Sản Phẩm:</strong> {selectedTopic[0].TenSanPham}
                  </p>
                  {selectedTopic[0].NgayHoanThanh && (
                    <p>
                      <strong>Ngày Hoàn Thành:</strong>{" "}
                      {selectedTopic[0].NgayHoanThanh}
                    </p>
                  )}
                  {selectedTopic[0].KetQua && (
                    <p>
                      <strong>Kết Quả:</strong> {selectedTopic[0].KetQua}
                    </p>
                  )}
                  {selectedTopic[0].FileSanPham && (
                    <p>
                      <strong>File Sản Phẩm:</strong>{" "}
                      <a
                        href={`path/to/files/${selectedTopic[0].FileSanPham}`}
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
                <p>Chưa có sản phẩm nghiên cứu.</p>
              )}
            </div>
          </div>
        </div>
      )}

      {/* Student Detail Modal */}
      {showStudentDetail && selectedStudent && selectedStudent.length > 0 && (
        <StudentDetailModal
          students={selectedStudent} // Truyền vào mảng các sinh viên
          onClose={() => setShowStudentDetail(false)}
        />
      )}

      {/* Advisor Detail Modal */}
      {showAdvisorDetail &&
        selectedTopic &&
        selectedTopic.advisor &&
        selectedTopic.advisor.length > 0 && (
          <AdvisorDetailModal
            advisors={selectedTopic.advisor} // Truyền vào mảng các giảng viên
            onClose={() => setShowAdvisorDetail(false)}
          />
        )}
    </div>
  );
};

export default TopicList;
