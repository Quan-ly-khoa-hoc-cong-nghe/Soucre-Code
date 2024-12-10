import React, { useState, useEffect } from "react";

const ProductLecturer = () => {
  const [isAddGroupModalOpen, setIsAddGroupModalOpen] = useState(false);
  const [isAddLecturerModalOpen, setIsAddLecturerModalOpen] = useState(false); // State for adding lecturer
  const [selectedGroup, setSelectedGroup] = useState(null); // Track the selected group
  const [projects, setProjects] = useState([]); // State to hold project data
  const [groups, setGroups] = useState([]); // State to hold group data
  const [lecturers, setLecturers] = useState([]); // State to hold lecturer data
  const [fullLecturerData, setFullLecturerData] = useState([]); // State to hold full lecturer details (HoTenGV)
  const [selectedLecturer, setSelectedLecturer] = useState(""); // State for selected lecturer in the modal
  const [isRemoveLecturerModalOpen, setIsRemoveLecturerModalOpen] = useState(false); // Modal xóa giảng viên
const [lecturerToRemove, setLecturerToRemove] = useState(""); // Giảng viên chọn xóa


  // Fetch data from APIs on component mount
  useEffect(() => {
    const fetchData = async () => {
      try {
        // Fetch project data
        const projectResponse = await fetch(
          "http://localhost/Soucre-Code/BackEnd/Api/DeTaiNCKHGiangVien_Api/DeTaiNCKHGiangVien_Api.php?action=GET"
        );
        const projectData = await projectResponse.json();

        // Fetch group data
        const groupResponse = await fetch(
          "http://localhost/Soucre-Code/BackEnd/Api/DeTaiNCKHGiangVien_Api/NhomNCKHGV_Api.php?action=GET"
        );
        const groupData = await groupResponse.json();

        // Fetch lecturer data from the first API (GiangVienNCKHGV_Api)
        const lecturerResponse = await fetch(
          "http://localhost/Soucre-Code/BackEnd/Api/DeTaiNCKHGiangVien_Api/GiangVienNCKHGV_Api.php?action=GET"
        );
        const lecturerData = await lecturerResponse.json();

        // Fetch full lecturer details from the second API (GiangVien_Api)
        const fullLecturerResponse = await fetch(
          "http://localhost/Soucre-Code/BackEnd/Api/DuyetDeTaiSV/GiangVien_Api.php?action=get"
        );
        const fullLecturerData = await fullLecturerResponse.json();

        // Map fullLecturerData to a lookup table by MaGV for easy access
        const lecturerLookup = fullLecturerData.GiangVien.reduce(
          (acc, lecturer) => {
            acc[lecturer.MaGV] = lecturer.HoTenGV;
            return acc;
          },
          {}
        );

        // Combine project, group, and lecturer data
        const combinedData = projectData.map((project) => {
          // Find group data related to the project
          const group = groupData.find(
            (group) => group.MaDeTaiNCKHGV === project.MaDeTaiNCKHGV
          );

          // Find all lecturers for the group's MaNhomNCKHGV
          const groupLecturers = lecturerData.filter(
            (lecturer) => lecturer.MaNhomNCKHGV === group?.MaNhomNCKHGV
          );

          // Map lecturer codes to full names using the lookup
          const lecturerNames = groupLecturers
            .map((lecturer) => lecturerLookup[lecturer.MaGV] || "Unknown")
            .join(", ");

          return {
            ...project,
            MaNhomNCKHGV: group ? group.MaNhomNCKHGV : "N/A",
            lecturers: lecturerNames, // Add full lecturer names to the project
            lecturerIds: groupLecturers.map((lecturer) => lecturer.MaGV), // Store lecturer IDs for easy exclusion
          };
        });

        setProjects(combinedData); // Set the combined data into the state
        setGroups(groupData);
        setLecturers(lecturerData);
        setFullLecturerData(fullLecturerData.GiangVien);
      } catch (error) {
        console.error("Error fetching data", error);
      }
    };

    fetchData();
  }, []);
  const handleOpenRemoveLecturerModal = (group) => {
    setSelectedGroup(group);
    setIsRemoveLecturerModalOpen(true);
  };
  
  const handleAddLecturerToGroup = (group) => {
    setSelectedGroup(group);
    setIsAddLecturerModalOpen(true);
  };
  const handleAddLecturer = async () => {
    if (selectedLecturer && selectedGroup) {
      const lecturerLimit = 3; // Maximum number of lecturers per group

      // Check if the group already has 3 lecturers
      if (selectedGroup.lecturerIds.length >= lecturerLimit) {
        alert("Nhóm này đã có đủ giảng viên (3 giảng viên).");
        return; // Don't proceed if the limit is reached
      }

      const newLecturer = {
        SoGioQuyDoi: 1,
        MaNhomNCKHGV: selectedGroup.MaNhomNCKHGV,
        MaGV: selectedLecturer, // The selected lecturer ID
      };

      try {
        // Send POST request to the API
        const response = await fetch(
          "http://localhost/Soucre-Code/BackEnd/Api/DeTaiNCKHGiangVien_Api/GiangVienNCKHGV_Api.php?action=POST",
          {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify(newLecturer),
          }
        );

        if (!response.ok) {
          throw new Error("Không thể thêm giảng viên vào nhóm.");
        }

        const result = await response.json();
        if (result.success) {
          alert("Giảng viên đã được thêm thành công.");
          // Optionally reload the group data or update the UI
          setIsAddLecturerModalOpen(false);
        } else {
          // Display the specific error message from the server
          alert(result.message || "Đã xảy ra lỗi khi thêm giảng viên.");
        }
      } catch (error) {
        console.error("Error adding lecturer:", error);
        // Display detailed error message
        alert("Có lỗi xảy ra khi thêm giảng viên: " + error.message);
      }
    } else {
      alert("Vui lòng chọn giảng viên và nhóm trước.");
    }
  };

  // Filter out lecturers that have already been added to the group
  const availableLecturers = fullLecturerData.filter((lecturer) => {
    // Check if the lecturer is already in the group by comparing their MaGV with the added lecturers
    return !selectedGroup?.lecturerIds.includes(lecturer.MaGV);
  });
  const handleRemoveLecturerFromGroup = async (lecturerId) => {
    if (!lecturerId || !selectedGroup) {
      alert("Vui lòng chọn giảng viên và nhóm trước.");
      return;
    }
  
    try {
      const response = await fetch(
        "http://localhost/Soucre-Code/BackEnd/Api/DeTaiNCKHGiangVien_Api/GiangVienNCKHGV_Api.php?action=DELETE",
        {
          method: "DELETE",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            MaNhomNCKHGV: selectedGroup.MaNhomNCKHGV, // Use the selected group's MaNhomNCKHGV
            MaGV: lecturerId, // The lecturer's ID to remove
          }),
        }
      );
  
      if (response.ok) {
        alert("Giảng viên đã được xóa thành công.");
        
        // Update the state to remove the lecturer from the group
        setProjects((prevProjects) =>
          prevProjects.map((project) => {
            if (project.MaNhomNCKHGV === selectedGroup.MaNhomNCKHGV) {
              return {
                ...project,
                lecturers: project.lecturers
                  .split(", ")
                  .filter((name) => name !== lecturerId) // Remove the lecturer from the list
                  .join(", "),
                lecturerIds: project.lecturerIds.filter((id) => id !== lecturerId), // Remove the lecturer ID from the list
              };
            }
            return project;
          })
        );
        setIsRemoveLecturerModalOpen(false); // Close the modal after removal
      } else {
        alert("Không thể xóa giảng viên.");
      }
    } catch (error) {
      console.error("Error removing lecturer:", error);
      alert("Có lỗi xảy ra khi xóa giảng viên.");
    }
  };
  
  return (
    <div className="p-6">
      {/* Nhóm giảng viên */}
      <div className="mb-6 p-4 border border-blue-500 rounded-lg shadow-md bg-white">
        <h2 className="text-xl font-semibold mb-2 text-blue-600">
         Sản phẩm NCKHGV
        </h2>

        {/* Bảng thông tin đề tài và nhóm giảng viên */}
        <table className="min-w-full table-auto border-collapse border border-gray-300 mt-4">
          <thead>
            <tr className="bg-gray-100 text-sm text-left">
              <th className="px-4 py-2 border">Mã Đề sTài</th>
              <th className="px-4 py-2 border">Tên Đề Tài</th>
              <th className="px-4 py-2 border">Mã Nhóm</th>
              <th className="px-4 py-2 border">Giảng Viên</th>
              <th className="px-4 py-2 border">Thao Tác</th>{" "}
              {/* New column for actions */}
            </tr>
          </thead>
          <tbody>
            {projects.map((project) => (
              <tr key={project.MaDeTaiNCKHGV} className="hover:bg-gray-50">
                <td className="px-4 py-2 border">{project.MaDeTaiNCKHGV}</td>
                <td className="px-4 py-2 border">{project.TenDeTai}</td>
                <td className="px-4 py-2 border">{project.MaNhomNCKHGV}</td>
                <td className="px-4 py-2 border">{project.lecturers}</td>
                <td className="px-4 py-2 border">
                  {/* Add Lecturer button */}
                  <button
                    onClick={() => handleAddLecturerToGroup(project)}
                    className="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
                  >
                    Thêm Thành Viên
                  </button>
                  <button
  onClick={() => handleOpenRemoveLecturerModal(project)}
  className="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 ml-2"
>
  Xóa thành viên
</button>

                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
      {isRemoveLecturerModalOpen && (
  <div className="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-50">
    <div className="bg-white p-6 rounded-md shadow-lg w-96">
      <h2 className="text-xl font-semibold mb-4 text-center">
        Chọn Giảng Viên Cần Xóa
      </h2>
      <form className="space-y-4">
        <div>
          <label className="block font-medium mb-2">
            Chọn Giảng Viên:
          </label>
          <select
            onChange={(e) => setLecturerToRemove(e.target.value)}
            value={lecturerToRemove}
            className="w-full px-4 py-2 border border-gray-300 rounded-md"
          >
            <option value="">Chọn Giảng Viên</option>
            {selectedGroup?.lecturerIds.map((lecturerId) => {
              const lecturer = fullLecturerData.find(
                (item) => item.MaGV === lecturerId
              );
              return (
                <option key={lecturer.MaGV} value={lecturer.MaGV}>
                  {lecturer.HoTenGV} - {lecturer.MaGV}
                </option>
              );
            })}
          </select>
        </div>
        <div className="text-center mt-4">
          <button
            type="button"
            onClick={() => handleRemoveLecturerFromGroup(lecturerToRemove)}
            className="px-6 py-2 bg-red-600 text-white rounded hover:bg-red-700"
          >
            Xóa
          </button>

          <button
            type="button"
            onClick={() => setIsRemoveLecturerModalOpen(false)}
            className="px-6 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 ml-4"
          >
            Đóng
          </button>
        </div>
      </form>
    </div>
  </div>
)}

      {/* Modal thêm giảng viên */}
      {isAddLecturerModalOpen && (
        <div className="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-50">
          <div className="bg-white p-6 rounded-md shadow-lg w-96">
            <h2 className="text-xl font-semibold mb-4 text-center">
              Thêm Giảng Viên Vào Nhóm
            </h2>
            <form className="space-y-4">
              <div>
                <label className="block font-medium mb-2">
                  Chọn Giảng Viên:
                </label>
                <select
                  onChange={(e) => setSelectedLecturer(e.target.value)}
                  value={selectedLecturer}
                  className="w-full px-4 py-2 border border-gray-300 rounded-md"
                >
                  <option value="">Chọn Giảng Viên</option>
                  {availableLecturers.map((lecturer) => (
                    <option key={lecturer.MaGV} value={lecturer.MaGV}>
                      {lecturer.HoTenGV} - {lecturer.MaGV}{" "}
                      {/* Hiển thị tên giảng viên và mã giảng viên */}
                    </option>
                  ))}
                </select>
              </div>
              <div className="text-center mt-4">
                <button
                  type="button"
                  onClick={handleAddLecturer}
                  className="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700"
                >
                  Thêm
                </button>

                <button
                  type="button"
                  onClick={() => setIsAddLecturerModalOpen(false)}
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

export default ProductLecturer;
