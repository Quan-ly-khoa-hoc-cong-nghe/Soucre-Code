import React, { useState, useEffect } from "react";
import { FaEye, FaPlus, FaTrash } from "react-icons/fa";

const ReportLecturer = () => {
  // State để lưu dữ liệu đề tài và báo cáo
  const [projects, setProjects] = useState([]);
  const [reports, setReports] = useState([]);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [formData, setFormData] = useState({
    NoiDungBaoCao: "",
    NgayNop: "",
    FileBaoCao: "",
    MaDeTaiNCKHGV: "",
  });
  const handleAddReport = (maDeTaiNCKHGV) => {
    setFormData({
      NoiDungBaoCao: "",
      NgayNop: "",
      FileBaoCao: "",
      MaDeTaiNCKHGV: maDeTaiNCKHGV,
    });
    setIsModalOpen(true);
  };
  const handleSubmit = (e) => {
    e.preventDefault();
    fetch(
      "http://localhost/Soucre-Code/BackEnd/Api/DeTaiNCKHGiangVien_Api/BaoCaoDinhKy_Api.php?action=POST",
      {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(formData),
      }
    )
      .then((res) => res.json())
      .then((data) => {
        alert(data.message || "Thêm báo cáo thành công!");
        setReports([...reports, formData]); // Cập nhật danh sách
        setIsModalOpen(false);
      })
      .catch((err) => console.error("Lỗi:", err));
  };

  // Fetch dữ liệu từ API cho đề tài và báo cáo
  useEffect(() => {
    // Lấy thông tin đề tài
    fetch(
      "http://localhost/Soucre-Code/BackEnd/Api/DeTaiNCKHGiangVien_Api/DeTaiNCKHGiangVien_Api.php?action=get"
    )
      .then((res) => res.json())
      .then((data) => setProjects(data));

    // Lấy thông tin báo cáo
    fetch(
      "http://localhost/Soucre-Code/BackEnd/Api/DeTaiNCKHGiangVien_Api/BaoCaoDinhKy_Api.php?action=GET"
    )
      .then((res) => res.json())
      .then((data) => setReports(data));
  }, []);

  return (
    <div className="p-6">
      {/* Display project list */}
      <div className="mb-6 p-4 border border-blue-500 rounded-lg shadow-md bg-white">
        <h2 className="text-xl font-semibold mb-4 text-blue-600">
          Quản lý báo cáo định kỳ
        </h2>
        {projects.length === 0 ? (
          <p>Không có đề tài nào.</p>
        ) : (
          <table className="w-full border-collapse border border-gray-300">
            <thead>
              <tr className="bg-blue-100">
                <th className="border border-gray-300 px-4 py-2 text-left">
                  Tên đề tài
                </th>
                <th className="border border-gray-300 px-4 py-2 text-left">
                  Mô tả
                </th>
                <th className="border border-gray-300 px-4 py-2 text-left">
                  Báo cáo định kỳ
                </th>
                <th className="border border-gray-300 px-4 py-2 text-center">
                  Hành động{" "}
                </th>
              </tr>
            </thead>
            {isModalOpen && (
              <div className="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                <div className="bg-white p-6 rounded-lg shadow-lg w-96">
                  <h2 className="text-xl font-semibold mb-4">Thêm báo cáo</h2>
                  <form onSubmit={handleSubmit}>
                    <label className="block mb-2">
                      Nội dung báo cáo:
                      <input
                        type="text"
                        value={formData.NoiDungBaoCao}
                        onChange={(e) =>
                          setFormData({
                            ...formData,
                            NoiDungBaoCao: e.target.value,
                          })
                        }
                        className="w-full border p-2 rounded"
                        required
                      />
                    </label>
                    <label className="block mb-2">
                      Ngày nộp:
                      <input
                        type="date"
                        value={formData.NgayNop}
                        onChange={(e) =>
                          setFormData({ ...formData, NgayNop: e.target.value })
                        }
                        className="w-full border p-2 rounded"
                        required
                      />
                    </label>
                    <label className="block mb-4">
                      File báo cáo:
                      <input
                        type="file"
                        onChange={(e) =>
                          setFormData({
                            ...formData,
                            FileBaoCao: e.target.files[0]?.name,
                          })
                        }
                        className="w-full border p-2 rounded"
                        accept=".pdf,.doc,.docx"
                        required
                      />
                    </label>
                    <div className="flex justify-end space-x-2">
                      <button
                        type="button"
                        onClick={() => setIsModalOpen(false)}
                        className="bg-gray-500 text-white px-4 py-2 rounded"
                      >
                        Hủy
                      </button>
                      <button
                        type="submit"
                        className="bg-blue-500 text-white px-4 py-2 rounded"
                      >
                        Lưu
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            )}

            <tbody>
              {projects.map((project) => {
                // Lọc các báo cáo liên quan đến đề tài này
                const relatedReports = reports.filter(
                  (report) => report.MaDeTaiNCKHGV === project.MaDeTaiNCKHGV
                );

                return (
                  <tr key={project.MaDeTaiNCKHGV} className="hover:bg-gray-50">
                    <td className="border border-gray-300 px-4 py-2 align-top">
                      {project.TenDeTai}
                    </td>
                    <td className="border border-gray-300 px-4 py-2 align-top">
                      {project.MoTa}
                    </td>
                    <td className="border border-gray-300 px-4 py-2 align-top">
                      {relatedReports.length === 0 ? (
                        <p className="text-sm text-gray-500">
                          Chưa có báo cáo nào.
                        </p>
                      ) : (
                        <table className="w-full border-collapse">
                          <thead>
                            <tr>
                              <th className="border border-gray-300 px-2 py-1 text-left text-sm">
                                Nội dung báo cáo
                              </th>
                              <th className="border border-gray-300 px-2 py-1 text-left text-sm">
                                Ngày nộp
                              </th>
                              <th className="border border-gray-300 px-2 py-1 text-left text-sm">
                                File báo cáo
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                            {relatedReports.map((report) => (
                              <tr key={report.MaBaoCaoDinhKy}>
                                <td className="border border-gray-300 px-2 py-1">
                                  {report.NoiDungBaoCao}
                                </td>
                                <td className="border border-gray-300 px-2 py-1">
                                  {report.NgayNop}
                                </td>
                                <td className="border border-gray-300 px-2 py-1">
                                  <a
                                    href={`/files/${report.FileBaoBao}`}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="text-blue-500 hover:underline"
                                  >
                                    {report.FileBaoBao}
                                  </a>
                                </td>
                              </tr>
                            ))}
                          </tbody>
                        </table>
                      )}
                    </td>
                    <td className="border border-gray-300 px-4 py-2 text-center align-top">
                      <button
                        onClick={() => handleAddReport(project.MaDeTaiNCKHGV)}
                        className="px-4 py-2 bg-green-500 text-white rounded-lg flex items-center justify-center"
                      >
                        <FaPlus className="mr-2" /> Thêm báo cáo
                      </button>
                    </td>
                  </tr>
                );
              })}
            </tbody>
          </table>
        )}
      </div>
    </div>
  );
};

export default ReportLecturer;
