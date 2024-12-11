import React, { useState, useEffect } from "react";
import { FaEye, FaPlus, FaTrash } from "react-icons/fa";

const ReportLecturer = () => {
  // State để lưu dữ liệu đề tài và báo cáo
  const [projects, setProjects] = useState([]);
  const [reports, setReports] = useState([]);

  // Fetch dữ liệu từ API cho đề tài và báo cáo
  useEffect(() => {
    // Lấy thông tin đề tài
    fetch("http://localhost/Soucre-Code/BackEnd/Api/DeTaiNCKHGiangVien_Api/DeTaiNCKHGiangVien_Api.php?action=get")
      .then((res) => res.json())
      .then((data) => setProjects(data));

    // Lấy thông tin báo cáo
    fetch("http://localhost/Soucre-Code/BackEnd/Api/DeTaiNCKHGiangVien_Api/BaoCaoDinhKy_Api.php?action=GET")
      .then((res) => res.json())
      .then((data) => setReports(data));
  }, []);

  return (
    <div className="p-6">
      {/* Display project list */}
      <div className="mb-6 p-4 border border-blue-500 rounded-lg shadow-md bg-white">
        <h2 className="text-xl font-semibold mb-2 text-blue-600">Quản lý báo cáo định kỳ</h2>
        
        {projects.length === 0 ? (
          <p>Không có đề tài nào.</p>
        ) : (
          <div>
            {projects.map((project) => {
              // Lọc các báo cáo liên quan đến đề tài này
              const relatedReports = reports.filter(report => report.MaDeTaiNCKHGV === project.MaDeTaiNCKHGV);

              return (
                
                <div key={project.MaDeTaiNCKHGV} className="mb-4 p-4 border border-gray-300 rounded-lg shadow-sm">
                   <div className="mt-4">
                    <button className="px-4 py-2 bg-green-500 text-white rounded-lg flex items-center">
                      <FaPlus className="mr-2" /> Thêm báo cáo
                    </button>
                  </div>
                  <h3 className="text-lg font-semibold">{project.TenDeTai}</h3>
                  <p>{project.MoTa}</p>
                  <div className="mt-2">
                    <h4 className="font-medium">Báo cáo định kỳ:</h4>
                    {relatedReports.length === 0 ? (
                      <p className="text-sm text-gray-500">Chưa có báo cáo nào cho đề tài này.</p>
                    ) : (
                      <ul>
                        {relatedReports.map((report) => (
                          <li key={report.MaBaoCaoDinhKy} className="flex justify-between items-center mt-2">
                            <span>{report.NoiDungBaoCao} - {report.NgayNop}</span>
                            <div className="flex space-x-2">
                              <a href={`/files/${report.FileBaoBao}`} target="_blank" rel="noopener noreferrer" className="text-blue-500 hover:underline">
                                <FaEye />
                              </a>
                              <FaTrash className="text-red-500 cursor-pointer" />
                            </div>
                          </li>
                        ))}
                      </ul>
                    )}
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

export default ReportLecturer;
