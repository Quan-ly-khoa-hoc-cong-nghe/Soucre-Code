import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { FaEye, FaPlus, FaTrash } from 'react-icons/fa'; // Import các icon từ react-icons

const Nhomdtcs = () => {
  const [projects, setProjects] = useState([]); // State lưu trữ dữ liệu dự án
  const [lecturers, setLecturers] = useState([]); // State lưu trữ dữ liệu giảng viên

  // Dùng useEffect để fetch dữ liệu từ API
  useEffect(() => {
    const fetchProjectsAndLecturers = async () => {
      try {
        // Lấy dữ liệu đề tài
        const projectsResponse = await axios.get('http://localhost/Soucre-Code/BackEnd/Api/DeTaiCapCoSo_Api/DeTaiCapSo_Api.php?action=get');
        const lecturersResponse = await axios.get('http://localhost/Soucre-Code/BackEnd/Api/DeTaiCapCoSo_Api/NhomDTCS_Api.php?action=get');
        const allLecturersResponse = await axios.get('http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiSV/GiangVien_Api.php?action=get');

        // Lưu dữ liệu vào state
        const projectsData = projectsResponse.data;
        const lecturersData = lecturersResponse.data;
        const allLecturersData = allLecturersResponse.data.GiangVien;

        // Kết hợp thông tin giảng viên với tên đề tài
        const combinedData = projectsData.map(project => {
          // Tìm các giảng viên thuộc đề tài
          const relatedLecturers = lecturersData
            .filter(item => item.MaDTCS === project.MaDTCS)
            .map(item => item.MaGV);

          // Tìm tên giảng viên dựa vào MaGV
          const lecturersNames = relatedLecturers.map(maGV => {
            const lecturer = allLecturersData.find(l => l.MaGV === maGV);
            return lecturer ? lecturer.HoTenGV : 'Unknown'; // Nếu không tìm thấy giảng viên thì trả về 'Unknown'
          }).join(', ');

          // Trả về thông tin kết hợp, bao gồm ngày bắt đầu và ngày kết thúc
          return {
            ...project,
            lecturers: lecturersNames, // Giảng viên liên kết với tên
            startDate: project.NgayBatDau, // Ngày bắt đầu
            endDate: project.NgayKetThuc, // Ngày kết thúc
          };
        });

        setProjects(combinedData); // Lưu dữ liệu kết hợp vào state
        setLecturers(allLecturersData); // Lưu dữ liệu giảng viên vào state
      } catch (error) {
        console.error('Error fetching projects and lecturers:', error);
      }
    };

    fetchProjectsAndLecturers();
  }, []); // Chạy một lần khi component được mount

  return (
    <div className="min-h-screen bg-gray-50 p-8">
      <div className="container mx-auto">
        <div className="flex items-center justify-between mb-6">
          <h1 className="text-2xl font-semibold">Quản lý nhóm đề tài cấp sở</h1>
        </div>

        {/* Hiển thị danh sách đề tài */}
        <div className="overflow-x-auto bg-white p-4 rounded-lg shadow-md">
          <table className="min-w-full table-auto">
            <thead>
              <tr className="bg-gray-100">
                <th className="px-4 py-2 text-left">Mã Đề Tài</th>
                <th className="px-4 py-2 text-left">Tên Đề Tài</th>
                <th className="px-4 py-2 text-left">Thành viên</th>
                <th className="px-4 py-2 text-left">Ngày Bắt Đầu</th> {/* Thêm cột Ngày Bắt Đầu */}
                <th className="px-4 py-2 text-left">Ngày Kết Thúc</th> {/* Thêm cột Ngày Kết Thúc */}
                <th className="px-4 py-2 text-left">Thao Tác</th>
              </tr>
            </thead>
            <tbody>
              {projects.map((project) => (
                <tr key={project.MaDTCS} className="hover:bg-gray-50">
                  <td className="px-4 py-2">{project.MaDTCS}</td>
                  <td className="px-4 py-2">{project.TenDeTai}</td>
                  <td className="px-4 py-2">{project.lecturers}</td>
                  <td className="px-4 py-2">{project.startDate}</td> {/* Hiển thị Ngày Bắt Đầu */}
                  <td className="px-4 py-2">{project.endDate}</td> {/* Hiển thị Ngày Kết Thúc */}
                  <td className="px-4 py-2">
                    <button className="text-blue-500 hover:text-blue-700 mr-2">
                      <FaEye />
                    </button>
                    <button className="bg-text-white text-green-500 p-2 rounded-full">
                      <FaPlus />
                    </button>
                    <button className="text-red-500 hover:text-red-700">
                      <FaTrash />
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
};

export default Nhomdtcs;
