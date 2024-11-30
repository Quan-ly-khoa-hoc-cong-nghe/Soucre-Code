import { useEffect, useState } from "react";
import { FaEye, FaEdit } from "react-icons/fa";

const TopicList = () => {
  const [topics, setTopics] = useState([]);
  const [selectedTopic, setSelectedTopic] = useState(null);
  const [hoSoDetails, setHoSoDetails] = useState(null);
  const [loaiHinhDetails, setLoaiHinhDetails] = useState([]);
  const [nhomNCKHDetails, setNhomNCKHDetails] = useState([]);
  const [giangVienDetails, setGiangVienDetails] = useState([]);
  const [isEditing, setIsEditing] = useState(false);
  const [editedTopic, setEditedTopic] = useState(null);

  // Tải dữ liệu đề tài và loại hình
  useEffect(() => {
    const fetchData = async () => {
      const topicsData = await fetchTopics();
      setTopics(topicsData);

      const loaiHinhData = await fetchLoaiHinh();
      setLoaiHinhDetails(loaiHinhData);
    };

    fetchData();
  }, []);

  // Lấy danh sách đề tài
  const fetchTopics = async () => {
    const response = await fetch(
      "http://localhost/Soucre-Code/BackEnd/Api/DeTaiNCKHGiangVien_Api/DeTaiNCKHGiangVien_Api.php?action=GET"
    );
    const data = await response.json();
    return data;
  };

  // Lấy thông tin hồ sơ
  const fetchHoSo = async (maHoSo) => {
    const response = await fetch(
      "http://localhost/Soucre-Code/BackEnd/Api/DeTaiNCKHGiangVien_Api/HoSoNCKHGiangVien_Api.php?action=GET"
    );
    const data = await response.json();
    return data.find((hoSo) => hoSo.MaHoSo === maHoSo);
  };

  // Lấy dữ liệu loại hình nghiên cứu
  const fetchLoaiHinh = async () => {
    const response = await fetch(
      "http://localhost/Soucre-Code/BackEnd/Api/DeTaiNCKHGiangVien_Api/LoaiHinhNCKHGV_Api.php?action=GET"
    );
    const data = await response.json();
    return data;
  };

  // Lấy thông tin nhóm nghiên cứu
  const fetchNhomNCKH = async (maDeTai) => {
    const response = await fetch(
      "http://localhost/Soucre-Code/BackEnd/Api/DeTaiNCKHGiangVien_Api/NhomNCKHGV_Api.php?action=GET"
    );
    const data = await response.json();
    return data.filter((nhom) => nhom.MaDeTaiNCKHGV === maDeTai);
  };

  // Lấy thông tin giảng viên theo mã nhóm nghiên cứu
  const fetchGiangVien = async (maNhom) => {
    const response = await fetch(
      "http://localhost/Soucre-Code/BackEnd/Api/DeTaiNCKHGiangVien_Api/GiangVienNCKHGV_Api.php?action=GET"
    );
    const data = await response.json();
    return data.filter((giangVien) => giangVien.MaNhomNCKHGV === maNhom);
  };

  // Lấy thông tin giảng viên chi tiết
  // Lấy thông tin giảng viên chi tiết từ mã giảng viên
  const fetchGiangVienDetail = async (maGV) => {
    const response = await fetch(
      "http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiSV/GiangVien_Api.php?action=get"
    );
    const data = await response.json();

    // Lọc giảng viên theo mã giảng viên
    const giangVien = data.GiangVien.find(
      (giangVien) => giangVien.MaGV === maGV
    );
    return giangVien;
  };

  // Lấy tên loại hình từ mã loại hình
  const getLoaiHinhName = (maLoaiHinh) => {
    const loaiHinh = loaiHinhDetails.find(
      (item) => item.MaLoaiHinhNCKH === maLoaiHinh
    );
    return loaiHinh ? loaiHinh.TenLoaiHinh : "Không xác định";
  };

  const handleEdit = () => {
    setIsEditing(true);
    setEditedTopic({ ...selectedTopic }); // Sao chép đề tài hiện tại để chỉnh sửa
  };

  // Hủy bỏ chế độ chỉnh sửa
  const handleCancel = () => {
    setIsEditing(false);
    setEditedTopic(null);
  };

  // Lấy thông tin giảng viên chi tiết khi xem chi tiết đề tài
  const handleViewDetails = async (topic) => {
    setSelectedTopic(topic);
    const hoSo = await fetchHoSo(topic.MaHoSo);
    setHoSoDetails(hoSo);
    const nhom = await fetchNhomNCKH(topic.MaDeTaiNCKHGV);
    setNhomNCKHDetails(nhom);

    // Lấy thông tin giảng viên cho mỗi nhóm nghiên cứu
    const giangVienPromises = nhom.map((nhom) =>
      fetchGiangVien(nhom.MaNhomNCKHGV)
    );
    const giangVienData = await Promise.all(giangVienPromises);
    setGiangVienDetails(giangVienData.flat());

    // Lấy thông tin chi tiết giảng viên
    const giangVienDetailPromises = giangVienData
      .flat()
      .map((giangVien) => fetchGiangVienDetail(giangVien.MaGV));
    const giangVienDetails = await Promise.all(giangVienDetailPromises);

    // Cập nhật giảng viên chi tiết
    setGiangVienDetails(giangVienDetails);
  };

  return (
    <div className="container mx-auto p-6">
      <div className="bg-white rounded-lg shadow-lg p-6">
        <table className="w-full table-auto">
          <thead>
            <tr className="bg-gray-100 border-b">
              <th className="text-left py-3 px-4 font-semibold">Tên Đề Tài</th>
              <th className="text-left py-3 px-4 font-semibold">Mã Hồ Sơ</th>
              <th className="text-left py-3 px-4 font-semibold">Mô Tả</th>
              <th className="text-left py-3 px-4 font-semibold">
                Loại Hình Nghiên Cứu
              </th>
              <th className="text-right py-3 px-4 font-semibold">Hành Động</th>
            </tr>
          </thead>
          <tbody>
            {topics.map((topic) => (
              <tr
                key={topic.MaDeTaiNCKHGV}
                className="border-b hover:bg-gray-50"
              >
                <td className="py-3 px-4">{topic.TenDeTai}</td>
                <td className="py-3 px-4">{topic.MaHoSo}</td>
                <td className="py-3 px-4">{topic.MoTa}</td>
                <td className="py-3 px-4">
                  <span className="px-2 py-1 rounded-full text-sm text-blue-600">
                    {getLoaiHinhName(topic.MaLoaiHinhNCKH)}
                  </span>
                </td>
                <td className="py-3 px-4 text-right">
                  <div className="flex justify-end space-x-2">
                    {/* Icon mắt để xem chi tiết */}
                    <button
                      onClick={() => handleViewDetails(topic)}
                      className="p-2 text-blue-600 hover:bg-blue-100 rounded-full"
                      title="Xem chi tiết"
                    >
                      <FaEye className="w-5 h-5" />
                    </button>
                  </div>
                </td>
              </tr>
            ))}
          </tbody>
        </table>

        {/* Modal xem chi tiết */}
        {selectedTopic && (
          <div className="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
            <div className="bg-white rounded-lg p-8 max-w-lg w-full shadow-lg max-h-[80vh] overflow-y-auto">
              <h2 className="text-2xl font-bold text-center mb-6">
                {selectedTopic.TenDeTai}
              </h2>

              {/* Hiển thị thông tin chi tiết đề tài */}
              <div className="space-y-4">
                <div>
                  <label className="block text-sm font-medium text-gray-600">
                    Mã Hồ Sơ
                  </label>
                  <input
                    type="text"
                    value={selectedTopic.MaHoSo}
                    onChange={(e) =>
                      setSelectedTopic({
                        ...selectedTopic,
                        MaHoSo: e.target.value,
                      })
                    }
                    className="w-full px-4 py-2 border rounded-md"
                    disabled={!isEditing}
                  />
                </div>

                <div>
                  <label className="block text-sm font-medium text-gray-600">
                    Mô Tả
                  </label>
                  <textarea
                    value={selectedTopic.MoTa}
                    onChange={(e) =>
                      setSelectedTopic({
                        ...selectedTopic,
                        MoTa: e.target.value,
                      })
                    }
                    className="w-full px-4 py-2 border rounded-md"
                    disabled={!isEditing}
                  />
                </div>

                <div>
                  <label className="block text-sm font-medium text-gray-600">
                    Loại Hình Nghiên Cứu
                  </label>
                  <select
                    value={selectedTopic.MaLoaiHinhNCKH} // Mặc định là loại hình của đề tài
                    onChange={(e) =>
                      setSelectedTopic({
                        ...selectedTopic,
                        MaLoaiHinhNCKH: e.target.value, // Cập nhật loại hình khi chọn
                      })
                    }
                    className="w-full px-4 py-2 border rounded-md bg-gray-100"
                    disabled={!isEditing} // Nếu không phải chế độ chỉnh sửa, không cho chọn
                  >
                    {/* Lặp qua tất cả các loại hình để tạo option */}
                    {loaiHinhDetails.map((loaiHinh) => (
                      <option
                        key={loaiHinh.MaLoaiHinhNCKH}
                        value={loaiHinh.MaLoaiHinhNCKH}
                      >
                        {loaiHinh.TenLoaiHinh}
                      </option>
                    ))}
                  </select>
                </div>

                {/* Hiển thị thông tin hồ sơ */}
                {hoSoDetails && (
                  <>
                    <div>
                      <label className="block text-sm font-medium text-gray-600">
                        Ngày Nộp Hồ Sơ
                      </label>
                      <input
                        type="date"
                        value={hoSoDetails.NgayNop}
                        onChange={(e) =>
                          setHoSoDetails({
                            ...hoSoDetails,
                            NgayNop: e.target.value,
                          })
                        }
                        className="w-full px-4 py-2 border rounded-md"
                        disabled={!isEditing}
                      />
                    </div>

                    <div>
                      <label className="block text-sm font-medium text-gray-600">
                        Trạng Thái Hồ Sơ
                      </label>
                      <input
                        type="text"
                        value={hoSoDetails.TrangThai}
                        onChange={(e) =>
                          setHoSoDetails({
                            ...hoSoDetails,
                            TrangThai: e.target.value,
                          })
                        }
                        className="w-full px-4 py-2 border rounded-md"
                        disabled={!isEditing}
                      />
                    </div>

                    <div>
                      <label className="block text-sm font-medium text-gray-600">
                        Tệp Hồ Sơ
                      </label>
                      <input
                        type="file"
                        onChange={(e) =>
                          setHoSoDetails({
                            ...hoSoDetails,
                            FileHoSo: e.target.files[0]?.name,
                          })
                        }
                        className="w-full px-4 py-2 border rounded-md"
                        disabled={!isEditing}
                      />
                      {hoSoDetails.FileHoSo && (
                        <a
                          href={`/path/to/hoso/${hoSoDetails.FileHoSo}`}
                          target="_blank"
                          className="text-blue-600"
                        >
                          {hoSoDetails.FileHoSo}
                        </a>
                      )}
                    </div>
                  </>
                )}

                {/* Hiển thị thông tin nhóm nghiên cứu */}
                {nhomNCKHDetails &&
                  nhomNCKHDetails.map((nhom) => (
                    <div key={nhom.MaNhomNCKHGV}>
                      <div>
                        <label className="block text-sm font-medium text-gray-600">
                          Mã Nhóm NCKH
                        </label>
                        <input
                          type="text"
                          value={nhom.MaNhomNCKHGV}
                          onChange={(e) => {
                            const updatedNhom = [...nhomNCKHDetails];
                            updatedNhom[nhomNCKHDetails.indexOf(nhom)] = {
                              ...nhom,
                              MaNhomNCKHGV: e.target.value,
                            };
                            setNhomNCKHDetails(updatedNhom);
                          }}
                          className="w-full px-4 py-2 border rounded-md"
                          disabled={!isEditing}
                        />
                      </div>
                    </div>
                  ))}
                {/* Hiển thị thông tin giảng viên */}
                {giangVienDetails &&
                  giangVienDetails.map((giangVien) => (
                    <div key={giangVien.MaGV}>
                      <div>
                        <label className="block text-sm font-medium text-gray-600">
                          Giảng Viên
                        </label>
                        <input
                          type="text"
                          value={giangVien.HoTenGV}
                          onChange={(e) => {
                            const updatedGiangVien = [...giangVienDetails];
                            updatedGiangVien[
                              giangVienDetails.indexOf(giangVien)
                            ] = { ...giangVien, HoTenGV: e.target.value };
                            setGiangVienDetails(updatedGiangVien);
                          }}
                          className="w-full px-4 py-2 border rounded-md"
                          disabled={!isEditing}
                        />
                      </div>

                      <div>
                        <label className="block text-sm font-medium text-gray-600">
                          Email
                        </label>
                        <input
                          type="email"
                          value={giangVien.EmailGV}
                          onChange={(e) => {
                            const updatedGiangVien = [...giangVienDetails];
                            updatedGiangVien[
                              giangVienDetails.indexOf(giangVien)
                            ] = { ...giangVien, EmailGV: e.target.value };
                            setGiangVienDetails(updatedGiangVien);
                          }}
                          className="w-full px-4 py-2 border rounded-md"
                          disabled={!isEditing}
                        />
                      </div>

                      <div>
                        <label className="block text-sm font-medium text-gray-600">
                          Địa chỉ
                        </label>
                        <input
                          type="text"
                          value={giangVien.DiaChiGV}
                          onChange={(e) => {
                            const updatedGiangVien = [...giangVienDetails];
                            updatedGiangVien[
                              giangVienDetails.indexOf(giangVien)
                            ] = { ...giangVien, DiaChiGV: e.target.value };
                            setGiangVienDetails(updatedGiangVien);
                          }}
                          className="w-full px-4 py-2 border rounded-md"
                          disabled={!isEditing}
                        />
                      </div>

                      <div>
                        <label className="block text-sm font-medium text-gray-600">
                          Ngày sinh
                        </label>
                        <input
                          type="date"
                          value={giangVien.NgaySinhGV}
                          onChange={(e) => {
                            const updatedGiangVien = [...giangVienDetails];
                            updatedGiangVien[
                              giangVienDetails.indexOf(giangVien)
                            ] = { ...giangVien, NgaySinhGV: e.target.value };
                            setGiangVienDetails(updatedGiangVien);
                          }}
                          className="w-full px-4 py-2 border rounded-md"
                          disabled={!isEditing}
                        />
                      </div>

                      <div>
                        <label className="block text-sm font-medium text-gray-600">
                          Điểm NCKH
                        </label>
                        <input
                          type="number"
                          value={giangVien.DiemNCKH}
                          onChange={(e) => {
                            const updatedGiangVien = [...giangVienDetails];
                            updatedGiangVien[
                              giangVienDetails.indexOf(giangVien)
                            ] = { ...giangVien, DiemNCKH: e.target.value };
                            setGiangVienDetails(updatedGiangVien);
                          }}
                          className="w-full px-4 py-2 border rounded-md"
                          disabled={!isEditing}
                        />
                      </div>
                    </div>
                  ))}

                {/* Nút chỉnh sửa */}
                <div className="text-center">
                  {!isEditing && (
                    <button
                      onClick={handleEdit}
                      className="p-2 mt-4 bg-blue-500 text-white rounded"
                      title="Chỉnh sửa thông tin"
                    >
                      <FaEdit className="inline-block mr-2" />
                      Chỉnh Sửa
                    </button>
                  )}
                </div>
              </div>

              {/* Modal chỉnh sửa */}
              {isEditing && editedTopic && (
                <div className="mt-6">
                  <div className="space-y-4">
                    {/* Lưu và hủy */}
                    <div className="flex justify-end space-x-4 mt-4">
                      <button
                        onClick={handleCancel}
                        className="p-2 bg-gray-500 text-white rounded"
                      >
                        Hủy
                      </button>
                      <button className="p-2 bg-blue-600 text-white rounded">
                        Lưu
                      </button>
                    </div>
                  </div>
                </div>
              )}
            </div>
          </div>
        )}
      </div>
    </div>
  );
};

export default TopicList;
