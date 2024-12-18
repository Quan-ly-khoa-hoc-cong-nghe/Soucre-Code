import React, { useState, useEffect } from "react";
import axios from "axios";

const ProductLecturer = () => {
  const [products, setProducts] = useState([]);
  const [topics, setTopics] = useState([]);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [editFormData, setEditFormData] = useState(null);
  const [isEditModalOpen, setIsEditModalOpen] = useState(false);
  const [filterOption, setFilterOption] = useState("all"); // "all", "hasProduct", "noProduct"
  const [searchQuery, setSearchQuery] = useState("");

  const [formData, setFormData] = useState({
    MaDeTaiNCKHGV: "",
    TenSanPham: "",
    NgayHoanThanh: "",
    KetQua: "",
    FileSanPham: "",
  });

  // Fetch data
  useEffect(() => {
    // Lấy danh sách sản phẩm
    axios
      .get(
        "http://localhost/Soucre-Code/BackEnd/Api/DeTaiNCKHGiangVien_Api/SanPhamNCKHGV_Api.php?action=get"
      )
      .then((response) => {
        setProducts(response.data || []);
      })
      .catch((error) => {
        console.error("Error fetching products:", error);
      });

    // Lấy danh sách đề tài
    axios
      .get(
        "http://localhost/Soucre-Code/BackEnd/Api/DeTaiNCKHGiangVien_Api/DeTaiNCKHGiangVien_Api.php?action=get"
      )
      .then((response) => {
        setTopics(response.data || []);
      })
      .catch((error) => {
        console.error("Error fetching topics:", error);
      });
  }, []);

  // Modal thêm sản phẩm
  const handleCancel = () => {
    setFormData({
      MaDeTaiNCKHGV: "",
      TenSanPham: "",
      NgayHoanThanh: "",
      KetQua: "",
      FileSanPham: "",
    });
    setIsModalOpen(false);
  };

  const handleAddProduct = (maDeTaiNCKHGV) => {
    // Kiểm tra nếu đề tài đã có sản phẩm
    const hasProduct = products.some(
      (product) => product.MaDeTaiNCKHGV === maDeTaiNCKHGV
    );

    if (hasProduct) {
      alert("Đề tài này đã có sản phẩm. Bạn không thể thêm sản phẩm mới!");
      return; // Dừng lại, không mở modal
    }

    // Nếu chưa có sản phẩm, mở modal thêm sản phẩm
    setFormData({
      TenSanPham: "",
      NgayHoanThanh: "",
      KetQua: "Đang làm", // Giá trị mặc định
      MaDeTaiNCKHGV: maDeTaiNCKHGV,
    });
    setIsModalOpen(true); // Mở modal
  };

  const handleDeleteProduct = (maDeTaiNCKHGV) => {
    if (window.confirm("Bạn có chắc chắn muốn xóa sản phẩm này?")) {
      // Gửi yêu cầu DELETE tới API
      axios
        .post(
          "http://localhost/Soucre-Code/BackEnd/Api/DeTaiNCKHGiangVien_Api/SanPhamNCKHGV_Api.php?action=delete",
          { MaDeTaiNCKHGV: maDeTaiNCKHGV }, // Gửi mã đề tài trong body
          {
            headers: {
              "Content-Type": "application/json",
            },
          }
        )
        .then((response) => {
          alert("Sản phẩm đã được xóa thành công!");
          // Cập nhật danh sách sản phẩm sau khi xóa
          setProducts((prevProducts) =>
            prevProducts.filter(
              (product) => product.MaDeTaiNCKHGV !== maDeTaiNCKHGV
            )
          );
        })
        .catch((error) => {
          console.error("Lỗi khi xóa sản phẩm:", error);
          alert("Đã xảy ra lỗi khi xóa sản phẩm.");
        });
    }
  };

  const handleEditProduct = (product) => {
    setEditFormData(product);
    setIsEditModalOpen(true);
  };

  const handleSubmit = (e) => {
    e.preventDefault(); // Ngăn chặn hành vi mặc định của form

    // Gửi dữ liệu tới API
    axios
      .post(
        "http://localhost/Soucre-Code/BackEnd/Api/DeTaiNCKHGiangVien_Api/SanPhamNCKHGV_Api.php?action=post",
        formData, // Dữ liệu sản phẩm trong state
        {
          headers: {
            "Content-Type": "application/json", // Định dạng JSON
          },
        }
      )
      .then((response) => {
        // Xử lý phản hồi thành công
        alert("Thêm sản phẩm thành công!");
        console.log(response.data); // In ra phản hồi từ API
        setIsModalOpen(false); // Đóng modal
      })
      .catch((error) => {
        // Xử lý lỗi
        console.error("Lỗi khi thêm sản phẩm:", error);
        alert("Đã xảy ra lỗi khi thêm sản phẩm!");
      });
  };

  const filteredTopics = topics.filter((topic) => {
    const hasProduct = products.some(
      (product) => product.MaDeTaiNCKHGV === topic.MaDeTaiNCKHGV
    );

    const matchesSearch = topic.TenDeTai.toLowerCase().includes(
      searchQuery.toLowerCase()
    );

    if (filterOption === "hasProduct") {
      return hasProduct && matchesSearch;
    }
    if (filterOption === "noProduct") {
      return !hasProduct && matchesSearch;
    }
    return matchesSearch;
  });
  const [isEvaluateModalOpen, setIsEvaluateModalOpen] = useState(false);
  const [evaluateFormData, setEvaluateFormData] = useState({
    MaSanPhamNCKHGV: "",
    DanhGia: "",
  });

  const handleEvaluate = (product) => {
    setEvaluateFormData({
      MaSanPhamNCKHGV: product.MaSanPhamNCKHGV, // Lưu mã sản phẩm
      DanhGia: "", // Đặt mặc định đánh giá rỗng
    });
    setIsEvaluateModalOpen(true); // Mở modal
  };

  const handleSubmitEvaluation = (e) => {
    e.preventDefault(); // Ngăn chặn hành vi mặc định của form
  
    // Chuẩn bị dữ liệu để gửi đến API
    const payload = {
      MaSanPhamNCKHGV: evaluateFormData.MaSanPhamNCKHGV,
      KetQua: evaluateFormData.DanhGia, // Giá trị đánh giá sẽ được gửi dưới dạng 'KetQua'
    };
  
    // Gửi yêu cầu POST đến API
    axios
      .post(
        "http://localhost/Soucre-Code/BackEnd/Api/DeTaiNCKHGiangVien_Api/SanPhamNCKHGV_Api.php?action=update_ketqua",
        payload,
        {
          headers: {
            "Content-Type": "application/json", // Định dạng JSON
          },
        }
      )
      .then((response) => {
        // Xử lý phản hồi thành công
        if (response.data.message) {
          alert(response.data.message); // Thông báo từ API
        }
        setIsEvaluateModalOpen(false); // Đóng modal
      })
      .catch((error) => {
        // Xử lý lỗi
        console.error("Lỗi khi cập nhật kết quả:", error);
        alert("Đã xảy ra lỗi khi cập nhật kết quả.");
      });
  };
  

  return (
    <div className="p-6 bg-gray-100 rounded-lg shadow-lg max-w-6xl mx-auto">
      <h1 className="text-2xl font-semibold mb-6">
        Quản lý sản phẩm giảng viên
      </h1>
      <div className="mb-4 flex items-center space-x-4">
        <input
          type="text"
          value={searchQuery}
          onChange={(e) => setSearchQuery(e.target.value)}
          className="flex-1 p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500"
          placeholder="Tìm kiếm theo chủ đề"
        />
        <select
          value={filterOption}
          onChange={(e) => setFilterOption(e.target.value)}
          className="flex-1 p-2 border border-gray-300 rounded-md shadow-sm"
        >
          <option value="all">Tất cả</option>
          <option value="hasProduct">Đã có sản phẩm</option>
          <option value="noProduct">Chưa có sản phẩm</option>
        </select>
      </div>
      {isEvaluateModalOpen && (
        <div className="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
          <div className="bg-white rounded-lg p-6 max-w-md w-full shadow-lg">
            <h2 className="text-2xl font-bold mb-4 text-center">
              Đánh giá sản phẩm
            </h2>
            <form onSubmit={handleSubmitEvaluation}>
              {/* Hiển thị mã sản phẩm */}
              <div className="mb-4">
                <label className="block text-sm font-medium mb-1">
                  Mã sản phẩm
                </label>
                <input
                  type="text"
                  value={evaluateFormData.MaSanPhamNCKHGV}
                  readOnly
                  className="w-full px-4 py-2 border rounded-lg shadow-sm bg-gray-100 cursor-not-allowed"
                />
              </div>

              {/* Nhập đánh giá */}
              <div className="mb-4">
                <label className="block text-sm font-medium mb-1">
                  Đánh giá
                </label>
                <textarea
                  value={evaluateFormData.DanhGia}
                  onChange={(e) =>
                    setEvaluateFormData({
                      ...evaluateFormData,
                      DanhGia: e.target.value,
                    })
                  }
                  className="w-full px-4 py-2 border rounded-lg shadow-sm"
                  placeholder="Nhập đánh giá của bạn"
                  required
                />
              </div>

              {/* Nút lưu và hủy */}
              <div className="flex justify-end space-x-4">
                <button
                  type="button"
                  className="bg-gray-500 text-white px-4 py-2 rounded-lg shadow-sm hover:bg-gray-600"
                  onClick={() => setIsEvaluateModalOpen(false)}
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

      {filteredTopics.map((topic) => (
        <div
          key={topic.MaDeTaiNCKHGV}
          className="mb-6 border border-gray-300 rounded-lg p-4 bg-white"
        >
          <h2 className="text-lg font-bold text-blue-600 mb-2">
            Tên đề tài: {topic.TenDeTai}
          </h2>
          <p className="text-sm text-gray-600 mb-2">
            Mô tả: {topic.MoTa || "Chưa có mô tả"}
          </p>
          <button
            className="bg-green-500 text-white px-4 py-2 rounded-lg shadow-sm hover:bg-green-600"
            onClick={() => handleAddProduct(topic.MaDeTaiNCKHGV)} // Truyền mã đề tài đang chọn
          >
            Thêm sản phẩm
          </button>

          <table className="mt-4 w-full border border-gray-300">
            <thead className="bg-gray-100">
              <tr>
                <th className="px-4 py-2 border">Tên sản phẩm</th>
                <th className="px-4 py-2 border">Ngày hoàn thành</th>
                <th className="px-4 py-2 border">Kết quả</th>
                <th className="px-4 py-2 border">File sản phẩm</th>

                <th className="px-4 py-2 border">Thao tác</th>
                
              </tr>
            </thead>
            <tbody>
              {isModalOpen && (
                <div className="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
                  <div className="bg-white rounded-lg p-6 max-w-md w-full shadow-lg">
                    <h2 className="text-2xl font-bold mb-4 text-center">
                      Thêm sản phẩm
                    </h2>
                    <form
                      onSubmit={(e) => {
                        e.preventDefault();
                        axios
                          .post(
                            "http://localhost/Soucre-Code/BackEnd/Api/DeTaiNCKHGiangVien_Api/SanPhamNCKHGV_Api.php?action=post",
                            formData
                          )
                          .then((response) => {
                            alert("Thêm sản phẩm thành công!");
                            setIsModalOpen(false);
                          })
                          .catch((error) => {
                            console.error("Lỗi khi thêm sản phẩm:", error);
                          });
                      }}
                    >
                      {/* Tên sản phẩm */}
                      <div className="mb-4">
                        <label className="block text-sm font-medium mb-1">
                          Tên sản phẩm
                        </label>
                        <input
                          type="text"
                          value={formData.TenSanPham}
                          onChange={(e) =>
                            setFormData({
                              ...formData,
                              TenSanPham: e.target.value,
                            })
                          }
                          className="w-full px-4 py-2 border rounded-lg shadow-sm"
                          placeholder="Nhập tên sản phẩm"
                          required
                        />
                      </div>

                      {/* Ngày hoàn thành */}
                      <div className="mb-4">
                        <label className="block text-sm font-medium mb-1">
                          Ngày hoàn thành
                        </label>
                        <input
                          type="date"
                          value={formData.NgayHoanThanh}
                          onChange={(e) =>
                            setFormData({
                              ...formData,
                              NgayHoanThanh: e.target.value,
                            })
                          }
                          className="w-full px-4 py-2 border rounded-lg shadow-sm"
                          required
                        />
                      </div>

                      {/* Kết quả */}
                      <div className="mb-4">
                        <label className="block text-sm font-medium mb-1">
                          Kết quả
                        </label>
                        <input
                          type="text"
                          value={formData.KetQua}
                          readOnly // Không cho phép chỉnh sửa
                          className="w-full px-4 py-2 border rounded-lg shadow-sm bg-gray-100 cursor-not-allowed"
                        />
                      </div>

                      {/* Chọn file */}
                      <div className="mb-4">
                        <label className="block text-sm font-medium mb-1">
                          Chọn file
                        </label>
                        <input
                          type="file"
                          onChange={(e) =>
                            setFormData({
                              ...formData,
                              FileSanPham: e.target.files[0].name,
                            })
                          }
                          className="w-full px-4 py-2 border rounded-lg shadow-sm"
                          accept=".pdf,.doc,.docx,.txt" // Chỉ cho phép chọn một số loại file
                        />
                      </div>

                      {/* Mã đề tài (ẩn) */}
                      <input
                        type="hidden"
                        value={formData.MaDeTaiNCKHGV} // Lấy mã đề tài tự động
                      />

                      {/* Nút lưu và hủy */}
                      <div className="flex justify-end space-x-4">
                        <button
                          type="button"
                          className="bg-gray-500 text-white px-4 py-2 rounded-lg shadow-sm hover:bg-gray-600"
                          onClick={() => setIsModalOpen(false)}
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

              {products
                .filter(
                  (product) => product.MaDeTaiNCKHGV === topic.MaDeTaiNCKHGV
                )
                .map((product) => (
                  <tr key={product.MaSanPhamNCKHGV}>
                    <td className="px-4 py-2 border">{product.TenSanPham}</td>
                    <td className="px-4 py-2 border">
                      {product.NgayHoanThanh}
                    </td>
                    <td className="px-4 py-2 border">{product.KetQua}</td>
                    <td className="px-4 py-2 border">{product.FileSanPham}</td>

                    <td className="px-4 py-2 border">
                      <div className="flex items-center justify-center space-x-4">
                        {product.KetQua === "Đang làm" ? (
                          // Nút "Đánh giá" nếu sản phẩm có trạng thái "Đang làm"
                          <button
                            className="text-orange-500 hover:text-orange-700"
                            onClick={() => handleEvaluate(product)}
                          >
                            Đánh giá
                          </button>
                        ) : (
                          // Nút "Sửa", "Xóa" nếu không phải trạng thái "Đang làm"
                          <>
                            <button
                              className="text-blue-500 hover:text-blue-700"
                              onClick={() => handleEditProduct(product)}
                            >
                              Sửa
                            </button>
                            <button
                              className="text-red-500 hover:text-red-700"
                              onClick={() =>
                                handleDeleteProduct(product.MaDeTaiNCKHGV)
                              }
                            >
                              Xóa
                            </button>
                            <button
                              className="text-green-500 hover:text-green-700"
                              onClick={() => handleViewDetails(product)}
                            >
                              Xem chi tiết
                            </button>
                          </>
                        )}
                      </div>
                    </td>
                  </tr>
                ))}
            </tbody>
          </table>
        </div>
      ))}
    </div>
  );
};

export default ProductLecturer;
