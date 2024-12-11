import React, { useEffect, useState } from "react";
import axios from "axios";
import { FaEye, FaEdit, FaTrash } from "react-icons/fa";
// Base API URL
const API_BASE = "http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiSV";

// Modal Components
const StudentDetailModal = ({ students, onClose }) => {
  return (
    <div className="modal">
      <button onClick={onClose}>Close</button>
      <h2>Student Details</h2>
      {students.map((student, index) => (
        <div key={index} className="student-detail">
          <p>
            <strong>Name:</strong> {student.TenSinhVien}
          </p>
          <p>
            <strong>Student ID:</strong> {student.MaSinhVien}
          </p>
          <p>
            <strong>Email:</strong> {student.EmailSV}
          </p>
          <p>
            <strong>SĐT:</strong> {student.sdtSV}
          </p>
        </div>
      ))}
    </div>
  );
};

const AdvisorDetailModal = ({ advisors, onClose }) => {
  return (
    <div className="modal">
      <button onClick={onClose}>Close</button>
      <h2>Advisor Details</h2>
      {advisors.map((advisor, index) => (
        <div key={index} className="advisor-detail">
          <p>
            <strong>Name:</strong> {advisor.name}
          </p>
          <p>
            <strong>Department:</strong> {advisor.department}
          </p>
          <p>
            <strong>Email:</strong> {advisor.email}
          </p>
        </div>
      ))}
    </div>
  );
};

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
      const topicDetailsResponse = await axios.get(
        `${API_BASE}/DeTaiNCKHSV_Api.php?action=getInfoByMaDeTaiSV&MaDeTaiSV=${maDeTaiSV}`
      );
      const topicDetails = topicDetailsResponse.data.DeTaiNCKHSV || {};
      console.log("Topic Details:", topicDetails); // Debugging line
      if (topicDetails.TenDeTai) {
        setSelectedTopic((prevTopic) => ({
          ...prevTopic, // Giữ lại các dữ liệu cũ của topic
          ...topicDetails, // Thêm thông tin chi tiết mới vào
        }));
      } else {
        console.warn("Không tìm thấy chi tiết đề tài");
      }
    } catch (error) {
      console.error("Error fetching topic details:", error);
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

                    {/* Icon sửa để sửa đề tài */}
                    <button
                      onClick={() => handleEditTopic(topic)} // Hàm sửa sẽ gọi với đối tượng topic
                      className="p-2 text-green-600 hover:bg-green-100 rounded-full"
                      title="Sửa"
                    >
                      <FaEdit className="w-5 h-5" />
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
              {selectedTopic.TenDeTai || "Tên Đề Tài"}
            </h2>
            {/* Description Section */ console.log(selectedTopic.TenDeTai)}
            <div className="mb-4">
              <h3 className="text-xl font-semibold mb-2">Mô Tả Đề Tài</h3>
              <p>{selectedTopic.MoTa || "Không có mô tả."}</p>
            </div>
            Information Grid
            <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
              {/* Student Details */}
              {/* <div className="p-4 border rounded-lg shadow-sm bg-gray-50">
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
              </div> */}

              {/* Advisor Details */}
              {/* <div className="p-4 border rounded-lg shadow-sm bg-gray-50">
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
              </div> */}

              {/* Research Plan Details */}
              <div className="p-4 border rounded-lg shadow-sm bg-gray-50">
                <h3 className="font-semibold mb-2">
                  Thông Tin Kế Hoạch Nghiên Cứu
                </h3>
                {selectedTopic ? (
                  <>
                    {selectedTopic.NgayBatDau && (
                      <p>
                        <strong>Ngày Bắt Đầu:</strong>{" "}
                        {selectedTopic.NgayBatDau}
                      </p>
                    )}
                    {selectedTopic.NgayKetThuc && (
                      <p>
                        <strong>Ngày Kết Thúc:</strong>{" "}
                        {selectedTopic.NgayKetThuc}
                      </p>
                    )}
                    {selectedTopic.KinhPhi && (
                      <p>
                        <strong>Kinh Phí:</strong>{" "}
                        {selectedTopic.KinhPhi.toLocaleString()} VND
                      </p>
                    )}
                    {selectedTopic.FileKeHoach && (
                      <p>
                        <strong>File Kế Hoạch:</strong>{" "}
                        <a
                          href={`path/to/files/${selectedTopic.FileKeHoach}`}
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
              {selectedTopic.DeTaiNCKHSV &&
              selectedTopic.DeTaiNCKHSV.length > 0 ? (
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
                      {selectedTopic.DeTaiNCKHSV.map((deTai, index) => (
                        // Kiểm tra trường TenSanPham có null không và thay bằng "Chưa có sản phẩm"
                        <tr key={index} className="text-center">
                          <td className="py-2 px-4 border-b">
                            {deTai.TenSanPham || "Chưa có sản phẩm"}
                          </td>
                          <td className="py-2 px-4 border-b">
                            {deTai.NgayHoanThanh || "Chưa hoàn thành"}
                          </td>
                          <td className="py-2 px-4 border-b">
                            {deTai.KetQua || "Chưa có kết quả"}
                          </td>
                        </tr>
                      ))}
                    </tbody>
                  </table>
                </div>
              ) : (
                <p>Chưa có sản phẩm nghiên cứu.</p>
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
