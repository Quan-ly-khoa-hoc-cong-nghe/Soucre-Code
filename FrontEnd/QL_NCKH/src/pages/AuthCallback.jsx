import React, { useEffect } from 'react';

const AuthCallback = () => {
  useEffect(() => {
    // Lấy mã xác thực từ URL
    const urlParams = new URLSearchParams(window.location.search);
    const authCode = urlParams.get('code');

    if (authCode) {
      // Gửi mã xác thực tới backend hoặc Google API để lấy token
      fetch('http://localhost:5000/oauth2callback', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ code: authCode }),
      })
        .then(response => response.json())
        .then(data => {
          console.log('Access token:', data.access_token);
          // Lưu token hoặc điều hướng người dùng sau khi đăng nhập thành công
        })
        .catch(err => {
          console.error('Error getting access token:', err);
        });
    }
  }, []);

  return (
    <div>
      <h1>Đang xử lý đăng nhập...</h1>
    </div>
  );
};

export default AuthCallback;
