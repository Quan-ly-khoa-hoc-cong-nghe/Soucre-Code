import bgLogin from '../../assets/bg_login.png';

const LoginLayout = () => {
  return (
    <div
      className="min-h-screen flex items-center justify-center bg-cover bg-center"
      style={{
        backgroundImage: `url(${bgLogin})`,
        backgroundPosition: "center",
      }}
    >
      {/* Left side for background image */}
      <div className="flex-1"></div>

      {/* Right side for the login form */}
      <div className="w-full max-w-lg bg-white bg-opacity-80 p-12 rounded-lg shadow-lg mr-12 border border-gray-300">
        <h1 className="text-4xl font-bold mb-12 text-center">Welcome Back!</h1>
        
        <form>
          <label className="block mb-4 text-lg font-medium text-gray-700">Username:</label>
          <input
            type="text"
            className="w-full p-3 mb-6 border border-blue-300 rounded focus:outline-none focus:border-blue-600"
            placeholder="Enter your username"
          />

          <label className="block mb-4 text-lg font-medium text-gray-700">Password:</label>
          <input
            type="password"
            className="w-full p-3 mb-6 border border-blue-300 rounded focus:outline-none focus:border-blue-600"
            placeholder="Enter your password"
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
