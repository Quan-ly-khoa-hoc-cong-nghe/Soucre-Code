import React, { useState, useEffect } from "react";
import { FaEye, FaTrash, FaEdit,FaPlus  } from "react-icons/fa"; // Import biểu tượng từ react-icons

const StudentManager = () => {
  const [groups, setGroups] = useState([]);
  const [projects, setProjects] = useState([]);
  const [students, setStudents] = useState([]);
  const [lecturers, setLecturers] = useState([]);
  const [studentsInGroups, setStudentsInGroups] = useState([]);
  const [lecturersInGroups, setLecturersInGroups] = useState([]);

  // Fetch dữ liệu từ API
  useEffect(() => {
    fetch("http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiSV/NhomNCKHSV_Api.php?action=get")
      .then((res) => res.json())
      .then((data) => setGroups(data.NhomNCKHSV));

    fetch("http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiSV/DeTaiNCKHSV_Api.php?action=get")
      .then((res) => res.json())
      .then((data) => setProjects(data.DeTaiNCKHSV));

    fetch("http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiSV/SinhVien_Api.php?action=get")
      .then((res) => res.json())
      .then((data) => setStudents(data.SinhVien));

    fetch("http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiSV/GiangVien_Api.php?action=get")
      .then((res) => res.json())
      .then((data) => setLecturers(data.GiangVien));

    fetch("http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiSV/SinhVienNCKHSV_Api.php?action=get")
      .then((res) => res.json())
      .then((data) => setStudentsInGroups(data.SinhVienNCKHSV));

    fetch("http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiSV/GiangVienNCKHSV_Api.php?action=get")
      .then((res) => res.json())
      .then((data) => setLecturersInGroups(data.GiangVienNCKHSV));
  }, []);

  // Hàm xử lý các nút
  const handleView = (id) => {
    alert(`Viewing ${id}`);
  };

  const handleDelete = (id) => {
    alert(`Deleting ${id}`);
  };

  const handleEdit = (id) => {
    alert(`Editing ${id}`);
  };

  return (
    <div className="p-6">
      <div className="mb-6 p-4 border border-blue-500 rounded-lg shadow-md bg-white">
        <h2 className="text-xl font-semibold mb-2 text-blue-600">
          Nhóm sinh viên Nghiên cứu khoa học
        </h2>

        {groups.length === 0 ? (
          <p>Không có nhóm sinh viên nào.</p>
        ) : (
          <div>
            {groups.map((group) => {
              const relatedStudents = studentsInGroups
                .filter((student) => student.MaNhomNCKHSV === group.MaNhomNCKHSV)
                .map((student) => {
                  const studentData = students.find((s) => s.MaSinhVien === student.MaSinhVien);
                  return studentData ? studentData.TenSinhVien : null;
                });

              const relatedLecturers = lecturersInGroups
                .filter((lecturer) => lecturer.MaNhomNCKHSV === group.MaNhomNCKHSV)
                .map((lecturer) => {
                  const lecturerData = lecturers.find((l) => l.MaGV === lecturer.MaGV);
                  return lecturerData ? lecturerData.HoTenGV : null;
                });

              const relatedProject = projects.find(
                (project) => project.MaDeTaiSV === group.MaDeTaiSV
              );

              return (
                <div key={group.MaNhomNCKHSV} className="mb-4 p-4 border border-gray-300 rounded-lg shadow-sm">
                  <h3 className="text-lg font-semibold border-b border-gray-400 pb-2 mb-4">Nhóm {group.MaNhomNCKHSV}</h3>

                  <div className="flex flex-wrap justify-between mt-4 border-b border-gray-200 pb-2 mb-4">
                    <div className="flex-1 pr-4">
                      <p className="font-medium">Mã đề tài:</p>
                      <p>{group.MaDeTaiSV}</p>
                    </div>
                    <div className="flex-1 pl-4">
                      <p className="font-medium">Tên đề tài:</p>
                      {relatedProject ? (
                        <p>{relatedProject.TenDeTai}</p>
                      ) : (
                        <p className="text-sm text-gray-500">Không có thông tin đề tài.</p>
                      )}
                    </div>
                  </div>

                  <div className="mt-4">
                    <h4 className="font-medium mb-2">Sinh viên trong nhóm:</h4>
                    {relatedStudents.length === 0 ? (
                      <p className="text-sm text-gray-500">Không có sinh viên nào trong nhóm này.</p>
                    ) : (
                      <div className="flex flex-wrap gap-2 border border-gray-300 p-2 rounded-md">
                        {relatedStudents.map((studentName, index) => (
                          <div key={index} className="bg-blue-100 text-blue-600 px-3 py-1 rounded-full border border-blue-300">
                            {studentName}
                          </div>
                        ))}
                      </div>
                    )}
                  </div>

                  <div className="mt-4">
                    <h4 className="font-medium mb-2">Giảng viên hướng dẫn:</h4>
                    {relatedLecturers.length === 0 ? (
                      <p className="text-sm text-gray-500">Không có giảng viên nào hướng dẫn nhóm này.</p>
                    ) : (
                      <div className="flex flex-wrap gap-2 border border-gray-300 p-2 rounded-md">
                        {relatedLecturers.map((lecturerName, index) => (
                          <div key={index} className="bg-green-100 text-green-600 px-3 py-1 rounded-full border border-green-300">
                            {lecturerName}
                          </div>
                        ))}
                      </div>
                    )}
                  </div>

                  {/* Các nút điều khiển */}
                  <div className="flex justify-end mt-4">
                    <button 
                      className="text-blue-500 mx-2"
                      onClick={() => handleView(group.MaNhomNCKHSV)}
                    >
                      <FaEye />
                    </button>
                    <button 
                      className="text-green-500 mx-2"
                      onClick={() => handleEdit(group.MaNhomNCKHSV)}
                    >
                      <FaPlus />
                    </button>
                    <button 
                      className="text-red-500 mx-2"
                      onClick={() => handleDelete(group.MaNhomNCKHSV)}
                    >
                      <FaTrash />
                    </button>
                    
                  </div>
                </div>
              );
            })}
          </div>
        )}
      </div>
    </div>
  );
};

export default StudentManager;

