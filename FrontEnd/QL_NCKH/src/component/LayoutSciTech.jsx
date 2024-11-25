import React, { useState, useEffect, useRef } from "react";
import {
  FaGift,
  FaUpload,
  FaComment,
  FaChartBar,
  FaBars,
  FaTimes,
  FaSignOutAlt,
  FaBook,
  FaClipboardList,
} from "react-icons/fa";
import { GoBell } from "react-icons/go";
import { CiUser } from "react-icons/ci";
import { Link } from "react-router-dom";
import LogoHUIT from "../assets/logohuitt.png";

const LayoutSciTech = ({ children }) => {
  const [sidebarToggle, setSidebarToggle] = useState(false);
  const [userInfo, setUserInfo] = useState({ name: "", role: "" });
  const [isLogoutModalOpen, setIsLogoutModalOpen] = useState(false);

  const sidebarRef = useRef();

  const handleClickOutside = (event) => {
    if (sidebarRef.current && !sidebarRef.current.contains(event.target)) {
      setSidebarToggle(false);
    }
  };
  const handleLogout = () => {
    // Xóa thông tin người dùng từ localStorage
    localStorage.removeItem("userName");
    localStorage.removeItem("userRole");

    // Điều hướng đến trang login (nếu sử dụng React Router)
    window.location.href = "/"; // Hoặc dùng navigate trong react-router-dom
  };

  useEffect(() => {
    // Khi component mount, lấy thông tin người dùng từ localStorage
    const storedUserName = localStorage.getItem("userName");
    const storedUserRole = localStorage.getItem("userRole");

    if (storedUserName && storedUserRole) {
      setUserInfo({ name: storedUserName, role: storedUserRole });
    }

    document.addEventListener("mousedown", handleClickOutside);
    return () => {
      document.removeEventListener("mousedown", handleClickOutside);
    };
  }, []);

  return (
    <div className="flex h-screen overflow-hidden bg-gray-100">
      {/* Sidebar with border */}
      <div
        ref={sidebarRef}
        className={`h-screen w-72 bg-[#ffffff] border-r border-gray-300 text-gray-700 lg:static absolute z-20 ${
          sidebarToggle ? "translate-x-0" : "-translate-x-full"
        } lg:translate-x-0 transition-all duration-300`}
      >
        <div className="flex p-4 text-center text-2xl font-bold justify-between items-center ">
          <img src={LogoHUIT} alt="Admin Dashboard Logo" className="h-10" />
          <div
            className="text-gray-500 block border border-gray-500 lg:hidden p-2 rounded-lg"
            onClick={() => setSidebarToggle(false)}
          >
            <FaTimes />
          </div>
        </div>
        <div className="py-10 px-5">
          <ul className="space-y-6">
            <li className="flex items-center space-x-3 hover:bg-[#8AADE0] text-black hover:text-[#419a7c] p-2 rounded">
              <Link to="/" className="flex items-center space-x-3">
                <FaChartBar />
                <span className="font-semibold">Dashboard LayoutSciTech</span>
              </Link>
            </li>
            <li className="flex items-center space-x-3 hover:bg-[#8AADE0] text-black hover:text-[#419a7c] p-2 rounded">
              <Link
                to="/article-review"
                className="flex items-center space-x-3"
              >
                <FaBook />
                <span className="font-semibold">Article Review</span>
              </Link>
            </li>
            <li className="flex items-center space-x-3 hover:bg-[#8AADE0] text-black hover:text-[#419a7c] p-2 rounded">
              <Link
                to="lecturer-topic-approval"
                className="flex items-center space-x-3"
              >
                <FaClipboardList />
                <span className="font-semibold">Lecturer Topic Approva</span>
              </Link>
            </li>
            <li className="flex items-center space-x-3 hover:bg-[#8AADE0] text-black hover:text-[#419a7c] p-2 rounded">
              <Link
                to="/student-topic-approval"
                className="flex items-center space-x-3"
              >
                <FaClipboardList />
                <span className="font-semibold">Student Topic Approval</span>
              </Link>
            </li>
            <li className="flex items-center space-x-3 hover:bg-[#8AADE0] text-black hover:text-[#419a7c] p-2 rounded">
              <Link
                to="/science-seminar"
                className="flex items-center space-x-3"
              >
                <FaGift />
                <span className="font-semibold"> Science Seminar</span>
              </Link>
            </li>
            <li className="flex items-center space-x-3 hover:bg-[#8AADE0] text-black hover:text-[#419a7c] p-2 rounded">
              <Link
                to="/content-uploaded"
                className="flex items-center space-x-3"
              >
                <FaUpload />
                <span className="font-semibold">ádsa sad</span>
              </Link>
            </li>
            <li className="flex items-center space-x-3 hover:bg-[#8AADE0] text-black hover:text-[#419a7c] p-2 rounded">
              <Link to="/cmt-manage" className="flex items-center space-x-3">
                <FaComment />
                <span className="font-semibold">ádsa</span>
              </Link>
            </li>
            <hr className="border-gray-400 my-4" />

            <li className="flex items-center space-x-3 hover:bg-[#8AADE0] p-2 rounded hover:text-[#d95959]">
              <Link to="/logout" className="flex items-center space-x-3 mt">
                <FaSignOutAlt />
                <span className="font-semibold">Logout</span>
              </Link>
            </li>
          </ul>
        </div>
      </div>

      {/* Main content area */}
      <div className="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
        {/* Header with border */}
        <div className="sticky top-0 z-10 w-full bg-white shadow px-8 lg:px-4 py-5 flex justify-between items-center border-b border-gray-300">
          <div className="flex items-center space-x-4">
            <div
              className="block lg:hidden rounded-sm border border-stroke p-2 shadow-sm text-2xl rounded-xl"
              onClick={() => setSidebarToggle(true)}
            >
              <FaBars />
            </div>
          </div>
          <div className="flex items-center gap-8 h-full mr-4">
            <div className="relative h-5/6 p-1.5 border border-[#e2e8f0] rounded-full bg-[#ffffff] group">
              <GoBell className="text-xl text-gray-600 h-full w-full group-hover:text-[#3c50e0]" />
              <span className="absolute -top-0.5 right-0 z-1 h-2 w-2 rounded-full bg-red-500 inline">
                <span className="absolute -z-1 inline-flex h-full w-full animate-ping rounded-full bg-red-300"></span>
              </span>
            </div>

            <div className="flex gap-4 h-full">
              <div className="text-right">
                <p className="text-sm font-semibold">{userInfo.name}</p>
                <p className="text-xs font-semibold">{userInfo.role}</p>
              </div>
              <div
                className="relative h-full p-1.5 border border-[#e2e8f0] rounded-full bg-[#eff4fb] group"
                onClick={() => setIsLogoutModalOpen(true)}
              >
                <CiUser className="text-xl text-gray-600 h-full flex w-full" />
              </div>

              {/* Modal */}
              {isLogoutModalOpen && (
                <div className="fixed inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center z-30">
                  <div className="bg-white p-6 rounded-lg shadow-lg w-96">
                    <h2 className="text-lg font-semibold mb-4">
                      Are you sure you want to logout?
                    </h2>
                    <div className="flex justify-end space-x-4">
                      <button
                        onClick={() => setIsLogoutModalOpen(false)}
                        className="px-4 py-2 bg-gray-300 rounded-lg"
                      >
                        Cancel
                      </button>
                      <button
                        onClick={handleLogout}
                        className="px-4 py-2 bg-red-600 text-white rounded-lg"
                      >
                        Logout
                      </button>
                    </div>
                  </div>
                </div>
              )}
            </div>
          </div>
        </div>

        <div className="flex-1 bg-[#e4e4e4] p-8">{children}</div>
      </div>
    </div>
  );
};

export default LayoutSciTech;
