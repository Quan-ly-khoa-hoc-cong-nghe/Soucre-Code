import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import axios from 'axios';
import bgLogin from '../../assets/bg_login.png';
import Layout from '../Layout';
import LayoutDepartment from '../LayoutDepartment';
import LayoutSciTech from '../LayoutSciTech';

const LoginLayout = () => {
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [userData, setUserData] = useState(null); // Dùng để lưu thông tin người dùng đã đăng nhập
  const navigate = useNavigate();

  const handleLogin = async (e) => {
    e.preventDefault();
  
    try {
      const response = await axios.post('http://localhost/Soucre-Code/BackEnd/Api/DangNhap_Api.php?action=login', {
        MaNguoiDung: username,
        MatKhau: password
      });
  
      console.log('Response from API:', response);
  
      if (response.data.status === 'success') {
        const user = response.data.data;
  
        localStorage.setItem('isAuthenticated', 'true');
        localStorage.setItem('userRole', user.VaiTro);
        localStorage.setItem('userName', user.MaNguoiDung);
  
        setUserData(user);
  
        if (user.VaiTro === "Admin") {
          navigate('/admin');
        } else if (user.VaiTro === "Khoa") {
          navigate('/department');
        } else if (user.VaiTro === "PhongNCKH") {
          navigate('/scitech');
        }
      } else {
        setError(response.data.message || 'Thông tin đăng nhập không đúng');
      }
    } catch (error) {
      console.error('Lỗi khi gọi API:', error);
      setError(`Đã xảy ra lỗi khi đăng nhập. Chi tiết: ${error.message || error}`);
    }
  };
  
  useEffect(() => {
    // Khi component load, lấy thông tin người dùng từ localStorage nếu có
    const storedUserName = localStorage.getItem('userName');
    const storedUserRole = localStorage.getItem('userRole');
    if (storedUserName && storedUserRole) {
      setUserData({ MaNguoiDung: storedUserName, VaiTro: storedUserRole });
    }
  }, []);

  return (
    <div
      className="min-h-screen flex items-center justify-center bg-cover bg-center"
      style={{
        backgroundImage: `url(${bgLogin})`,
        backgroundPosition: 'center',
      }}
    >
      <div className="flex-1"></div>

      <div className="w-full max-w-lg bg-white bg-opacity-80 p-12 rounded-lg shadow-lg mr-12 border border-gray-300">
        <h1 className="text-4xl font-bold mb-12 text-center">Welcome Back!</h1>

        {/* Display error message */}
        {error && (
          <div className="bg-red-100 text-red-700 p-3 mb-6 rounded">
            {error}
          </div>
        )}

        <form onSubmit={handleLogin}>
          <label className="block mb-4 text-lg font-medium text-gray-700">Username:</label>
          <input
            type="text"
            className="w-full p-3 mb-6 border border-blue-300 rounded focus:outline-none focus:border-blue-600"
            placeholder="Enter your username"
            value={username}
            onChange={(e) => setUsername(e.target.value)}
            required
          />

          <label className="block mb-4 text-lg font-medium text-gray-700">Password:</label>
          <input
            type="password"
            className="w-full p-3 mb-6 border border-blue-300 rounded focus:outline-none focus:border-blue-600"
            placeholder="Enter your password"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            required
          />

          <button
            type="submit"
            className="w-full bg-blue-600 text-white py-3 rounded hover:bg-blue-700 transition duration-200 mb-8"
          >
            Login
          </button>
        </form>

        <p className="text-center text-sm text-gray-600">
          Don't remember your password? <a href="#" className="text-blue-600 hover:underline">Reset it</a>
        </p>
      </div>
    </div>
  );
};

export default LoginLayout;