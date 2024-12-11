import React, { useState, useEffect } from "react";
import axios from "axios";

const Sanphamngoaitruong = () => {
  const [products, setProducts] = useState([]);
  const [topics, setTopics] = useState([]);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [formData, setFormData] = useState({
    MaDuAn: "",
    TenSanPham: "",
    NgayHoanThanh: "",
    KetQua: "",
    FileSanPham: "",
  });
  const [searchQuery, setSearchQuery] = useState("");
  const [filterOption, setFilterOption] = useState("all");

  useEffect(() => {
    // Fetching product data
    axios
      .get(
        "http://localhost/Soucre-Code/BackEnd/Api/DuAnNCNT_Api/SanPhamNCNT_Api.php?action=get"
      )
      .then((response) => {
        setProducts(response.data || []);
      })
      .catch((error) => {
        console.error("Error fetching products:", error);
      });

    // Fetching project data
    axios
      .get(
        "http://localhost/Soucre-Code/BackEnd/Api/DuAnNCNT_Api/DuAnNCNT_Api.php?action=get"
      )
      .then((response) => {
        setTopics(response.data || []);
      })
      .catch((error) => {
        console.error("Error fetching projects:", error);
      });
  }, []);

  const handleAddProduct = (maDuAn) => {
    setFormData({
      MaDuAn: maDuAn,
      TenSanPham: "",
      NgayHoanThanh: "",
      KetQua: "",
      FileSanPham: "",
    });
    setIsModalOpen(true);
  };

  const handleCancel = () => {
    setFormData({
      MaDuAn: "",
      TenSanPham: "",
      NgayHoanThanh: "",
      KetQua: "",
      FileSanPham: "",
    });
    setIsModalOpen(false);
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    axios
      .post(
        "http://localhost/Soucre-Code/BackEnd/Api/DuAnNCNT_Api/SanPhamNCNT_Api.php?action=add",
        formData
      )
      .then((response) => {
        alert(response.data.message || "Product added successfully!");
        setProducts([...products, formData]);
        handleCancel();
      })
      .catch((error) => {
        console.error("Error adding product:", error);
      });
  };

  const searchFilter = (topic) => {
    return (
      topic.TenDuAn.toLowerCase().includes(searchQuery.toLowerCase()) ||
      products.some(
        (product) =>
          product.TenSanPham.toLowerCase().includes(searchQuery.toLowerCase()) &&
          product.MaDuAn === topic.MaDuAn
      )
    );
  };

  const filteredTopics = topics.filter((topic) => {
    const matchesSearch = searchFilter(topic);
    return matchesSearch;
  });

  return (
    <div className="p-6 bg-gray-100 rounded-lg shadow-lg max-w-6xl mx-auto">
      <div className="flex items-center justify-between mb-6">
        <h1 className="text-2xl font-semibold">Quản lý sản phẩm ngoài trường</h1>
      </div>

      <div className="mb-4 flex items-center space-x-4">
        {/* Search bar */}
        <div className="flex-1">
          <input
            type="text"
            value={searchQuery}
            onChange={(e) => setSearchQuery(e.target.value)}
            className="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
            placeholder="Tìm kiếm theo tên sản phẩm hoặc mã dự án"
          />
        </div>

        {/* Filter dropdown */}
        <div className="flex-1">
          <select
            value={filterOption}
            onChange={(e) => setFilterOption(e.target.value)}
            className="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
          >
            <option value="all">Tất cả</option>
            <option value="hasFile">Có file</option>
            <option value="noFile">Không có file</option>
          </select>
        </div>
      </div>

      {/* Display topics and products */}
      {filteredTopics.map((topic) => (
        <div
          key={topic.MaDuAn}
          className="mb-6 border border-gray-300 rounded-lg p-4 shadow-sm bg-white"
        >
          <h2 className="text-lg font-bold text-blue-600 mb-2">
            Tên dự án: {topic.TenDuAn}
          </h2>
          <p className="text-sm text-gray-600 mb-2">
            Mô tả: {topic.MoTa || "No Description"}
          </p>

          <button
            className="bg-green-500 text-white px-4 py-2 rounded-lg shadow-sm hover:bg-green-600 transition mb-4"
            onClick={() => handleAddProduct(topic.MaDuAn)}
          >
            Thêm sản phẩm
          </button>

          <table className="min-w-full border-collapse border border-gray-300">
            <thead>
              <tr className="bg-gray-100 text-left">
                <th className="px-4 py-2 border">Mã sản phẩm</th>
                <th className="px-4 py-2 border">Tên sản phẩm</th>
                <th className="px-4 py-2 border">Ngày hoàn thành</th>
                <th className="px-4 py-2 border">Kết quả</th>
                <th className="px-4 py-2 border">File sản phẩm</th>
                <th className="px-4 py-2 border">Thao tác</th>

              </tr>
            </thead>
            <tbody>
              {products
                .filter((product) => product.MaDuAn === topic.MaDuAn)
                .map((product) => (
                  <tr key={product.MaSanPhamNCNT} className="hover:bg-gray-50">
                    <td className="px-4 py-2 border">{product.MaSanPhamNCNT}</td>
                    <td className="px-4 py-2 border">{product.TenSanPham}</td>
                    <td className="px-4 py-2 border">{product.NgayHoanThanh}</td>
                    <td className="px-4 py-2 border">{product.KetQua}</td>
                    <td className="px-4 py-2 border">
                      {product.FileSanPham ? "Có" : "Không"}
                    </td>
                    <td className="px-4 py-2 border">
                      <button
                        className="text-blue-500 hover:text-blue-700 mr-2"
                        onClick={() => handleEditProduct(product)}
                      >
                        Sửa
                      </button>

                      <button
                        className="text-red-500 hover:text-red-700"
                        onClick={() => handleDeleteProduct(product)}
                      >
                        Xóa
                      </button>
                    </td>
                  </tr>
                ))}
            </tbody>
          </table>
        </div>
      ))}

      {/* Modal for adding a product */}
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
              </div>
              <div className="flex justify-center mt-6">
                <button
                  type="submit"
                  className="bg-blue-500 text-white px-6 py-3 rounded-lg shadow-md hover:bg-blue-600 transition"
                >
                  Add Product
                </button>
              </div>
            </form>

            <div className="flex justify-center mt-4">
              <button
                onClick={handleCancel}
                className="bg-gray-500 text-white px-6 py-3 rounded-lg shadow-md hover:bg-gray-600 transition"
              >
                Cancel
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};

export default Sanphamngoaitruong;
