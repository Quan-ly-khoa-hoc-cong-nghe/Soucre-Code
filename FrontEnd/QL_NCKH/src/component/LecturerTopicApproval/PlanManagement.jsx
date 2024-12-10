import React, { useState, useEffect } from "react";
import { FaEye, FaPlus, FaTrash } from "react-icons/fa";

const PlanManagement = () => {
  const [projects, setProjects] = useState([]); // State to hold project data
  const [selectedProject, setSelectedProject] = useState(null); // State to hold selected project
  const [plans, setPlans] = useState([]); // State to hold plan data for the selected project
  const [isModalOpen, setIsModalOpen] = useState(false); // State to handle modal visibility
  const [isDetailModalOpen, setIsDetailModalOpen] = useState(false); // State to handle detail modal visibility
  const [selectedPlan, setSelectedPlan] = useState(null); // State to hold selected plan details
  const [newPlan, setNewPlan] = useState({
    MaKeHoachNCKHGV: "",
    NgayBatDau: "",
    NgayKetThuc: "",
    KinhPhi: "",
    FileKeHoach: "",
  }); // State to hold new plan form data

  // Fetch project data from DeTai API
  useEffect(() => {
    const fetchProjects = async () => {
      try {
        const response = await fetch(
          "http://localhost/Soucre-Code/BackEnd/Api/DeTaiNCKHGiangVien_Api/DeTaiNCKHGiangVien_Api.php?action=GET"
        );
        const data = await response.json();
        setProjects(data);
      } catch (error) {
        console.error("Error fetching projects", error);
      }
    };
    fetchProjects();
  }, []);

  // Fetch plans based on selected project
  useEffect(() => {
    if (selectedProject) {
      const fetchPlans = async () => {
        try {
          const response = await fetch(
            "http://localhost/Soucre-Code/BackEnd/Api/DeTaiNCKHGiangVien_Api/KeHoachNCKHGV_Api.php?action=GET"
          );
          const data = await response.json();
          // Filter plans for the selected project
          const filteredPlans = data.filter(
            (plan) => plan.MaDeTaiNCKHGV === selectedProject
          );
          setPlans(filteredPlans);
        } catch (error) {
          console.error("Error fetching plans", error);
        }
      };
      fetchPlans();
    }
  }, [selectedProject]);

  // Function to close modals
  const closeModal = () => {
    setIsModalOpen(false);
    setIsDetailModalOpen(false);
    setSelectedProject(null); // Reset the selected project when closing
    setSelectedPlan(null); // Reset the selected plan when closing the detail modal
  };

  // Function to open modal for adding plan
  const openModal = (projectCode) => {
    setSelectedProject(projectCode);
    setIsModalOpen(true);
  };

  // Function to open detail modal with selected plan
  const openDetailModal = async (planCode) => {
    try {
      const response = await fetch(
        "http://localhost/Soucre-Code/BackEnd/Api/DeTaiNCKHGiangVien_Api/KeHoachNCKHGV_Api.php?action=GET"
      );
      const data = await response.json();
      const plan = data.find((plan) => plan.MaKeHoachNCKHGV === planCode);
      setSelectedPlan(plan);
      setIsDetailModalOpen(true);
    } catch (error) {
      console.error("Error fetching plan details", error);
    }
  };

  // Handle adding a new plan
  const handleAddPlan = async () => {
    try {
      const response = await fetch(
        "http://localhost/Soucre-Code/BackEnd/Api/DeTaiNCKHGiangVien_Api/KeHoachNCKHGV_Api.php?action=ADD", // Adjust API endpoint if needed
        {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(newPlan),
        }
      );
      const result = await response.json();
      if (result.success) {
        // Refresh plans after adding
        setPlans((prevPlans) => [...prevPlans, newPlan]);
        setNewPlan({
          MaKeHoachNCKHGV: "",
          NgayBatDau: "",
          NgayKetThuc: "",
          KinhPhi: "",
          FileKeHoach: "",
        }); // Reset form
      } else {
        console.error("Error adding plan");
      }
    } catch (error) {
      console.error("Error adding plan", error);
    }
  };

  // Handle deleting a plan
  const handleDeletePlan = async (planCode) => {
    try {
      const response = await fetch(
       " http://localhost/Soucre-Code/BackEnd/Api/DeTaiNCKHGiangVien_Api/KeHoachNCKHGV_Api.php?action=DELETE&MaKeHoachNCKHGV=${planCode}",
        { method: "DELETE" }
      );
      const result = await response.json();
      if (result.success) {
        // Remove the plan from state after deletion
        setPlans((prevPlans) =>
          prevPlans.filter((plan) => plan.MaKeHoachNCKHGV !== planCode)
        );
      } else {
        console.error("Error deleting plan");
      }
    } catch (error) {
      console.error("Error deleting plan", error);
    }
  };
  

  return (
    <div className="p-6">
      {/* Display project list */}
      <div className="mb-6 p-4 border border-blue-500 rounded-lg shadow-md bg-white">
        <h2 className="text-xl font-semibold mb-2 text-blue-600">Quản lý kế hoạch</h2>
        <table className="min-w-full table-auto border-collapse border border-gray-300 mt-4">
          <thead>
            <tr className="bg-gray-100 text-sm text-left">
              <th className="px-4 py-2 border">Mã Đề Tài</th>
              <th className="px-4 py-2 border">Tên Đề Tài</th>
              <th className="px-4 py-2 border">Chọn</th>
            </tr>
          </thead>
          <tbody>
            {projects.map((project) => (
              <tr
                key={project.MaDeTaiNCKHGV}
                className="hover:bg-gray-50 transition duration-300 ease-in-out"
              >
                <td className="px-4 py-3 border-b text-left w-1/5">{project.MaDeTaiNCKHGV}</td>
                <td className="px-4 py-3 border-b text-left w-2/5">{project.TenDeTai}</td>
                <td className="px-4 py-3 border-b text-left w-2/5">
                  <div className="flex space-x-2 justify-start">
                    <button
                      className="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition duration-300 ease-in-out"
                    >
                      Chi tiết kế hoạch
                    </button>
                    <button
                      onClick={() => openModal(project.MaDeTaiNCKHGV)}
                      className="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 transition duration-300 ease-in-out"
                    >
                      Thêm kế hoạch
                    </button>
                    <button
                      onClick={() => handleDeletePlan(project.MaDeTaiNCKHGV)}
                      className="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition duration-300 ease-in-out"
                    >
                      Xóa
                    </button>
                  </div>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>

      {/* Modal to display selected plan details */}
      {isDetailModalOpen && selectedPlan && (
        <div className="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50">
          <div className="bg-white rounded-lg p-6 w-4/5 md:w-1/2 relative">
            <h2 className="text-xl font-semibold mb-2 text-blue-600">
              Chi tiết kế hoạch {selectedPlan.MaKeHoachNCKHGV}
            </h2>
            <button
              onClick={closeModal}
              className="absolute top-2 right-2 text-gray-500 hover:text-black"
            >
              ✖
            </button>
            <div className="mt-4">
              <p><strong>Mã kế hoạch:</strong> {selectedPlan.MaKeHoachNCKHGV}</p>
              <p><strong>Ngày bắt đầu:</strong> {selectedPlan.NgayBatDau}</p>
              <p><strong>Ngày kết thúc:</strong> {selectedPlan.NgayKetThuc}</p>
              <p><strong>Kinh phí:</strong> {selectedPlan.KinhPhi}</p>
              <p><strong>File kế hoạch:</strong> {selectedPlan.FileKeHoach}</p>
              <p><strong>Mã đề tài:</strong> {selectedPlan.MaDeTaiNCKHGV}</p>
            </div>
          </div>
        </div>
      )}

      {/* Modal to add a new plan */}
      {isModalOpen && (
        <div className="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50">
          <div className="bg-white rounded-lg p-6 w-4/5 md:w-1/2 relative">
            <h2 className="text-xl font-semibold mb-2 text-blue-600">
              Thêm kế hoạch mới cho đề tài {selectedProject}
            </h2>
            <button
              onClick={closeModal}
              className="absolute top-2 right-2 text-gray-500 hover:text-black"
            >
              ✖
            </button>
            {/* Form to add new plan */}
            <div className="mt-4">
              <form
                onSubmit={(e) => {
                  e.preventDefault();
                  handleAddPlan();
                }}
              >
                <div className="mb-4">
                  <label className="block text-sm font-medium mb-2">Ngày bắt đầu</label>
                  <input
                    type="date"
                    value={newPlan.NgayBatDau}
                    onChange={(e) =>
                      setNewPlan({ ...newPlan, NgayBatDau: e.target.value })
                    }
                    required
                    className="w-full px-3 py-2 border border-gray-300 rounded"
                  />
                </div>
                <div className="mb-4">
                  <label className="block text-sm font-medium mb-2">Ngày kết thúc</label>
                  <input
                    type="date"
                    value={newPlan.NgayKetThuc}
                    onChange={(e) =>
                      setNewPlan({ ...newPlan, NgayKetThuc: e.target.value })
                    }
                    required
                    className="w-full px-3 py-2 border border-gray-300 rounded"
                  />
                </div>
                <div className="mb-4">
                  <label className="block text-sm font-medium mb-2">Kinh phí</label>
                  <input
                    type="number"
                    value={newPlan.KinhPhi}
                    onChange={(e) =>
                      setNewPlan({ ...newPlan, KinhPhi: e.target.value })
                    }
                    required
                    className="w-full px-3 py-2 border border-gray-300 rounded"
                  />
                </div>
                <div className="mb-4">
                  <label className="block text-sm font-medium mb-2">File kế hoạch</label>
                  <input
                    type="file"
                    onChange={(e) =>
                      setNewPlan({ ...newPlan, FileKeHoach: e.target.files[0]?.name })
                    }
                    required
                    className="w-full px-3 py-2 border border-gray-300 rounded"
                  />
                </div>
                <button
                  type="submit"
                  className="w-full bg-green-500 text-white py-2 rounded hover:bg-green-600 transition duration-300"
                >
                  Thêm kế hoạch
                </button>
              </form>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};

export default PlanManagement;