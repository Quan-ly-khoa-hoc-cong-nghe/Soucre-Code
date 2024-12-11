import React, { useState, useEffect } from "react";
import axios from "axios";

const Sanphamdtcs = () => {
  const [products, setProducts] = useState([]);
  const [topics, setTopics] = useState([]); // Store topics
  const [searchQuery, setSearchQuery] = useState("");
  const [filterOption, setFilterOption] = useState("all"); // "all", "completed"

  useEffect(() => {
    // Fetch the products data from the API
    axios
      .get("http://localhost/Soucre-Code/BackEnd/Api/DeTaiCapCoSo_Api/SanPhamDTCS_Api.php?action=get")
      .then((response) => {
        setProducts(response.data || []);
      })
      .catch((error) => {
        console.error("Error fetching products:", error);
      });

    // Fetch the topics data from the API
    axios
      .get("http://localhost/Soucre-Code/BackEnd/Api/DeTaiCapCoSo_Api/DeTaiCapSo_Api.php?action=get")
      .then((response) => {
        setTopics(response.data || []);
      })
      .catch((error) => {
        console.error("Error fetching topics:", error);
      });
  }, []);

  // Search filter logic
  const searchFilter = (product) => {
    return (
      product.TenSanPham.toLowerCase().includes(searchQuery.toLowerCase()) ||
      product.MaSanPhamDTCS.toString().includes(searchQuery.toLowerCase())
    );
  };

  // Filter products based on the filterOption
  const filteredProducts = products.filter((product) => {
    const matchesSearch = searchFilter(product);

    if (filterOption === "completed") {
      return product.KetQua === "Đạt" && matchesSearch;
    }

    return matchesSearch; // Show all if no specific filter
  });

  // Function to get topic name based on MaDTCS
  const getTopicName = (maDTCS) => {
    const topic = topics.find((t) => t.MaDTCS === maDTCS);
    return topic ? topic.TenDeTai : "Unknown Topic";
  };

  return (
    <div className="p-6 bg-gray-100 rounded-lg shadow-lg max-w-6xl mx-auto">
      <div className="flex items-center justify-between mb-6">
        <h1 className="text-2xl font-semibold">Quản lý sản phẩm đề tài cấp sở</h1>
      </div>

      <div className="mb-4 flex items-center space-x-4">
        {/* Search Input */}
        <div className="flex-1">
          <input
            type="text"
            value={searchQuery}
            onChange={(e) => setSearchQuery(e.target.value)}
            className="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
            placeholder="Tìm kiếm theo mã sản phẩm hoặc tên sản phẩm"
          />
        </div>

        {/* Dropdown Filter */}
        <div className="flex-1">
          <select
            value={filterOption}
            onChange={(e) => setFilterOption(e.target.value)}
            className="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
          >
            <option value="all">Tất cả</option>
            <option value="completed">Đạt</option>
          </select>
        </div>
      </div>

      {/* Displaying filtered list of products */}
      {filteredProducts.map((product) => (
        <div
          key={product.MaSanPhamDTCS}
          className="mb-6 border border-gray-300 rounded-lg p-4 shadow-sm bg-white"
        >
          <button
            className="bg-green-500 text-white px-4 py-2 rounded-lg shadow-sm hover:bg-green-600 transition mb-4"
            onClick={() => handleAddProduct(topic.MaDeTaiSV)}
          >
            Thêm sản phẩm
          </button>
          <h2 className="text-lg font-bold text-blue-600 mb-2">
            Tên sản phẩm: {product.TenSanPham}
          </h2>
          <p className="text-sm text-gray-600 mb-2">Mã sản phẩm: {product.MaSanPhamDTCS}</p>
          <p className="text-sm text-gray-600 mb-2">Tên đề tài: {getTopicName(product.MaDTCS)}</p> {/* Display the topic name */}
          <p className="text-sm text-gray-600 mb-2">Ngày hoàn thành: {product.NgayHoanThanh}</p>
          <p className="text-sm text-gray-600 mb-2">Kết quả: {product.KetQua}</p>
          <p className="text-sm text-gray-600 mb-2">Mã đề tài cấp sở: {product.MaDTCS}</p>
          <p className="text-sm text-gray-600 mb-2">
            Tệp sản phẩm: <a href={`/files/${product.FileSanPham}`} target="_blank" className="text-blue-500">Download</a>
          </p>
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
        </div>
      ))}
    </div>
  );
};

export default Sanphamdtcs;
