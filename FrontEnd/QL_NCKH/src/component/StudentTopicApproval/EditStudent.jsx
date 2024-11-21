import React, { useState, useEffect } from "react";

const EditStudent = () => {
  const [sinhVienList, setSinhVienList] = useState([]);
  const [sinhVienNhom, setSinhVienNhom] = useState([]);
  const [deTaiList, setDeTaiList] = useState([]);
  const [selectedStudent, setSelectedStudent] = useState(null);
  const [isProductModalOpen, setIsProductModalOpen] = useState(false);
  const [groups, setGroups] = useState([]);

  const [formData, setFormData] = useState({
    MaSinhVien: "",
    TenSinhVien: "",
    EmailSV: "",
    sdtSV: "",
  });
    const addGroup = () => {
    const newGroup = {
      id: groups.length + 1,
      name: `Nhóm NCKH ${groups.length + 1}`,
      topic: 'Đề tài NCKH',
    };

    setGroups([...groups, newGroup]);
  };
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [isAddStudentModalOpen, setIsAddStudentModalOpen] = useState(false);
  const [newStudentData, setNewStudentData] = useState({
    MaSinhVien: "",
    TenSinhVien: "",
    EmailSV: "",
    sdtSV: "",
  });
  const [selectedGroup, setSelectedGroup] = useState(null);

  useEffect(() => {
    const fetchSinhVienData = async () => {
      try {
        const response = await fetch(
          "http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiSV/SinhVien_Api.php?action=get"
        );
        const data = await response.json();
        if (data.SinhVien) {
          setSinhVienList(data.SinhVien);
        }
      } catch (error) {
        console.error("Có lỗi khi lấy dữ liệu sinh viên:", error);
      }
    };

    const fetchSinhVienNhomData = async () => {
      try {
        const response = await fetch(
          "http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiSV/SinhVienNCKHSV_Api.php?action=get"
        );
        const data = await response.json();
        if (data.SinhVienNCKHSV) {
          setSinhVienNhom(data.SinhVienNCKHSV);
        }
      } catch (error) {
        console.error("Có lỗi khi lấy dữ liệu nhóm sinh viên:", error);
      }
    };

    const fetchDeTaiData = async () => {
      try {
        const response = await fetch(
          "http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiSV/DeTaiNCKHSV_Api.php?action=get"
        );
        const data = await response.json();
        if (data.DeTaiNCKHSV) {
          setDeTaiList(data.DeTaiNCKHSV);
        }
      } catch (error) {
        console.error("Có lỗi khi lấy dữ liệu đề tài:", error);
      }
    };

    fetchSinhVienData();
    fetchSinhVienNhomData();
    fetchDeTaiData();
  }, []);

  const handleSelectStudent = (sinhVien) => {
    setSelectedStudent(sinhVien);
    setFormData({
      MaSinhVien: sinhVien.MaSinhVien,
      TenSinhVien: sinhVien.TenSinhVien,
      EmailSV: sinhVien.EmailSV,
      sdtSV: sinhVien.sdtSV,
    });
    setIsModalOpen(true);
  };

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setFormData({
      ...formData,
      [name]: value,
    });
  };

  const handleSave = async () => {
    try {
      const response = await fetch(
        "http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiSV/SinhVien_Api.php?action=update",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(formData), // Chỉ gửi thông tin được chỉnh sửa
        }
      );

      const result = await response.json();
      if (result.message === "Cập nhật sinh viên thành công") {
        alert("Thông tin sinh viên đã được cập nhật!");
        setIsModalOpen(false);
        fetchSinhVienData(); // Cập nhật lại danh sách sinh viên
      } else {
        alert("Không thể cập nhật thông tin sinh viên");
      }
    } catch (error) {
      console.error("Có lỗi khi gửi dữ liệu:", error);
      alert("Lỗi khi gửi yêu cầu cập nhật");
    }
  };

  const groupedSinhVien = sinhVienList.map((sinhVien) => {
    const nhom = sinhVienNhom.find(
      (nhom) => nhom.MaSinhVien === sinhVien.MaSinhVien
    );
    return {
      ...sinhVien,
      MaNhomNCKHSV: nhom ? nhom.MaNhomNCKHSV : "Chưa có nhóm",
    };
  });

  const groupedByNhom = groupedSinhVien.reduce((acc, sinhVien) => {
    const nhom = sinhVien.MaNhomNCKHSV;
    if (!acc[nhom]) {
      acc[nhom] = [];
    }
    acc[nhom].push(sinhVien);
    return acc;
  }, {});

  const groupedByDeTai = deTaiList.reduce((acc, deTai) => {
    const nhomId = deTai.MaNhomNCKHSV;
    if (!acc[nhomId]) {
      acc[nhomId] = [];
    }
    acc[nhomId].push(deTai);
    return acc;
  }, {});

  const handleAddStudentToGroup = (nhom) => {
    const groupStudents = groupedByNhom[nhom];
    if (groupStudents.length >= 3) {
      alert("Nhóm này đã đủ 3 sinh viên.");
      return;
    }
    setSelectedGroup(nhom);
    setNewStudentData({
      MaSinhVien: "",
      TenSinhVien: "",
      EmailSV: "",
      sdtSV: "",
    });
    setIsAddStudentModalOpen(true);
  };

  const handleSaveNewStudent = async () => {
    // Kiểm tra nếu có trường trống
    if (
      !newStudentData.MaSinhVien ||
      !newStudentData.TenSinhVien ||
      !newStudentData.EmailSV ||
      !newStudentData.sdtSV
    ) {
      alert("Vui lòng nhập đầy đủ thông tin.");
      return;
    }

    // Kiểm tra mã sinh viên có bị trùng hay không
    const isDuplicate = sinhVienList.some(
      (sinhVien) => sinhVien.MaSinhVien === newStudentData.MaSinhVien
    );

    if (isDuplicate) {
      alert("Mã sinh viên đã tồn tại. Vui lòng chọn mã khác.");
      return;
    }

    const newSinhVien = {
      MaSinhVien: newStudentData.MaSinhVien,
      TenSinhVien: newStudentData.TenSinhVien,
      EmailSV: newStudentData.EmailSV,
      sdtSV: newStudentData.sdtSV,
    };

    try {
      // Gửi yêu cầu POST để thêm sinh viên mới vào bảng SinhVien
      const response = await fetch(
        "http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiSV/SinhVien_Api.php?action=add",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(newSinhVien),
        }
      );

      const result = await response.json();
      console.log(result); // Log kết quả API

      if (response.ok && result.message === "Thêm sinh viên thành công") {
        alert("Sinh viên đã được thêm!");

        // Lưu sinh viên vào nhóm đã chọn (MaNhomNCKHSV)
        const newSinhVienNhom = {
          MaNhomNCKHSV: selectedGroup, // Nhóm được chọn
          MaSinhVien: newStudentData.MaSinhVien, // Mã sinh viên vừa tạo
        };

        const nhomResponse = await fetch(
          "http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiSV/SinhVienNCKHSV_Api.php?action=add",
          {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify(newSinhVienNhom),
          }
        );

        const nhomResult = await nhomResponse.json();
        if (
          nhomResponse.ok &&
          nhomResult.message === "Thêm sinh viên vào nhóm thành công"
        ) {
          alert("Sinh viên đã được thêm vào nhóm!");
          setIsAddStudentModalOpen(false);
          fetchSinhVienData(); // Cập nhật lại dữ liệu sinh viên sau khi thêm mới
        } else {
          alert(nhomResult.message || "Không thể thêm sinh viên vào nhóm.");
        }
      } else {
        alert(result.message || "Không thể thêm sinh viên.");
      }
    } catch (error) {
      console.error("Có lỗi khi thêm sinh viên:", error);
      alert("Lỗi khi gửi yêu cầu thêm sinh viên.");
    }
  };

  const handleDeleteStudent = async (maSinhVien, nhom) => {
    // Xóa sinh viên khỏi nhóm trong bảng SinhVienNCKHSV
    try {
      const nhomResponse = await fetch(
        "http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiSV/SinhVienNCKHSV_Api.php?action=delete",
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ MaSinhVien: maSinhVien, MaNhomNCKHSV: nhom }),
        }
      );

      const nhomResult = await nhomResponse.json();
      if (
        nhomResponse.ok &&
        nhomResult.message === "Xóa sinh viên khỏi nhóm thành công"
      ) {
        alert("Sinh viên đã được xóa khỏi nhóm!");

        // Sau khi xóa khỏi nhóm, tiếp tục xóa sinh viên khỏi bảng SinhVien
        const sinhVienResponse = await fetch(
          "http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiSV/SinhVien_Api.php?action=delete",
          {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify({ MaSinhVien: maSinhVien }),
          }
        );

        const sinhVienResult = await sinhVienResponse.json();
        if (
          sinhVienResponse.ok &&
          sinhVienResult.message === "Xóa sinh viên thành công"
        ) {
          alert("Sinh viên đã được xóa khỏi hệ thống!");
          fetchSinhVienData(); // Cập nhật lại danh sách sinh viên sau khi xóa
        } else {
          alert(
            sinhVienResult.message || "Không thể xóa sinh viên khỏi hệ thống."
          );
        }
      } else {
        alert(nhomResult.message || "Không thể xóa sinh viên khỏi nhóm.");
      }
    } catch (error) {
      console.error("Có lỗi khi xóa sinh viên:", error);
      alert("Lỗi khi gửi yêu cầu xóa sinh viên.");
    }
  };

  return (
    <div className="p-6">
      <button
  onClick={() => setIsProductModalOpen(true)}
  className="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
>
  Xem Đề Tài Chưa Có Nhóm
</button>
    {Object.keys(groupedByNhom).map((nhom) => (
      <div
        key={nhom}
        className="mb-6 p-4 border border-blue-500 rounded-lg shadow-md bg-white"
      >
        <h2 className="text-xl font-semibold mb-2 text-blue-600">
          Nhóm: {nhom}
        </h2>
  
        <button
          onClick={() => handleAddStudentToGroup(nhom)}
          className="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600"
        >
          Thêm sinh viên
        </button>
        {groupedByDeTai[nhom] && groupedByDeTai[nhom].length > 0 && (
          <div className="mb-4">
            <h3 className="text-lg font-medium">Đề Tài:</h3>
            <ul className="list-disc pl-5">
              {groupedByDeTai[nhom].map((deTai) => (
                <li key={deTai.MaDeTaiSV} className="ml-4 text-gray-700">
                  {deTai.TenDeTai}
                </li>
              ))}
            </ul>
          </div>
        )}
  
        <table className="min-w-full table-auto border-collapse border border-gray-300 mt-4">
          <thead>
            <tr className="bg-gray-100 text-sm text-left">
              <th className="px-4 py-2 border">Mã Sinh Viên</th>
              <th className="px-4 py-2 border">Tên Sinh Viên</th>
              <th className="px-4 py-2 border">Email</th>
              <th className="px-4 py-2 border">Số điện thoại</th>
              <th className="px-4 py-2 border">Mã Nhóm</th>
              <th className="px-4 py-2 border">Thao tác</th>
            </tr>
          </thead>
          <tbody>
            {groupedByNhom[nhom].map((sinhVien) => (
              <tr key={sinhVien.MaSinhVien} className="hover:bg-gray-50">
                <td className="px-4 py-2 border">{sinhVien.MaSinhVien}</td>
                <td className="px-4 py-2 border">{sinhVien.TenSinhVien}</td>
                <td className="px-4 py-2 border">{sinhVien.EmailSV}</td>
                <td className="px-4 py-2 border">{sinhVien.sdtSV}</td>
                <td className="px-4 py-2 border">{sinhVien.MaNhomNCKHSV}</td>
                <td className="px-4 py-2 border">
                  <button
                    onClick={() => handleSelectStudent(sinhVien)}
                    className="text-blue-600 hover:text-blue-800"
                  >
                    Sửa
                  </button>
                  <button
                    onClick={() => handleDeleteStudent(sinhVien.MaSinhVien, nhom)}
                    className="text-red-600 hover:text-red-800 ml-4"
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
  
  {isProductModalOpen && (
  <div className="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-50">
    <div className="bg-white p-6 rounded-md shadow-lg w-3/4 max-w-4xl">
      <h2 className="text-xl font-semibold mb-4 text-center">Đề Tài Chưa Có Nhóm</h2>
      
      <table className="min-w-full table-auto border-collapse border border-gray-300">
        <thead className="bg-gray-100">
          <tr className="text-sm text-left">
            <th className="px-4 py-2 border">Mã Đề Tài</th>
            <th className="px-4 py-2 border">Tên Đề Tài</th>
            <th className="px-4 py-2 border">Mô Tả</th>
            <th className="px-4 py-2 border">Thao Tác</th>
          </tr>
        </thead>
        <tbody>
          {deTaiList
            .filter((deTai) => !deTai.MaNhomNCKHSV) // Lọc các đề tài chưa có nhóm
            .map((deTai) => (
              <tr key={deTai.MaDeTaiSV} className="hover:bg-gray-50">
                <td className="px-4 py-2 border">{deTai.MaDeTaiSV}</td>
                <td className="px-4 py-2 border">{deTai.TenDeTai}</td>
                <td className="px-4 py-2 border">{deTai.MoTa || "Không có mô tả"}</td>
                <td className="px-4 py-2 border">
                  <button
                    className="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
                  >
                    Thêm Nhóm
                  </button>
                </td>
              </tr>
            ))}
        </tbody>
      </table>

      <div className="text-center mt-4">
        <button
          onClick={() => setIsProductModalOpen(false)}
          className="px-6 py-2 bg-red-600 text-white rounded hover:bg-red-700"
        >
          Đóng
        </button>
      </div>
    </div>
  </div>
)}


      {/* Modal sửa thông tin sinh viên */}
      {isModalOpen && (
        <div className="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-50">
          <div className="bg-white p-6 rounded-md shadow-lg w-96">
            <h2 className="text-xl font-semibold mb-4 text-center">
              Sửa Thông Tin Sinh Viên
            </h2>
            <form className="space-y-4">
              {/* Mã Sinh Viên không thể sửa */}
              <div>
                <label className="block font-medium mb-2">Mã Sinh Viên:</label>
                <input
                  type="text"
                  value={formData.MaSinhVien}
                  disabled
                  className="w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-100"
                />
              </div>

              {/* Các trường có thể sửa */}
              <div>
                <label className="block font-medium mb-2">Tên Sinh Viên:</label>
                <input
                  type="text"
                  name="TenSinhVien"
                  value={formData.TenSinhVien}
                  onChange={handleInputChange}
                  className="w-full px-4 py-2 border border-gray-300 rounded-md"
                />
              </div>

              <div>
                <label className="block font-medium mb-2">Email:</label>
                <input
                  type="email"
                  name="EmailSV"
                  value={formData.EmailSV}
                  onChange={handleInputChange}
                  className="w-full px-4 py-2 border border-gray-300 rounded-md"
                />
              </div>

              <div>
                <label className="block font-medium mb-2">Số Điện Thoại:</label>
                <input
                  type="text"
                  name="sdtSV"
                  value={formData.sdtSV}
                  onChange={handleInputChange}
                  className="w-full px-4 py-2 border border-gray-300 rounded-md"
                />
              </div>

              <div className="text-center mt-4">
                <button
                  type="button"
                  onClick={handleSave}
                  className="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700"
                >
                  Lưu
                </button>
                <button
                  type="button"
                  onClick={() => setIsModalOpen(false)}
                  className="px-6 py-2 bg-red-600 text-white rounded hover:bg-red-700 ml-4"
                >
                  Đóng
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
      {isAddStudentModalOpen && (
        <div className="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-50">
          <div className="bg-white p-6 rounded-md shadow-lg w-96">
            <h2 className="text-xl font-semibold mb-4 text-center">
              Thêm Sinh Viên Mới
            </h2>
            <form className="space-y-4">
              <div>
                <label className="block font-medium mb-2">Mã Sinh Viên:</label>
                <input
                  type="text"
                  value={newStudentData.MaSinhVien}
                  onChange={(e) =>
                    setNewStudentData({
                      ...newStudentData,
                      MaSinhVien: e.target.value,
                    })
                  }
                  className="w-full px-4 py-2 border border-gray-300 rounded-md"
                />
              </div>
              <div>
                <label className="block font-medium mb-2">Tên Sinh Viên:</label>
                <input
                  type="text"
                  value={newStudentData.TenSinhVien}
                  onChange={(e) =>
                    setNewStudentData({
                      ...newStudentData,
                      TenSinhVien: e.target.value,
                    })
                  }
                  className="w-full px-4 py-2 border border-gray-300 rounded-md"
                />
              </div>
              <div>
                <label className="block font-medium mb-2">Email:</label>
                <input
                  type="email"
                  value={newStudentData.EmailSV}
                  onChange={(e) =>
                    setNewStudentData({
                      ...newStudentData,
                      EmailSV: e.target.value,
                    })
                  }
                  className="w-full px-4 py-2 border border-gray-300 rounded-md"
                />
              </div>
              <div>
                <label className="block font-medium mb-2">Số Điện Thoại:</label>
                <input
                  type="text"
                  value={newStudentData.sdtSV}
                  onChange={(e) =>
                    setNewStudentData({
                      ...newStudentData,
                      sdtSV: e.target.value,
                    })
                  }
                  className="w-full px-4 py-2 border border-gray-300 rounded-md"
                />
              </div>
              <div className="text-center mt-4">
                <button
                  type="button"
                  onClick={handleSaveNewStudent}
                  className="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700"
                >
                  Lưu
                </button>
                <button
                  type="button"
                  onClick={() => setIsAddStudentModalOpen(false)}
                  className="px-6 py-2 bg-red-600 text-white rounded hover:bg-red-700 ml-4"
                >
                  Đóng
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
};

export default EditStudent;
