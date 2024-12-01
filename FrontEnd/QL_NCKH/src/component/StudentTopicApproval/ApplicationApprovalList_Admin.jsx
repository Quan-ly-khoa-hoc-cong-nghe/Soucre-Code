import React, { useState, useEffect } from "react";
import { FaEye, FaCheck, FaTimes } from "react-icons/fa";
import axios from "axios";

const ApplicationApprovalListAdmin = () => {
  const [applications, setApplications] = useState([]);
  const [students, setStudents] = useState([]); // Danh sách sinh viên được thêm
  const [lecturers, setLecturers] = useState([]); // Danh sách giảng viên được thêm
  const [selectedApp, setSelectedApp] = useState(null);
  const [showAddTopicModal, setShowAddTopicModal] = useState(false);
  const [departments, setDepartments] = useState([]);
  const [files, setFiles] = useState({
    FileHopDong: null,
    FileKeHoach: null,
  });
  const [tenDeTai, setTenDeTai] = useState("");
  const [moTa, setMoTa] = useState("");
  const [ngayBatDau, setNgayBatDau] = useState("");
  const [ngayKetThuc, setNgayKetThuc] = useState("");
  const [kinhPhi, setKinhPhi] = useState("");

  useEffect(() => {
    fetchApplications();
    fetchDepartments();
  }, []);

  const handleFileUpload = (event, fieldName) => {
    const file = event.target.files[0];
    const allowedTypes = [
      "application/pdf",
      "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
    ]; // Allowed file types
  
    if (!allowedTypes.includes(file.type)) {
      alert("Chỉ chấp nhận các tệp PDF hoặc DOCX.");
      // Clear the file input field
      event.target.value = ""; 
      return;
    }
  
    // If file type is valid, update the state
    setFiles((prevFiles) => ({
      ...prevFiles,
      [fieldName]: file,
    }));
  };  

  const fetchApplications = () => {
    axios
      .get(
        "http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiSV/HoSoNCKHSV_Api.php?action=get"
      )
      .then((response) => {
        setApplications(response.data.HoSoNCKHSV || []);
      })
      .catch((error) => {
        console.error("Error fetching applications:", error);
      });
  };

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



  const approveApplication = (app) => {
    const requestData = {
      MaHoSo: app.MaHoSo, // Mã hồ sơ
      TrangThai: "Đã duyệt", // Trạng thái mới
    };

    // Log thông tin gửi đi để kiểm tra
    console.log("Gửi yêu cầu cập nhật trạng thái:", requestData);

    axios
      .put(
        "http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiSV/HoSoNCKHSV_Api.php?action=updateTrangThai",
        requestData
      )
      .then((response) => {
        console.log("Phản hồi từ server:", response.data);

        if (response.data.message === "Cập nhật trạng thái hồ sơ thành công") {
          alert("Cập nhật trạng thái thành công!"); // Hiển thị thông báo thành công
        } else {
          alert("Có lỗi xảy ra: " + response.data.message); // Thông báo lỗi nếu có
        }

        fetchApplications(); // Lấy lại danh sách ứng dụng sau khi cập nhật
      })
      .catch((error) => {
        console.error("Error approving application:", error);
        alert("Đã xảy ra lỗi khi gửi yêu cầu: " + error.message); // Thông báo lỗi khi gặp sự cố
      });
  };
  const approveApplicationCancel = (app) => {
    const requestData = {
      MaHoSo: app.MaHoSo, // Mã hồ sơ
      TrangThai: "Hủy", // Trạng thái mới
    };

    // Log thông tin gửi đi để kiểm tra
    console.log("Gửi yêu cầu cập nhật trạng thái:", requestData);

    axios
      .put(
        "http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiSV/HoSoNCKHSV_Api.php?action=updateTrangThai",
        requestData
      )
      .then((response) => {
        console.log("Phản hồi từ server:", response.data);

        if (response.data.message === "Cập nhật trạng thái hồ sơ thành công") {
          alert("Cập nhật trạng thái thành công!"); // Hiển thị thông báo thành công
        } else {
          alert("Có lỗi xảy ra: " + response.data.message); // Thông báo lỗi nếu có
        }

        fetchApplications(); // Lấy lại danh sách ứng dụng sau khi cập nhật
      })
      .catch((error) => {
        console.error("Error approving application:", error);
        alert("Đã xảy ra lỗi khi gửi yêu cầu: " + error.message); // Thông báo lỗi khi gặp sự cố
      });
  };

  const handleAddTopicClick = (app) => {
    setSelectedApp(app); // Lưu hồ sơ được chọn
    setShowAddTopicModal(true);
  };
  const closeAddTopicModal = () => {
    setShowAddTopicModal(false);
    setStudentInfo({});
    setLecturerInfo({});
    resetForm();
  };
  const resetForm = () => {
    setTenDeTai("");
    setMoTa("");
    setNgayBatDau("");
    setNgayKetThuc("");
    setKinhPhi("");
    setFiles({ FileHopDong: null, FileKeHoach: null });
    setStudents([]);
    setLecturers([]);
  };

  const handleSubmit = (e) => {
    e.preventDefault();

    // Kiểm tra ngày
    if (new Date(ngayKetThuc) < new Date(ngayBatDau)) {
      alert("Ngày kết thúc không thể trước ngày bắt đầu!");
      return;
    }

    setLoading(true);

    // Kiểm tra thông tin bắt buộc
    if (!selectedApp?.MaHoSo) {
      alert("Không tìm thấy MaHoSo! Vui lòng chọn hồ sơ.");
      setLoading(false);
      return;
    }

    const formData = new FormData();
    formData.append("TenDeTai", tenDeTai);
    formData.append("MoTa", moTa);
    formData.append("NgayBatDau", ngayBatDau);
    formData.append("NgayKetThuc", ngayKetThuc);
    formData.append("KinhPhi", kinhPhi);
    formData.append("FileHopDong", files.FileHopDong);
    formData.append("FileKeHoach", files.FileKeHoach);
    formData.append("MaHoSo", selectedApp?.MaHoSo || "");

    // Thêm danh sách giảng viên
    lecturers.forEach((lecturer) => {
      formData.append("GiangViens[]", lecturer.MaGV);
    });

    // Thêm danh sách sinh viên
    students.forEach((student) => {
      formData.append("SinhViens[]", student.MaSinhVien);
    });

    console.log("Đang gửi dữ liệu:", {
      tenDeTai,
      moTa,
      ngayBatDau,
      ngayKetThuc,
      kinhPhi,
      files,
      lecturers,
      students,
    });

    axios
      .post(
        "http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiSV/DeTaiNCKHSV_Api.php?action=add",
        formData,
        {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        }
      )
      .then((response) => {
        setLoading(false);
        if (response.data.success) {
          alert("Đề tài được thêm thành công!");
          setShowAddTopicModal(false);
          fetchApplications(); // Tải lại danh sách
        } else {
          alert("Thêm đề tài thất bại: " + response.data.message);
        }
      })
      .catch((error) => {
        setLoading(false);
        alert("Lỗi xảy ra khi thêm đề tài: " + error.message);
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
            <th className="px-4 py-2 border">Department</th> 
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
                <td className="px-4 py-2 border">
                {
                  // Tìm tên khoa từ danh sách departments
                  departments.find((dept) => dept.MaKhoa === app.MaKhoa)?.TenKhoa || "Chưa có"
                }
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
                          onClick={() => handleAddTopicClick(app)}
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
      {/* Modal Thêm Đề Tài */}
      {showAddTopicModal && (
        <div className="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
          <div className="bg-white rounded-lg p-6 max-w-4xl w-full shadow-lg overflow-y-auto max-h-[90vh]">
            <h2 className="text-2xl font-bold mb-4 text-center">Thêm Đề Tài</h2>
            <form className="space-y-6">
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
                      className="w-full px-4 py-2 border rounded-lg"
                      required
                      value={tenDeTai}
                    />
                  </div>
                  <div>
                    <label classNaame="block text-sm font-medium mb-1">
                      Mô Tả
                    </label>
                    <textarea
                      name="description"
                      className="w-full px-4 py-2 border rounded-lg"
                      required
                      value={moTa}
                    />
                  </div>
                  <div>
                    <label className="block text-sm font-medium mb-1">
                      File Hợp Đồng
                    </label>
                    <input
                      type="file"
                      name="FileHopDong"
                      className="w-full px-4 py-2 border rounded-lg"
                      required
                    />
                  </div>
                </div>
              </div>

              {/* Research Plan Information */}
              <div className="border-b-2 border-gray-400 pb-4 my-4">
                <h3 className="text-xl font-semibold mb-2">
                  Kế Hoạch Nghiên Cứu
                </h3>
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label className="block text-sm font-medium">
                      Ngày Bắt Đầu
                    </label>
                    <input
                      type="date"
                      name="NgayBatDau"
                      className="w-full px-4 py-2 border rounded-lg"
                      required
                      value={ngayBatDau}
                    />
                  </div>
                  <div>
                    <label className="block text-sm font-medium">
                      Ngày Kết Thúc
                    </label>
                    <input
                      type="date"
                      name="NgayKetThuc"
                      className="w-full px-4 py-2 border rounded-lg"
                      required
                      value={ngayKetThuc}
                      onChange={(e) => setNgayKetThuc(e.target.value)}
                    />
                  </div>
                  <div>
                    <label className="block text-sm font-medium">
                      Kinh Phí
                    </label>
                    <input
                      type="number"
                      name="KinhPhi"
                      className="w-full px-4 py-2 border rounded-lg"
                      required
                      value={kinhPhi}
                    />
                  </div>
                  <div>
                    <label className="block text-sm font-medium">
                      File Kế Hoạch
                    </label>
                    <input
                      type="file"
                      name="FileKeHoach"
                      className="w-full px-4 py-2 border rounded-lg"
                      onChange={(e) => handleFileUpload(e, "FileKeHoach")}
                      required
                    />
                  </div>
                </div>
              </div>

              {/* Thông Tin Sinh Viên */}
              <div className="border-b-2 border-gray-400 pb-4 my-4">
                <h3 className="text-xl font-semibold mb-2">
                  Thông Tin Sinh Viên
                </h3>
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label className="block text-sm font-medium">
                      Mã Sinh Viên
                    </label>
                    <input
                      type="text"
                      className="w-full px-4 py-2 border rounded-lg"

                    />
                  </div>
                </div>

                {/* Danh sách sinh viên */}
                <div className="mt-4">
                  <h4 className="text-lg font-semibold mb-2">
                    Danh Sách Sinh Viên
                  </h4>
                  {students.length > 0 ? (
                    <ul className="list-disc pl-5">
                      {students.map((student, index) => (
                        <li
                          key={index}
                          className="flex justify-between items-center"
                        >
                          <span>
                            {student.TenSinhVien} - {student.EmailSV} -{" "}
                            {student.sdtSV}
                          </span>
                          <button
                            className="text-red-600 hover:underline ml-2"
                            onClick={() =>
                              setStudents((prevStudents) =>
                                prevStudents.filter((_, i) => i !== index)
                              )
                            }
                          >
                            Xóa
                          </button>
                        </li>
                      ))}
                    </ul>
                  ) : (
                    <p className="text-gray-500">
                      Chưa có sinh viên nào được thêm.
                    </p>
                  )}
                </div>
              </div>

              {/* Thông Tin Giảng Viên */}
              <div className="border-b-2 border-gray-400 pb-4 my-4">
                <h3 className="text-xl font-semibold mb-2">
                  Thông Tin Giảng Viên
                </h3>
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label className="block text-sm font-medium">
                      Mã Giảng Viên
                    </label>
                    <input
                      type="text"
                      className="w-full px-4 py-2 border rounded-lg"
                     
                    />
                  </div>
                </div>

                {/* Danh sách giảng viên */}
                <div className="mt-4">
                  <h4 className="text-lg font-semibold mb-2">
                    Danh Sách Giảng Viên
                  </h4>
                  {lecturers.length > 0 ? (
                    <ul className="list-disc pl-5">
                      {lecturers.map((lecturer, index) => (
                        <li
                          key={index}
                          className="flex justify-between items-center"
                        >
                          <span>
                            {lecturer.HoTenGV} - {lecturer.EmailGV}
                          </span>
                          <button
                            className="text-red-600 hover:underline ml-2"
                          >
                            Xóa
                          </button>
                        </li>
                      ))}
                    </ul>
                  ) : (
                    <p className="text-gray-500">
                      Chưa có giảng viên nào được thêm.
                    </p>
                  )}
                </div>
              </div>

              {/* Submit Button */}
              <div className="mt-6 flex justify-center">
                <button
                  type="submit"
                  className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                >
                  Lưu Đề Tài
                </button>
                <button
                  onClick={closeAddTopicModal}
                  className="ml-2 px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600"
                >
                  Đóng
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
};

export default ApplicationApprovalListAdmin;

