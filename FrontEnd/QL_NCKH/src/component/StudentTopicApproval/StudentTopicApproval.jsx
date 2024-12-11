import React, { useState } from "react";
import TopicList from "./TopicList"; // Giả sử bạn vẫn giữ lại component TopicList
import axios from "axios"; // Assuming you're using axios for API calls

const StudentTopicApproval = () => {
  const [showAddTopicModal, setShowAddTopicModal] = useState(false);
  const [newTopic, setNewTopic] = useState({
    name: "",
    description: "",
    FileHopDong: "",
    keHoach: {
      NgayBatDau: "",
      NgayKetThuc: "",
      KinhPhi: "",
      FileKeHoach: "",
    },
    sanPham: [],
    students: [],
    advisor: {
      id: "",
      name: "",
      department: "",
      email: "",
      address: "",
    },
  });

  // Mở modal khi người dùng click vào nút "Thêm Đề Tài"
  const handleAddTopicClick = () => {
    setShowAddTopicModal(true);
  };

  // Đóng modal khi người dùng click vào nút đóng
  const closeAddTopicModal = () => {
    setShowAddTopicModal(false);
  };

  // Cập nhật thông tin trong form khi người dùng thay đổi
  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setNewTopic((prev) => ({
      ...prev,
      [name]: value,
    }));
  };

  // Cập nhật thông tin kế hoạch nghiên cứu khi người dùng thay đổi
  const handleKeHoachChange = (e) => {
    const { name, value } = e.target;
    setNewTopic((prev) => ({
      ...prev,
      keHoach: {
        ...prev.keHoach,
        [name]: value,
      },
    }));
  };

  // Cập nhật thông tin sản phẩm nghiên cứu khi người dùng thay đổi
  const handleSanPhamChange = (e, index) => {
    const { name, value } = e.target;
    const updatedSanPham = [...newTopic.sanPham];
    updatedSanPham[index] = { ...updatedSanPham[index], [name]: value };
    setNewTopic((prev) => ({
      ...prev,
      sanPham: updatedSanPham,
    }));
  };

  // Cập nhật thông tin sinh viên khi người dùng thay đổi
  const handleStudentInputChange = (e, index) => {
    const { name, value } = e.target;
    const updatedStudents = [...newTopic.students];
    updatedStudents[index] = { ...updatedStudents[index], [name]: value };
    setNewTopic((prev) => ({
      ...prev,
      students: updatedStudents,
    }));
  };

  // Cập nhật thông tin giảng viên khi người dùng thay đổi
  const handleAdvisorInputChange = (e) => {
    const { name, value } = e.target;
    setNewTopic((prev) => ({
      ...prev,
      advisor: {
        ...prev.advisor,
        [name]: value,
      },
    }));
  };

  // Lưu đề tài mới
  const handleSave = () => {
    // Logic lưu đề tài mới, ví dụ gọi API để tạo đề tài mới
    console.log("Đề Tài Mới: ", newTopic);
    setShowAddTopicModal(false); // Đóng modal sau khi lưu
  };

  return (
    <div className="min-h-screen bg-gray-50 p-8">
      <div className="container mx-auto">
        <div className="flex items-center justify-between mb-6">
          <h1 className="text-2xl font-semibold">Quản lý đề tài sinh viên</h1>
        </div>

        {/* Nút Thêm Đề Tài
        <div className="mb-4 flex justify-end">
          <button
            onClick={handleAddTopicClick}
            className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200"
          >
            Thêm Đề Tài
          </button>
        </div> */}

        {/* Topic List */}
        <TopicList />

        {/* Modal Thêm Đề Tài */}
        {showAddTopicModal && (
          <div className="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div className="bg-white rounded-lg p-6 max-w-4xl w-full shadow-lg overflow-y-auto max-h-[90vh]">
              <h2 className="text-2xl font-bold mb-4 text-center">Thêm Đề Tài</h2>

              <form className="space-y-6" onSubmit={handleSave}>
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
                        value={newTopic.name}
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
                        value={newTopic.description}
                        onChange={handleInputChange}
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
                      <label className="block text-sm font-medium">Ngày Bắt Đầu</label>
                      <input
                        type="date"
                        name="NgayBatDau"
                        value={newTopic.keHoach.NgayBatDau}
                        onChange={handleKeHoachChange}
                        className="w-full px-4 py-2 border rounded-lg"
                        required
                      />
                    </div>
                    <div>
                      <label className="block text-sm font-medium">Ngày Kết Thúc</label>
                      <input
                        type="date"
                        name="NgayKetThuc"
                        value={newTopic.keHoach.NgayKetThuc}
                        onChange={handleKeHoachChange}
                        className="w-full px-4 py-2 border rounded-lg"
                        required
                      />
                    </div>
                    <div>
                      <label className="block text-sm font-medium">Kinh Phí</label>
                      <input
                        type="number"
                        name="KinhPhi"
                        value={newTopic.keHoach.KinhPhi}
                        onChange={handleKeHoachChange}
                        className="w-full px-4 py-2 border rounded-lg"
                        required
                      />
                    </div>
                  </div>
                </div>

                {/* Advisor Information */}
                <div className="border-b-2 border-gray-400 pb-4 my-4">
                  <h3 className="text-xl font-semibold mb-2">Thông Tin Giảng Viên</h3>
                  <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                      <label className="block text-sm font-medium">Tên Giảng Viên</label>
                      <input
                        type="text"
                        name="advisorName"
                        value={newTopic.advisor.name}
                        onChange={handleAdvisorInputChange}
                        className="w-full px-4 py-2 border rounded-lg"
                        required
                      />
                    </div>
                    <div>
                      <label className="block text-sm font-medium">Khoa</label>
                      <input
                        type="text"
                        name="department"
                        value={newTopic.advisor.department}
                        onChange={handleAdvisorInputChange}
                        className="w-full px-4 py-2 border rounded-lg"
                        required
                      />
                    </div>
                    <div>
                      <label className="block text-sm font-medium">Email</label>
                      <input
                        type="email"
                        name="advisorEmail"
                        value={newTopic.advisor.email}
                        onChange={handleAdvisorInputChange}
                        className="w-full px-4 py-2 border rounded-lg"
                        required
                      />
                    </div>
                    <div>
                      <label className="block text-sm font-medium">Địa Chỉ</label>
                      <input
                        type="text"
                        name="advisorAddress"
                        value={newTopic.advisor.address}
                        onChange={handleAdvisorInputChange}
                        className="w-full px-4 py-2 border rounded-lg"
                        required
                      />
                    </div>
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
    </div>
  );
};

export default StudentTopicApproval;
