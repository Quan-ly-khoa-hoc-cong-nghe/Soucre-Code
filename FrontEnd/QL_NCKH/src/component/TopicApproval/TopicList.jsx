import { useEffect, useState } from 'react';
import { FaCheck, FaTimes, FaEye } from 'react-icons/fa';

const fetchTopics = async () => {
  return [
    {
      id: 1,
      name: 'Nghiên cứu về AI',
      studentName: 'Nguyễn Văn A',
      advisorName: 'PGS. TS. Trần Văn B',
      status: 'Khoa đã duyệt',
      description: 'Nghiên cứu ứng dụng AI trong giáo dục.',
    },
    {
      id: 2,
      name: 'Phát triển hệ thống IoT',
      studentName: 'Trần Thị C',
      advisorName: 'TS. Nguyễn Thị D',
      status: 'Khoa đã duyệt',
      description: 'Hệ thống IoT cho nông nghiệp thông minh.',
    },
    {
        id: 3,
        name: 'Phát triển hệ thống IoT',
        studentName: 'Trần Thị C',
        advisorName: 'TS. Nguyễn Thị D',
        status: 'Khoa đã duyệt',
        description: 'Hệ thống IoT cho nông nghiệp thông minh.',
      },
      {
        id: 4,
        name: 'Phát triển hệ thống IoT',
        studentName: 'Trần Thị C',
        advisorName: 'TS. Nguyễn Thị D',
        status: 'Khoa đã duyệt',
        description: 'Hệ thống IoT cho nông nghiệp thông minh.',
      },
      {
        id: 5,
        name: 'Phát triển hệ thống IoT',
        studentName: 'Trần Thị C',
        advisorName: 'TS. Nguyễn Thị D',
        status: 'Khoa đã duyệt',
        description: 'Hệ thống IoT cho nông nghiệp thông minh.',
      },
      {
        id: 6,
        name: 'Phát triển hệ thống IoT',
        studentName: 'Trần Thị C',
        advisorName: 'TS. Nguyễn Thị D',
        status: 'Khoa đã duyệt',
        description: 'Hệ thống IoT cho nông nghiệp thông minh.',
      },
      {
        id: 7,
        name: 'Phát triển hệ thống IoT',
        studentName: 'Trần Thị C',
        advisorName: 'TS. Nguyễn Thị D',
        status: 'Khoa đã duyệt',
        description: 'Hệ thống IoT cho nông nghiệp thông minh.',
      },
      {
        id: 8,
        name: 'Phát triển hệ thống IoT',
        studentName: 'Trần Thị C',
        advisorName: 'TS. Nguyễn Thị D',
        status: 'Khoa đã duyệt',
        description: 'Hệ thống IoT cho nông nghiệp thông minh.',
      },

    // Thêm các đề tài khác nếu cần
  ];
};

const TopicList = () => {
  const [topics, setTopics] = useState([]);

  useEffect(() => {
    const getTopics = async () => {
      const data = await fetchTopics();
      setTopics(data);
    };
    getTopics();
  }, []);

  const handleApprove = (id) => {
    setTopics((prev) =>
      prev.map((topic) =>
        topic.id === id ? { ...topic, status: 'Đã duyệt' } : topic
      )
    );
  };

  const handleReject = (id) => {
    setTopics((prev) =>
      prev.map((topic) => (topic.id === id ? { ...topic, status: 'Hủy' } : topic))
    );
  };

  const handleViewDetails = (id) => {
    alert(`Viewing details for topic ID: ${id}`);
  };

  return (
    <div className="bg-white rounded-lg shadow p-6">
      <table className="w-full">
        <thead>
          <tr className="border-b">
            <th className="text-left py-4 px-2">Topic Name</th>
            <th className="text-left py-4 px-2">Student</th>
            <th className="text-left py-4 px-2">Advisor</th>
            <th className="text-left py-4 px-2">Status</th>
            <th className="text-right py-4 px-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          {topics.map((topic) => (
            <tr key={topic.id} className="border-b hover:bg-gray-50">
              <td className="py-4 px-2">{topic.name}</td>
              <td className="py-4 px-2">{topic.studentName}</td>
              <td className="py-4 px-2">{topic.advisorName}</td>
              <td className="py-4 px-2">
                <span
                  className={`px-2 py-1 rounded-full text-sm ${
                    topic.status === 'Đã duyệt'
                      ? 'bg-green-100 text-green-800'
                      : topic.status === 'Hủy'
                      ? 'bg-red-100 text-red-800'
                      : 'bg-blue-100 text-blue-800'
                  }`}
                >
                  {topic.status}
                </span>
              </td>
              <td className="py-4 px-2 text-right">
                <div className="flex justify-end space-x-2">
                  {topic.status === 'Khoa đã duyệt' && (
                    <>
                      <button
                        onClick={() => handleApprove(topic.id)}
                        className="p-2 text-green-600 hover:bg-green-100 rounded-full"
                        title="Chấp nhận"
                      >
                        <FaCheck className="w-5 h-5" />
                      </button>
                      <button
                        onClick={() => handleReject(topic.id)}
                        className="p-2 text-red-600 hover:bg-red-100 rounded-full"
                        title="Hủy"
                      >
                        <FaTimes className="w-5 h-5" />
                      </button>
                    </>
                  )}
                  <button
                    onClick={() => handleViewDetails(topic.id)}
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
    </div>
  );
};

export default TopicList;
