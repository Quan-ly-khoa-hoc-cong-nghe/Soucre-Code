import React, { useState, useEffect, useRef } from "react";
import {
  FaUser,
  FaMusic,
  FaLaptopCode,
  FaBox,
  FaUserFriends,
  FaComment,
  FaChartBar,
  FaBars,
  FaTimes,
  FaSignOutAlt,
  FaBook,
  FaClipboardList,
  FaTachometerAlt,
  FaUserEdit,
  FaUserPlus,
  FaRegHandshake,
  FaCheckCircle,
  FaClipboardCheck,
  FaUserCircle,
} from "react-icons/fa";

import { GoBell } from "react-icons/go";
import { CiUser } from "react-icons/ci";
import { Link } from "react-router-dom";
import LogoHUIT from "../assets/logohuitt.png";

const Layout = ({ children }) => {
  const [sidebarToggle, setSidebarToggle] = useState(false);
  const [userInfo, setUserInfo] = useState({ name: "", role: "" });
  const [userMenuOpen, setUserMenuOpen] = useState(false);
  const [isLogoutModalOpen, setIsLogoutModalOpen] = useState(false);

  const sidebarRef = useRef();

  const handleClickOutside = (event) => {
    if (sidebarRef.current && !sidebarRef.current.contains(event.target)) {
      setSidebarToggle(false);
    }
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
  const handleLogout = () => {
    // Xóa thông tin người dùng từ localStorage
    localStorage.removeItem("userName");
    localStorage.removeItem("userRole");

    // Điều hướng đến trang login (nếu sử dụng React Router)
    window.location.href = "/"; // Hoặc dùng navigate trong react-router-dom
  };

  return (
    <div className="flex h-screen overflow-hidden bg-gray-100">
      {/* Sidebar with border */}
      <div
        ref={sidebarRef}
        className={`h-screen w-72 bg-[#ffffff] border-r border-gray-300 text-gray-700 lg:static absolute z-20 ${
          sidebarToggle ? "translate-x-0" : "-translate-x-full"
        } lg:translate-x-0 transition-all duration-300`}
      >
        <div className="flex p-4 text-center text-2xl font-bold justify-between items-center">
          <img src={LogoHUIT} alt="Admin Dashboard Logo" className="h-10" />
          <div
            className="text-gray-500 block border border-gray-500 lg:hidden p-2 rounded-lg"
            onClick={() => setSidebarToggle(false)}
          >
            <FaTimes />
          </div>
        </div>
        <div className="py-10 px-5">
          <ul className="space-y-6 max-h-[calc(100vh-4rem)] overflow-y-auto">
            <li className="flex items-center space-x-3 hover:bg-[#8AADE0] text-black hover:text-[#419a7c] p-2 rounded">
              <Link to="/" className="flex items-center space-x-3">
                <FaChartBar />
                <span className="font-semibold">Dashboard</span>
              </Link>
            </li>
            <li className="flex items-center space-x-3 hover:bg-[#8AADE0] text-black hover:text-[#419a7c] p-2 rounded">
              <Link
                to="/admin/user"
                className="flex items-center space-x-3"
              >
                <FaUserFriends />
                <span className="font-semibold">Quản lý tài khoản </span>
              </Link>
            </li>
            <li className="flex items-center space-x-3 hover:bg-[#8AADE0] text-black hover:text-[#419a7c] p-2 rounded">
              <Link
                to="/admin/lecturer-topic-approval"
                className="flex items-center space-x-3"
              >
                <FaClipboardList />
                <span className="font-semibold">Chi tiết đề tài giảng viên</span>
              </Link>
            </li>
            <li className="flex items-center space-x-3 hover:bg-[#8AADE0] text-black hover:text-[#419a7c] p-2 rounded">
              <Link
                to="/admin/kehoach"
                className="flex items-center space-x-3"
              >
                <FaClipboardList />
                <span className="font-semibold">Kế hoạch nckh giảng viên</span>
              </Link>
            </li>
            <li className="flex items-center space-x-3 hover:bg-[#8AADE0] text-black hover:text-[#419a7c] p-2 rounded">
              <Link
                to="/admin/sanphamngoaitruong"
                className="flex items-center space-x-3"
              >
                <FaClipboardList />
                <span className="font-semibold">Quản lý sản phẩm ngoài trường</span>
              </Link>
            </li>
            <li className="flex items-center space-x-3 hover:bg-[#8AADE0] text-black hover:text-[#419a7c] p-2 rounded">
              <Link
                to="/admin/quanlydetaicapso"
                className="flex items-center space-x-3"
              >
                <FaClipboardList />
                <span className="font-semibold">Quản lý đề tài cấp sở</span>
              </Link>
            </li>
            <li className="flex items-center space-x-3 hover:bg-[#8AADE0] text-black hover:text-[#419a7c] p-2 rounded">
              <Link
                to="/admin/sanphamcapso"
                className="flex items-center space-x-3"
              >
                <FaClipboardList />
                <span className="font-semibold">Quản lý sản phẩm cấp sở</span>
              </Link>
            </li>
            <li className="flex items-center space-x-3 hover:bg-[#8AADE0] text-black hover:text-[#419a7c] p-2 rounded">
              <Link
                to="/admin/student-topic-approval"
                className="flex items-center space-x-3"
              >
                <FaClipboardList />
                <span className="font-semibold">Chi tiết đề tài sinh viên</span>
              </Link>
            </li>
            <li className="flex items-center space-x-3 hover:bg-[#8AADE0] text-black hover:text-[#419a7c] p-2 rounded">
              <Link
                to="/admin/edit-student"
                className="flex items-center space-x-3"
              >
                <FaUserFriends />
                <span className="font-semibold">Quản lý thành viên đề tài cấp sở</span>
              </Link>
            </li>
            <li className="flex items-center space-x-3 hover:bg-[#8AADE0] text-black hover:text-[#419a7c] p-2 rounded">
              <Link
                to="/admin/nhomdetaicapso"
                className="flex items-center space-x-3"
              >
                <FaUserFriends />
                <span className="font-semibold">Quản lý thành viên đề tài cấp sở</span>
              </Link>
            </li>
            <li className="flex items-center space-x-3 hover:bg-[#8AADE0] text-black hover:text-[#419a7c] p-2 rounded">
              <Link
                to="/admin/phienhoithao"
                className="flex items-center space-x-3"
              >
                <FaLaptopCode />
                <span className="font-semibold">Phiên hội thảo khoa học</span>
              </Link>
            </li>
            <li className="flex items-center space-x-3 hover:bg-[#8AADE0] text-black hover:text-[#419a7c] p-2 rounded">
              <Link
                to="/admin/hoithao"
                className="flex items-center space-x-3"
              >
                <FaLaptopCode />
                <span className="font-semibold">Hội thảo khoa học</span>
              </Link>
            </li>
            <li className="flex items-center space-x-3 hover:bg-[#8AADE0] text-black hover:text-[#419a7c] p-2 rounded">
              <Link
                to="/admin/edit-Lecturer"
                className="flex items-center space-x-3"
              >
                <FaUserFriends />
                <span className="font-semibold">Quản lý nhóm giảng viên NCKH</span>
              </Link>
            </li>
            <li className="flex items-center space-x-3 hover:bg-[#8AADE0] text-black hover:text-[#419a7c] p-2 rounded">
              <Link
                to="/admin/nguoithamgia"
                className="flex items-center space-x-3"
              >
                <FaUserFriends />
                <span className="font-semibold">Người tham gia hội thảo</span>
              </Link>
            </li>
            <li className="flex items-center space-x-3 hover:bg-[#8AADE0] text-black hover:text-[#419a7c] p-2 rounded">
              <Link
                to="/admin/nhataitro"
                className="flex items-center space-x-3"
              >
                <FaUserFriends />
                <span className="font-semibold">Nhà tài trợ hội thảo</span>
              </Link>
            </li>
            <li className="flex items-center space-x-3 hover:bg-[#8AADE0] text-black hover:text-[#419a7c] p-2 rounded">
              <Link
                to="/admin/vaitrohoithao"
                className="flex items-center space-x-3"
              >
                <FaUserFriends />
                <span className="font-semibold">Vai trò trong hội thảo</span>
              </Link>
            </li>
            <li className="flex items-center space-x-3 hover:bg-[#8AADE0] text-black hover:text-[#419a7c] p-2 rounded">
              <Link
                to="/admin/tailieu"
                className="flex items-center space-x-3"
              >
                <FaUserFriends />
                <span className="font-semibold">Thêm tài liệu hội thảo</span>
              </Link>
            </li>
            <li className="flex items-center space-x-3 hover:bg-[#8AADE0] text-black hover:text-[#419a7c] p-2 rounded">
              <Link
                to="/admin/nhomduannt"
                className="flex items-center space-x-3"
              >
                <FaUserFriends />
                <span className="font-semibold">Quản lý nhóm dự án ngoài trường</span>
              </Link>
            </li>
            <li className="flex items-center space-x-3 hover:bg-[#8AADE0] text-black hover:text-[#419a7c] p-2 rounded">
              <Link
                to="/admin/edit-student"
                className="flex items-center space-x-3"
              >
                <FaUserFriends />
                <span className="font-semibold">Chỉnh nhóm sinh viên</span>
              </Link>
            </li>
            <li className="flex items-center space-x-3 hover:bg-[#8AADE0] text-black hover:text-[#419a7c] p-2 rounded">
              <Link
                to="/admin/product-manager"
                className="flex items-center space-x-3"
              >
                <FaBox />
                <span className="font-semibold">Quản lý sản phẩm sinh viên</span>
              </Link>
            </li>
            <li className="flex items-center space-x-3 hover:bg-[#8AADE0] text-black hover:text-[#419a7c] p-2 rounded">
              <Link
                to="/admin/product-lecturer"
                className="flex items-center space-x-3"
              >
                <FaBox />
                <span className="font-semibold">Quản lý sản phẩm giảng viên</span>
              </Link>
            </li>
            <li className="flex items-center space-x-3 hover:bg-[#8AADE0] text-black hover:text-[#419a7c] p-2 rounded">
              <Link
                to="/admin/report"
                className="flex items-center space-x-3"
              >
                <FaBox />
                <span className="font-semibold">Quản lý báo cáo định kỳ</span>
              </Link>
            </li>
            <li className="flex items-center space-x-3 hover:bg-[#8AADE0] text-black hover:text-[#419a7c] p-2 rounded">
              <Link
                to="/admin/thamdinhbaibao"
                className="flex items-center space-x-3"
              >
                <FaClipboardList />
                <span className="font-semibold">Thẩm định bài báo</span>
              </Link>
            </li>
            <li className="flex items-center space-x-3 hover:bg-[#8AADE0] text-black hover:text-[#419a7c] p-2 rounded">
              <Link
                to="/admin/dondathang"
                className="flex items-center space-x-3"
              >
                <FaClipboardList />
                <span className="font-semibold">Quản lý đơn đặt hàng ngoài trường</span>
              </Link>
            </li>
            
            <li className="flex items-center space-x-3 hover:bg-[#8AADE0] text-black hover:text-[#419a7c] p-2 rounded">
              <Link
                to="/admin/application-approval"
                className="flex items-center space-x-3"
              >
                <FaCheckCircle />
                <span className="font-semibold">
                  Duyệt hồ sơ NCKHSV Role khoa
                </span>
              </Link>
            </li>
            <li className="flex items-center space-x-3 hover:bg-[#8AADE0] text-black hover:text-[#419a7c] p-2 rounded">
              <Link
                to="/admin/application-approval-admin"
                className="flex items-center space-x-3"
              >
                <FaCheckCircle />
                <span className="font-semibold">
                  Duyệt hồ sơ thêm đề tài role KHCNSV
                </span>
              </Link>
            </li>
            <li className="flex items-center space-x-3 hover:bg-[#8AADE0] text-black hover:text-[#419a7c] p-2 rounded">
              <Link
                to="/admin/lecturer-application-approval-admin"
                className="flex items-center space-x-3"
              >
                <FaCheckCircle />
                <span className="font-semibold">
                  Duyệt hồ sơ NCKHGV role KHCN
                </span>
              </Link>
            </li>
            <li className="flex items-center space-x-3 hover:bg-[#8AADE0] text-black hover:text-[#419a7c] p-2 rounded">
              <Link
                to="/admin/lecturer-application-approval-list-admin"
                className="flex items-center space-x-3"
              >
                <FaCheckCircle />
                <span className="font-semibold">
                  Duyệt hồ sơ NCKHGV role khoa
                </span>
              </Link>
            </li>

            <li className="flex items-center space-x-3 hover:bg-[#8AADE0] text-black hover:text-[#419a7c] p-2 rounded">
              <Link
                to="/admin/science-seminar-departments"
                className="flex items-center space-x-3"
              >
                <FaCheckCircle />
                <span className="font-semibold">
                  Duyệt hồ sơ hội thảo role khoa
                </span>
              </Link>
            </li>
            <li className="flex items-center space-x-3 hover:bg-[#8AADE0] text-black hover:text-[#419a7c] p-2 rounded">
              <Link
                to="/admin/science-seminar-sciTech"
                className="flex items-center space-x-3"
              >
                <FaCheckCircle />
                <span className="font-semibold">
                  Duyệt hồ sơ và thêm hội thảo
                </span>
              </Link>
            </li>
            <li className="flex items-center space-x-3 hover:bg-[#8AADE0] text-black hover:text-[#419a7c] p-2 rounded">
              <Link
                to="/admin/article-review-department"
                className="flex items-center space-x-3"
              >
                <FaCheckCircle />
                <span className="font-semibold">
                  Duyệt hồ sơ bài báo role Khoa{" "}
                </span>
              </Link>
            </li>
            <li className="flex items-center space-x-3 hover:bg-[#8AADE0] text-black hover:text-[#419a7c] p-2 rounded">
              <Link
                to="/admin/article-review-scitech"
                className="flex items-center space-x-3"
              >
                <FaCheckCircle />
                <span className="font-semibold">
                  Duyệt hồ sơ thêm bài báo role KHCN
                </span>
              </Link>
            </li>
            <li className="flex items-center space-x-3 hover:bg-[#8AADE0] text-black hover:text-[#419a7c] p-2 rounded">
              <Link
                to="/admin/approval-of-school-topic-department"
                className="flex items-center space-x-3"
              >
                <FaCheckCircle />
                <span className="font-semibold">
                  Duyệt hồ sơ DTCS role khoa
                </span>
              </Link>
            </li>
            <li className="flex items-center space-x-3 hover:bg-[#8AADE0] text-black hover:text-[#419a7c] p-2 rounded">
              <Link
                to="/admin/approval-of-school-topic-sciTech"
                className="flex items-center space-x-3"
              >
                <FaCheckCircle />
                <span className="font-semibold">Duyệt hồ sơ và thêm DTCS</span>
              </Link>
            </li>
            <li className="flex items-center space-x-3 hover:bg-[#8AADE0] text-black hover:text-[#419a7c] p-2 rounded">
              <Link
                to="/admin/approval-off-campus-project-department"
                className="flex items-center space-x-3"
              >
                <FaCheckCircle />
                <span className="font-semibold">
                  Duyệt hồ sơ NCNT role khoa
                </span>
              </Link>
            </li>
            <li className="flex items-center space-x-3 hover:bg-[#8AADE0] text-black hover:text-[#419a7c] p-2 rounded">
              <Link
                to="/admin/approval-off-campus-project-scitech"
                className="flex items-center space-x-3"
              >
                <FaCheckCircle />
                <span className="font-semibold">Duyệt hồ sơ và thêm NCNT</span>
              </Link>
            </li>

            <hr className="border-gray-400 my-4" />

            <li className="flex items-center space-x-3 hover:bg-[#8AADE0] p-2 rounded hover:text-[#d95959]"></li>
            
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

export default Layout;
