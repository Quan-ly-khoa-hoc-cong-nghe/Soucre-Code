import React, { useState, useEffect } from "react";
import axios from "axios";

const ProductManager = () => {
  const [products, setProducts] = useState([]);
  const [topics, setTopics] = useState([]);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [editFormData, setEditFormData] = useState(null);
  const [isEditModalOpen, setIsEditModalOpen] = useState(false);
  const [filterOption, setFilterOption] = useState("all"); // "all", "hasProduct", "noProduct"
  const [searchQuery, setSearchQuery] = useState("");


  const [formData, setFormData] = useState({
    MaDeTaiSV: "",
    TenSanPham: "",
    NgayHoanThanh: "",
    KetQua: "",
    FileSanPham: "",
  });

  const handleEditProduct = (product) => {
    setEditFormData(product);
    setIsEditModalOpen(true);
  };
  const searchFilter = (topic) => {
    // Kiểm tra tên đề tài và tên sản phẩm có chứa từ khóa tìm kiếm không
    return (
      topic.TenDeTai.toLowerCase().includes(searchQuery.toLowerCase()) ||
      products.some(
        (product) =>
          product.TenSanPham.toLowerCase().includes(searchQuery.toLowerCase()) &&
          product.MaDeTaiSV === topic.MaDeTaiSV
      )
    );
  };
  

  useEffect(() => {
    // Lấy danh sách sản phẩm
    axios
      .get(
        "http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiSV/SanPhamNCKHSV_Api.php?action=get"
      )
      .then((response) => {
        setProducts(response.data.SanPhamNCKHSV || []);
      })
      .catch((error) => {
        console.error("Error fetching products:", error);
      });

    // Lấy danh sách đề tài
    axios
      .get(
        "http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiSV/DeTaiNCKHSV_Api.php?action=get"
      )
      .then((response) => {
        setTopics(response.data.DeTaiNCKHSV || []);
      })
      .catch((error) => {
        console.error("Error fetching topics:", error);
      });
  }, []);

  const handleCancel = () => {
    setFormData({
      MaDeTaiSV: "",
      TenSanPham: "",
      NgayHoanThanh: "",
      KetQua: "",
      FileSanPham: "",
    });
    setIsModalOpen(false);
  };

  const handleAddProduct = (maDeTaiSV) => {
    // Kiểm tra nếu đề tài đã có sản phẩm
    const existingProduct = products.find(
      (product) => product.MaDeTaiSV === maDeTaiSV
    );

    if (existingProduct) {
      alert("This topic already has a product. You cannot add another.");
      return; // Dừng nếu đã có sản phẩm
    }

    // Nếu chưa có sản phẩm, mở modal thêm sản phẩm
    setFormData({
      MaDeTaiSV: maDeTaiSV,
      TenSanPham: "",
      NgayHoanThanh: "",
      KetQua: "",
      FileSanPham: "",
    });
    setIsModalOpen(true);
  };
  const handleDeleteProduct = (product) => {
    if (
      window.confirm(
        `Are you sure you want to delete product "${product.TenSanPham}"?`
      )
    ) {
      axios
        .post(
          "http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiSV/SanPhamNCKHSV_Api.php?action=delete",
          { MaDeTaiSV: product.MaDeTaiSV } // Gửi thông tin sản phẩm cần xóa
        )
        .then((response) => {
          alert(response.data.message || "Product deleted successfully!");
          // Cập nhật danh sách sản phẩm sau khi xóa
          setProducts((prevProducts) =>
            prevProducts.filter((p) => p.MaDeTaiSV !== product.MaDeTaiSV)
          );
        })
        .catch((error) => {
          console.error("Error deleting product:", error);
        });
    }
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    axios
      .post(
        "http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiSV/SanPhamNCKHSV_Api.php?action=add",
        formData
      )
      .then((response) => {
        alert(response.data.message || "Product added successfully!");
        const newProduct = { ...formData };
        setProducts([...products, newProduct]);
        handleCancel();
      })
      .catch((error) => {
        console.error("Error adding product:", error);
      });
  };

  // Lọc danh sách đề tài dựa trên lựa chọn
  const filteredTopics = topics.filter((topic) => {
    const hasProduct = products.some(
      (product) => product.MaDeTaiSV === topic.MaDeTaiSV
    );
  
    // Lọc theo bộ lọc sản phẩm và từ khóa tìm kiếm
    const matchesSearch = searchFilter(topic);
  
    if (filterOption === "hasProduct") {
      return hasProduct && matchesSearch; // Đề tài có sản phẩm và khớp tìm kiếm
    }
    if (filterOption === "noProduct") {
      return !hasProduct && matchesSearch; // Đề tài chưa có sản phẩm và khớp tìm kiếm
    }
    return matchesSearch; // Hiển thị tất cả khi không có bộ lọc cụ thể
  });
  

  return (
    <div className="p-6 bg-gray-100 rounded-lg shadow-lg max-w-6xl mx-auto">
     <div className="mb-4 flex items-center space-x-4">
  {/* Thanh tìm kiếm */}
  <div className="flex-1">
    <input
      type="text"
      value={searchQuery}
      onChange={(e) => setSearchQuery(e.target.value)}
      className="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
      placeholder="Search by Topic or Product"
    />
  </div>

  {/* Dropdown filter */}
  <div className="flex-1">
    <select
      value={filterOption}
      onChange={(e) => setFilterOption(e.target.value)}
      className="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
    >
      <option value="all">All Topics</option>
      <option value="hasProduct">Has Product</option>
      <option value="noProduct">No Product</option>
    </select>
  </div>
</div>

      {/* Hiển thị danh sách đề tài và các sản phẩm */}
      {filteredTopics.map((topic) => (
        <div
          key={topic.MaDeTaiSV}
          className="mb-6 border border-gray-300 rounded-lg p-4 shadow-sm bg-white"
        >
          <h2 className="text-lg font-bold text-blue-600 mb-2">
            Topic: {topic.TenDeTai}
          </h2>
          <p className="text-sm text-gray-600 mb-2">
            Description: {topic.MoTa || "No Description"}
          </p>

          <button
            className="bg-green-500 text-white px-4 py-2 rounded-lg shadow-sm hover:bg-green-600 transition mb-4"
            onClick={() => handleAddProduct(topic.MaDeTaiSV)}
          >
            Add Product
          </button>

          <table className="min-w-full border-collapse border border-gray-300">
            <thead>
              <tr className="bg-gray-100 text-left">
                <th className="px-4 py-2 border">Product ID</th>
                <th className="px-4 py-2 border">Product Name</th>
                <th className="px-4 py-2 border">Completion Date</th>
                <th className="px-4 py-2 border">Result</th>
                <th className="px-4 py-2 border">Actions</th>
              </tr>
            </thead>
            <tbody>
              {products
                .filter((product) => product.MaDeTaiSV === topic.MaDeTaiSV)
                .map((product) => (
                  <tr key={product.MaDeTaiSV} className="hover:bg-gray-50">
                    <td className="px-4 py-2 border">{product.MaDeTaiSV}</td>
                    <td className="px-4 py-2 border">{product.TenSanPham}</td>
                    <td className="px-4 py-2 border">
                      {product.NgayHoanThanh}
                    </td>
                    <td className="px-4 py-2 border">{product.KetQua}</td>
                    <td className="px-4 py-2 border">
                      <button
                        className="text-blue-500 hover:text-blue-700 mr-2"
                        onClick={() => handleEditProduct(product)}
                      >
                        Edit
                      </button>

                      <button
                        className="text-red-500 hover:text-red-700"
                        onClick={() => handleDeleteProduct(product)}
                      >
                        Delete
                      </button>
                    </td>
                  </tr>
                ))}
            </tbody>
          </table>
        </div>
      ))}

      {isEditModalOpen && (
        <div className="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
          <div className="bg-white rounded-lg p-6 max-w-2xl w-full shadow-lg">
            <h2 className="text-2xl font-bold mb-4 text-center">
              Edit Product
            </h2>
            <form
              onSubmit={(e) => {
                e.preventDefault();
                axios
                  .post(
                    "http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiSV/SanPhamNCKHSV_Api.php?action=update",
                    editFormData
                  )
                  .then((response) => {
                    alert(
                      response.data.message || "Product updated successfully!"
                    );
                    setProducts((prev) =>
                      prev.map((product) =>
                        product.MaDeTaiSV === editFormData.MaDeTaiSV
                          ? { ...editFormData } // Cập nhật sản phẩm trong danh sách
                          : product
                      )
                    );
                    setIsEditModalOpen(false); // Đóng modal chỉnh sửa
                  })
                  .catch((error) => {
                    console.error("Error editing product:", error);
                  });
              }}
              className="space-y-4"
            >
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label className="block text-sm font-medium mb-1">
                    Product Name
                  </label>
                  <input
                    type="text"
                    name="TenSanPham"
                    value={editFormData.TenSanPham}
                    onChange={(e) =>
                      setEditFormData((prev) => ({
                        ...prev,
                        TenSanPham: e.target.value,
                      }))
                    }
                    className="w-full px-4 py-2 border rounded-lg shadow-sm"
                  />
                </div>
                <div>
                  <label className="block text-sm font-medium mb-1">
                    Completion Date
                  </label>
                  <input
                    type="date"
                    name="NgayHoanThanh"
                    value={editFormData.NgayHoanThanh}
                    onChange={(e) =>
                      setEditFormData((prev) => ({
                        ...prev,
                        NgayHoanThanh: e.target.value,
                      }))
                    }
                    className="w-full px-4 py-2 border rounded-lg shadow-sm"
                  />
                </div>
                <div>
                  <label className="block text-sm font-medium mb-1">
                    Result
                  </label>
                  <input
                    type="text"
                    name="KetQua"
                    value={editFormData.KetQua}
                    onChange={(e) =>
                      setEditFormData((prev) => ({
                        ...prev,
                        KetQua: e.target.value,
                      }))
                    }
                    className="w-full px-4 py-2 border rounded-lg shadow-sm"
                  />
                </div>
                <div>
                  <label className="block text-sm font-medium mb-1">
                    Topic ID
                  </label>
                  <input
                    type="text"
                    name="MaDeTaiSV"
                    value={editFormData.MaDeTaiSV}
                    readOnly
                    className="w-full px-4 py-2 border rounded-lg shadow-sm bg-gray-100"
                  />
                </div>
              </div>
              <div className="flex justify-center mt-6">
                <button
                  type="submit"
                  className="bg-blue-500 text-white px-4 py-2 rounded-lg shadow-sm hover:bg-blue-600 transition"
                >
                  Save
                </button>
                <button
                  type="button"
                  onClick={() => setIsEditModalOpen(false)}
                  className="bg-gray-500 text-white px-4 py-2 rounded-lg shadow-sm hover:bg-gray-600 transition"
                >
                  Cancel
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* Modal */}
      {isModalOpen && (
        <div className="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
          <div className="bg-white rounded-lg p-6 max-w-2xl w-full shadow-lg">
            <h2 className="text-2xl font-bold mb-4 text-center">Add Product</h2>
            <form onSubmit={handleSubmit} className="space-y-4">
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label className="block text-sm font-medium mb-1">
                    Product Name
                  </label>
                  <input
                    type="text"
                    name="TenSanPham"
                    value={formData.TenSanPham}
                    onChange={(e) =>
                      setFormData((prev) => ({
                        ...prev,
                        TenSanPham: e.target.value,
                      }))
                    }
                    className="w-full px-4 py-2 border rounded-lg shadow-sm"
                  />
                </div>
                <div>
                  <label className="block text-sm font-medium mb-1">
                    Completion Date
                  </label>
                  <input
                    type="date"
                    name="NgayHoanThanh"
                    value={formData.NgayHoanThanh}
                    onChange={(e) =>
                      setFormData((prev) => ({
                        ...prev,
                        NgayHoanThanh: e.target.value,
                      }))
                    }
                    className="w-full px-4 py-2 border rounded-lg shadow-sm"
                  />
                </div>
                <div>
                  <label className="block text-sm font-medium mb-1">
                    Result
                  </label>
                  <input
                    type="text"
                    name="KetQua"
                    value={formData.KetQua}
                    onChange={(e) =>
                      setFormData((prev) => ({
                        ...prev,
                        KetQua: e.target.value,
                      }))
                    }
                    className="w-full px-4 py-2 border rounded-lg shadow-sm"
                  />
                </div>
                <div>
                  <label className="block text-sm font-medium mb-1">
                    Topic ID
                  </label>
                  <input
                    type="text"
                    name="MaDeTaiSV"
                    value={formData.MaDeTaiSV}
                    readOnly
                    className="w-full px-4 py-2 border rounded-lg shadow-sm bg-gray-100"
                  />
                </div>
              </div>
              <div className="flex justify-center mt-6">
                <button
                  type="submit"
                  className="bg-blue-500 text-white px-4 py-2 rounded-lg shadow-sm hover:bg-blue-600 transition"
                >
                  Save
                </button>
                <button
                  type="button"
                  onClick={handleCancel}
                  className="bg-gray-500 text-white px-4 py-2 rounded-lg shadow-sm hover:bg-gray-600 transition"
                >
                  Cancel
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
};

export default ProductManager;
