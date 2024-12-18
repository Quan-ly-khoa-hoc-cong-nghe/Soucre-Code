import React, { useState, useEffect } from "react";
import axios from "axios";

const KeHoach = () => {
  const [deTai, setDeTai] = useState([]); // Danh sách đề tài
  const [keHoach, setKeHoach] = useState([]); // Danh sách kế hoạch
  const [isModalOpen, setIsModalOpen] = useState(false); // Trạng thái mở/đóng modal
  const [selectedMaDeTai, setSelectedMaDeTai] = useState(""); // Mã đề tài được chọn
  const [formData, setFormData] = useState({
    MaKeHoachNCKHGV: 1, // Giá trị mặc định là 1
    NgayBatDau: "",
    NgayKetThuc: "",
    KinhPhi: "",
    FileKeHoach: "",
    MaDeTaiNCKHGV: "",
  });

  // Gọi API để lấy dữ liệu
  useEffect(() => {
    // Gọi API lấy danh sách đề tài
    axios
      .get(
        "http://localhost/Soucre-Code/BackEnd/Api/DeTaiNCKHGiangVien_Api/DeTaiNCKHGiangVien_Api.php?action=get"
      )
      .then((response) => {
        setDeTai(response.data || []);
      })
      .catch((error) => {
        console.error("Lỗi khi lấy danh sách đề tài:", error);
      });

    // Gọi API lấy danh sách kế hoạch
    axios
      .get(
        "http://localhost/Soucre-Code/BackEnd/Api/DeTaiNCKHGiangVien_Api/KeHoachNCKHGV_Api.php?action=get"
      )
      .then((response) => {
        setKeHoach(response.data || []);
      })
      .catch((error) => {
        console.error("Lỗi khi lấy danh sách kế hoạch:", error);
      });
  }, []);

  // Hàm mở modal
  const handleAddPlan = (maDeTaiNCKHGV) => {
    // Kiểm tra nếu đã có kế hoạch cho đề tài
    const existingPlan = keHoach.find((k) => k.MaDeTaiNCKHGV === maDeTaiNCKHGV);
    if (existingPlan) {
      alert("Đề tài này đã có kế hoạch, không thể thêm mới!");
      return;
    }

    const nextMaKeHoach =
      keHoach.length > 0
        ? Math.max(...keHoach.map((k) => k.MaKeHoachNCKHGV)) + 1
        : 1;
    setFormData({
      MaKeHoachNCKHGV: nextMaKeHoach, // Tự động tăng MaKeHoachNCKHGV
      NgayBatDau: "",
      NgayKetThuc: "",
      KinhPhi: "",
      FileKeHoach: "",
      MaDeTaiNCKHGV: maDeTaiNCKHGV, // Gán mã đề tài được chọn
    });
    setSelectedMaDeTai(maDeTaiNCKHGV);
    setIsModalOpen(true); // Mở modal
  };

  // Hàm gửi dữ liệu lên API
  const handleSubmit = (e) => {
    e.preventDefault();
    axios
      .post(
        "http://localhost/Soucre-Code/BackEnd/Api/DeTaiNCKHGiangVien_Api/KeHoachNCKHGV_Api.php?action=post",
        formData
      )
      .then((response) => {
        alert("Thêm kế hoạch thành công!");
        setKeHoach((prev) => [...prev, formData]); // Cập nhật danh sách kế hoạch
        setIsModalOpen(false); // Đóng modal
      })
      .catch((error) => {
        console.error("Lỗi khi thêm kế hoạch:", error);
        alert("Đã xảy ra lỗi khi thêm kế hoạch!");
      });
  };

  const handleDeletePlan = (maKeHoachNCKHGV) => {
    if (window.confirm("Bạn có chắc chắn muốn xóa kế hoạch này?")) {
      axios
        .post(
          "http://localhost/Soucre-Code/BackEnd/Api/DeTaiNCKHGiangVien_Api/KeHoachNCKHGV_Api.php?action=delete",
          { MaKeHoachNCKHGV: maKeHoachNCKHGV }
        )
        .then((response) => {
          alert("Xóa kế hoạch thành công!");
          setKeHoach((prev) =>
            prev.filter((k) => k.MaKeHoachNCKHGV !== maKeHoachNCKHGV)
          ); // Cập nhật danh sách kế hoạch
        })
        .catch((error) => {
          console.error("Lỗi khi xóa kế hoạch:", error);
          alert("Đã xảy ra lỗi khi xóa kế hoạch!");
        });
    }
  };
  

  return (
    <div className="p-6 bg-gray-100 rounded-lg shadow-lg max-w-6xl mx-auto">
      <h1 className="text-2xl font-bold mb-6">Kế Hoạch Đề Tài NCKH</h1>
      <div className="space-y-4">
        {/* Lặp qua danh sách đề tài */}
        {deTai.map((dt) => {
          const kh = keHoach.find((k) => k.MaDeTaiNCKHGV === dt.MaDeTaiNCKHGV);

          return (
            <div
              key={dt.MaDeTaiNCKHGV}
              className="p-4 border border-gray-300 rounded-lg shadow-sm bg-white"
            >
              <h2 className="text-lg font-bold text-blue-600">
                Tên đề tài: {dt.TenDeTai}
              </h2>
              <p className="text-sm text-gray-600">
                Mã đề tài: {dt.MaDeTaiNCKHGV}
              </p>
              <button
                onClick={() => handleAddPlan(dt.MaDeTaiNCKHGV)}
                className="bg-green-500 text-white px-4 py-2 rounded-lg shadow-sm hover:bg-green-600"
              >
                Thêm kế hoạch
              </button>
              <button
                    onClick={() => handleDeletePlan(kh.MaKeHoachNCKHGV)}
                    className="bg-red-500 text-white px-4 py-2 rounded-lg shadow-sm hover:bg-red-600 mt-2"
                  >
                    Xóa kế hoạch
                  </button>

              {kh ? (
                <div className="mt-2">
                  <p>
                    <strong>Ngày bắt đầu:</strong> {kh.NgayBatDau}
                  </p>
                  <p>
                    <strong>Ngày kết thúc:</strong> {kh.NgayKetThuc}
                  </p>
                  <p>
                    <strong>Kinh phí:</strong> {kh.KinhPhi.toLocaleString()} VNĐ
                  </p>
                  <p>
                    <strong>File kế hoạch:</strong>{" "}
                    <a
                      href={`http://localhost/Soucre-Code/Files/${kh.FileKeHoach}`}
                      target="_blank"
                      rel="noopener noreferrer"
                      className="text-blue-500 underline"
                    >
                      {kh.FileKeHoach}
                    </a>
                  </p>
                  
                </div>
              ) : (
                <p className="text-red-500 mt-2">Chưa có kế hoạch</p>
              )}
            </div>
          );
        })}
      </div>

      {isModalOpen && (
        <div className="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
          <div className="bg-white rounded-lg p-6 max-w-md w-full shadow-lg">
            <h2 className="text-2xl font-bold mb-4 text-center">Thêm kế hoạch</h2>
            <form onSubmit={handleSubmit}>
              <div className="mb-4">
                <label className="block text-sm font-medium mb-1">Ngày bắt đầu</label>
                <input
                  type="date"
                  value={formData.NgayBatDau}
                  onChange={(e) =>
                    setFormData({ ...formData, NgayBatDau: e.target.value })
                  }
                  className="w-full px-4 py-2 border rounded-lg shadow-sm"
                  required
                />
              </div>
              <div className="mb-4">
                <label className="block text-sm font-medium mb-1">Ngày kết thúc</label>
                <input
                  type="date"
                  value={formData.NgayKetThuc}
                  onChange={(e) =>
                    setFormData({ ...formData, NgayKetThuc: e.target.value })
                  }
                  className="w-full px-4 py-2 border rounded-lg shadow-sm"
                  required
                />
              </div>
              <div className="mb-4">
                <label className="block text-sm font-medium mb-1">Kinh phí</label>
                <input
                  type="number"
                  value={formData.KinhPhi}
                  onChange={(e) =>
                    setFormData({ ...formData, KinhPhi: e.target.value })
                  }
                  className="w-full px-4 py-2 border rounded-lg shadow-sm"
                  required
                />
              </div>
              <div className="mb-4">
                <label className="block text-sm font-medium mb-1">File kế hoạch</label>
                <input
                  type="file"
                  onChange={(e) =>
                    setFormData({
                      ...formData,
                      FileKeHoach: e.target.files[0]?.name || "",
                    })
                  }
                  className="w-full px-4 py-2 border rounded-lg shadow-sm"
                  accept=".pdf,.doc,.docx"
                  required
                />
              </div>
              <div className="flex justify-end space-x-4">
                <button
                  type="button"
                  onClick={() => setIsModalOpen(false)}
                  className="bg-gray-500 text-white px-4 py-2 rounded-lg shadow-sm hover:bg-gray-600"
                >
                  Hủy
                </button>
                <button
                  type="submit"
                  className="bg-blue-500 text-white px-4 py-2 rounded-lg shadow-sm hover:bg-blue-600"
                >
                  Lưu
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
};

export default KeHoach;
