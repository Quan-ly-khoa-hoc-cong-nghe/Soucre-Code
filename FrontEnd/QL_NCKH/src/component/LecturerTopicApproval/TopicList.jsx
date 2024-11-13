import { useEffect, useState } from 'react';
import { FaCheck, FaTimes, FaEye } from 'react-icons/fa';

const fetchTopics = async () => {
  return [
    {
      id: 1,
      name: 'Nghiên cứu về AI',
      description: 'Nghiên cứu ứng dụng AI trong giáo dục.',
      status: 'Khoa đã duyệt',
      groupId: 'G001',
      students: [
        {
          id: 'S001',
          name: 'Nguyễn Văn A',
          birthDate: '2000-01-01',
          phone: '0123456789',
          email: 'nguyenvana@example.com',
          major: 'Công nghệ thông tin',
        },
        {
          id: 'S002',
          name: 'Trần Thị B',
          birthDate: '2000-02-01',
          phone: '0123456780',
          email: 'tranthib@example.com',
          major: 'Công nghệ thông tin',
        },
      ],
      advisor: {
        id: 'A001',
        name: 'PGS. TS. Trần Văn B',
        department: 'Khoa Công nghệ Thông tin',
        email: 'tranvanb@example.com',
        phone: '0123456780',
      },
      researchPlan: {
        id: 'RP001',
        topicId: 1,
        planId: 'Plan01',
        description: 'Kế hoạch nghiên cứu ứng dụng AI.',
        startDate: '2024-01-01',
        endDate: '2024-12-31',
      },
    },
    {
      id: 2,
      name: 'Phát triển hệ thống IoT',
      description: 'Hệ thống IoT cho nông nghiệp thông minh.',
      status: 'Khoa đã duyệt',
      groupId: 'G002',
      students: [
        {
          id: 'S003',
          name: 'Trần Thị C',
          birthDate: '2000-02-02',
          phone: '0123456790',
          email: 'tranthic@example.com',
          major: 'Kỹ thuật Điện tử',
        },
        {
          id: 'S004',
          name: 'Lê Văn D',
          birthDate: '2000-03-03',
          phone: '0123456791',
          email: 'levand@example.com',
          major: 'Kỹ thuật Điện tử',
        },
        {
          id: 'S005',
          name: 'Nguyễn Văn E',
          birthDate: '2000-04-04',
          phone: '0123456792',
          email: 'nguyenvane@example.com',
          major: 'Kỹ thuật Điện tử',
        },
      ],
      advisor: {
        id: 'A002',
        name: 'TS. Nguyễn Thị D',
        department: 'Khoa Kỹ thuật Điện tử',
        email: 'nguyentd@example.com',
        phone: '0123456781',
      },
      researchPlan: {
        id: 'RP002',
        topicId: 2,
        planId: 'Plan02',
        description: 'Kế hoạch nghiên cứu hệ thống IoT.',
        startDate: '2024-02-01',
        endDate: '2024-11-30',
      },
    },
    {
      id: 3,
      name: 'Ứng dụng Machine Learning trong y tế',
      description: 'Nghiên cứu ứng dụng Machine Learning trong chẩn đoán bệnh.',
      status: 'Khoa đã duyệt',
      groupId: 'G003',
      students: [
        {
          id: 'S006',
          name: 'Lê Văn H',
          birthDate: '2000-03-03',
          phone: '0123456791',
          email: 'levanh@example.com',
          major: 'Công nghệ thông tin',
        },
      ],
      advisor: {
        id: 'A003',
        name: 'TS. Trần Thị H',
        department: 'Khoa Y Dược',
        email: 'tranthih@example.com',
        phone: '0123456782',
      },
      researchPlan: {
        id: 'RP003',
        topicId: 3,
        planId: 'Plan03',
        description: 'Kế hoạch nghiên cứu Machine Learning.',
        startDate: '2024-01-15',
        endDate: '2024-12-15',
      },
    },
    {
      id: 4,
      name: 'Phát triển phần mềm giáo dục',
      description: 'Nghiên cứu phát triển phần mềm hỗ trợ học tập.',
      status: 'Khoa đã duyệt',
      groupId: 'G004',
      students: [
        {
          id: 'S007',
          name: 'Nguyễn Thị I',
          birthDate: '2000-04-04',
          phone: '0123456793',
          email: 'nguyenthi@example.com',
          major: 'Khoa học máy tính',
        },
        {
          id: 'S008',
          name: 'Trần Văn J',
          birthDate: '2000-05-05',
          phone: '0123456794',
          email: 'tranvj@example.com',
          major: 'Khoa học máy tính',
        },
      ],
      advisor: {
        id: 'A004',
        name: 'PGS. TS. Lê Văn M',
        department: 'Khoa Công nghệ Thông tin',
        email: 'levanm@example.com',
        phone: '0123456784',
      },
      researchPlan: {
        id: 'RP004',
        topicId: 4,
        planId: 'Plan04',
        description: 'Kế hoạch phát triển phần mềm giáo dục.',
        startDate: '2024-03-01',
        endDate: '2024-09-30',
      },
    },
    {
      id: 5,
      name: 'Nghiên cứu dữ liệu lớn',
      description: 'Phân tích và nghiên cứu về Big Data.',
      status: 'Khoa đã duyệt',
      groupId: 'G005',
      students: [
        {
          id: 'S009',
          name: 'Trần Văn T',
          birthDate: '2000-05-05',
          phone: '0123456794',
          email: 'tranvt@example.com',
          major: 'Khoa học dữ liệu',
        },
        {
          id: 'S010',
          name: 'Nguyễn Văn K',
          birthDate: '2000-06-06',
          phone: '0123456795',
          email: 'nguyenvank@example.com',
          major: 'Khoa học dữ liệu',
        },
        {
          id: 'S011',
          name: 'Lê Văn L',
          birthDate: '2000-07-07',
          phone: '0123456796',
          email: 'levanl@example.com',
          major: 'Khoa học dữ liệu',
        },
      ],
      advisor: {
        id: 'A005',
        name: 'TS. Nguyễn Văn P',
        department: 'Khoa Khoa học máy tính',
        email: 'nguyenp@example.com',
        phone: '0123456785',
      },
      researchPlan: {
        id: 'RP005',
        topicId: 5,
        planId: 'Plan05',
        description: 'Kế hoạch nghiên cứu dữ liệu lớn.',
        startDate: '2024-04-01',
        endDate: '2024-12-01',
      },
    },
    {
      id: 6,
      name: 'Phát triển ứng dụng di động',
      description: 'Xây dựng ứng dụng di động cho học sinh.',
      status: 'Khoa đã duyệt',
      groupId: 'G006',
      students: [
        {
          id: 'S012',
          name: 'Nguyễn Văn M',
          birthDate: '2000-08-08',
          phone: '0123456798',
          email: 'nguyenm@example.com',
          major: 'Kỹ thuật phần mềm',
        },
      ],
      advisor: {
        id: 'A006',
        name: 'PGS. TS. Hoàng Văn R',
        department: 'Khoa Kỹ thuật phần mềm',
        email: 'hoangvr@example.com',
        phone: '0123456786',
      },
      researchPlan: {
        id: 'RP006',
        topicId: 6,
        planId: 'Plan06',
        description: 'Kế hoạch phát triển ứng dụng di động.',
        startDate: '2024-05-01',
        endDate: '2024-11-30',
      },
    },
    {
      id: 7,
      name: 'Nghiên cứu về Blockchain',
      description: 'Ứng dụng Blockchain trong tài chính.',
      status: 'Khoa đã duyệt',
      groupId: 'G007',
      students: [
        {
          id: 'S013',
          name: 'Trần Thị N',
          birthDate: '2000-09-09',
          phone: '0123456799',
          email: 'tranthyn@example.com',
          major: 'Khoa học máy tính',
        },
      ],
      advisor: {
        id: 'A007',
        name: 'TS. Lê Thị N',
        department: 'Khoa Tài chính',
        email: 'lethinh@example.com',
        phone: '0123456788',
      },
      researchPlan: {
        id: 'RP007',
        topicId: 7,
        planId: 'Plan07',
        description: 'Kế hoạch nghiên cứu về Blockchain.',
        startDate: '2024-06-01',
        endDate: '2024-12-31',
      },
    },
    {
      id: 8,
      name: 'Tối ưu hóa thuật toán tìm kiếm',
      description: 'Nghiên cứu tối ưu hóa thuật toán tìm kiếm.',
      status: 'Khoa đã duyệt',
      groupId: 'G008',
      students: [
        {
          id: 'S014',
          name: 'Nguyễn Văn O',
          birthDate: '2000-10-10',
          phone: '0123456700',
          email: 'nguyenvano@example.com',
          major: 'Khoa học máy tính',
        },
      ],
      advisor: {
        id: 'A008',
        name: 'PGS. TS. Trần Văn X',
        department: 'Khoa Công nghệ thông tin',
        email: 'tranvx@example.com',
        phone: '0123456789',
      },
      researchPlan: {
        id: 'RP008',
        topicId: 8,
        planId: 'Plan08',
        description: 'Kế hoạch tối ưu hóa thuật toán.',
        startDate: '2024-07-01',
        endDate: '2024-12-01',
      },
    },
    {
      id: 9,
      name: 'Phân tích dữ liệu trong ngành y tế',
      description: 'Nghiên cứu phân tích dữ liệu trong chăm sóc sức khỏe.',
      status: 'Khoa đã duyệt',
      groupId: 'G009',
      students: [
        {
          id: 'S015',
          name: 'Lê Thị P',
          birthDate: '2000-11-11',
          phone: '0123456701',
          email: 'lethip@example.com',
          major: 'Khoa học dữ liệu',
        },
      ],
      advisor: {
        id: 'A009',
        name: 'TS. Nguyễn Thị H',
        department: 'Khoa Y Dược',
        email: 'nguyenh@example.com',
        phone: '0123456790',
      },
      researchPlan: {
        id: 'RP009',
        topicId: 9,
        planId: 'Plan09',
        description: 'Kế hoạch phân tích dữ liệu trong ngành y tế.',
        startDate: '2024-08-01',
        endDate: '2024-11-30',
      },
    },
    {
      id: 10,
      name: 'Khoa học máy tính trong giáo dục',
      description: 'Nghiên cứu ứng dụng khoa học máy tính trong giáo dục.',
      status: 'Khoa đã duyệt',
      groupId: 'G010',
      students: [
        {
          id: 'S016',
          name: 'Nguyễn Thị Q',
          birthDate: '2000-12-12',
          phone: '0123456702',
          email: 'nguyenthiq@example.com',
          major: 'Công nghệ thông tin',
        },
      ],
      advisor: {
        id: 'A010',
        name: 'PGS. TS. Phạm Văn Y',
        department: 'Khoa Giáo dục',
        email: 'phamvy@example.com',
        phone: '0123456703',
      },
      researchPlan: {
        id: 'RP010',
        topicId: 10,
        planId: 'Plan10',
        description: 'Kế hoạch ứng dụng khoa học máy tính trong giáo dục.',
        startDate: '2024-09-01',
        endDate: '2024-12-31',
      },
    },
  ];
};

const StudentDetailModal = ({ student, onClose }) => (
  <div className="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
    <div className="bg-white rounded-lg p-6 max-w-lg w-full shadow-lg">
      <h2 className="text-2xl font-bold mb-4">{student.name} (ID: {student.id})</h2>
      <p><strong>Birth Date:</strong> {student.birthDate}</p>
      <p><strong>Phone:</strong> {student.phone}</p>
      <p><strong>Email:</strong> {student.email}</p>
      <p><strong>Major:</strong> {student.major}</p>
      <div className="mt-4 flex justify-center">
        <button onClick={onClose} className="mt-4 p-2 bg-blue-500 text-white rounded">Close</button>
      </div>
    </div>
  </div>
);

const AdvisorDetailModal = ({ advisor, onClose }) => (
  <div className="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
    <div className="bg-white rounded-lg p-6 max-w-lg w-full shadow-lg">
      <h2 className="text-2xl font-bold mb-4">{advisor.name} (ID: {advisor.id})</h2>
      <p><strong>Department:</strong> {advisor.department}</p>
      <p><strong>Email:</strong> {advisor.email}</p>
      <p><strong>Phone:</strong> {advisor.phone}</p>
      <div className="mt-4 flex justify-center">
        <button onClick={onClose} className="mt-4 p-2 bg-blue-500 text-white rounded">Close</button>
      </div>
    </div>
  </div>
);

const TopicList = () => {
  const [topics, setTopics] = useState([]);
  const [selectedTopic, setSelectedTopic] = useState(null);
  const [showStudentDetail, setShowStudentDetail] = useState(false);
  const [showAdvisorDetail, setShowAdvisorDetail] = useState(false);
  const [selectedStudent, setSelectedStudent] = useState(null);
  const [isEditing, setIsEditing] = useState(false);
  const [editedTopic, setEditedTopic] = useState({});

  useEffect(() => {
    const getTopics = async () => {
      const data = await fetchTopics();
      setTopics(data);
    };
    getTopics();
  }, []);

  const handleEdit = (topic) => {
    setIsEditing(true);
    setEditedTopic({ ...topic }); // sao chép thông tin đề tài để chỉnh sửa
  };

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setEditedTopic((prev) => ({
      ...prev,
      [name]: value,
    }));
  };

  const handleStudentInputChange = (e, index) => {
    const { name, value } = e.target;
    const updatedStudents = [...editedTopic.students];
    updatedStudents[index] = { ...updatedStudents[index], [name]: value };
    setEditedTopic((prev) => ({
      ...prev,
      students: updatedStudents,
    }));
  };

  const handleUpdate = () => {
    setTopics((prev) =>
      prev.map((topic) => (topic.id === editedTopic.id ? editedTopic : topic))
    );
    setIsEditing(false);
    setSelectedTopic(null);
  };

  const handleDelete = (id) => {
    setTopics((prev) => prev.filter((topic) => topic.id !== id));
  };

  const handleViewDetails = (topic) => {
    setSelectedTopic(topic);
  };

  const handleStudentDetail = (student) => {
    setSelectedStudent(student);
    setShowStudentDetail(true);
  };

  const handleAdvisorDetail = () => {
    setShowAdvisorDetail(true);
  };

  const closeDetailView = () => {
    setSelectedTopic(null);
    setShowStudentDetail(false);
    setShowAdvisorDetail(false);
    setSelectedStudent(null);
    setIsEditing(false);
  };

  return (
    <div className="bg-white rounded-lg shadow p-6">
      <table className="w-full">
        <thead>
          <tr className="border-b">
            <th className="text-left py-4 px-2">Topic Name</th>
            <th className="text-left py-4 px-2">Advisor Name</th>
            <th className="text-left py-4 px-2">Group ID</th>
            <th className="text-left py-4 px-2">Status</th>
            <th className="text-right py-4 px-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          {topics.map((topic) => (
            <tr key={topic.id} className="border-b hover:bg-gray-50">
              <td className="py-4 px-2">{topic.name}</td>
              <td className="py-4 px-2">{topic.advisor.name}</td>
              <td className="py-4 px-2">{topic.groupId}</td>
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

      {/* Detail View Modal */}
      {selectedTopic && (
        <div className="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
          <div className="bg-white rounded-lg p-6 max-w-lg w-full shadow-lg">
            <h2 className="text-2xl font-bold mb-4 text-center">{selectedTopic.name}</h2>
            <div className="grid grid-cols-2 gap-4 mb-4">
              <div className="p-4 border rounded-lg shadow-sm bg-gray-50">
                <h3 className="font-semibold">Student Details</h3>
                <div className="max-h-40 overflow-y-auto">
                  {selectedTopic.students.map((student, index) => (
                    <div key={student.id}>
                      <p><strong>ID:</strong> {student.id}</p>
                      <p><strong>Name:</strong> {student.name}</p>
                      <input
                        type="text"
                        name="name"
                        value={student.name}
                        onChange={(e) => handleStudentInputChange(e, index)}
                        className="border p-1 rounded mt-2 w-full"
                      />
                      <button onClick={() => handleStudentDetail(student)} className="text-blue-500 mt-2">View Full Details</button>
                      <hr className="my-2" />
                    </div>
                  ))}
                </div>
              </div>

              <div className="p-4 border rounded-lg shadow-sm bg-gray-50">
                <h3 className="font-semibold">Advisor Details</h3>
                <p><strong>ID:</strong> {selectedTopic.advisor.id}</p>
                <p><strong>Name:</strong> {selectedTopic.advisor.name}</p>
                <button onClick={handleAdvisorDetail} className="text-blue-500 mt-2">View Full Details</button>
              </div>
            </div>

            <div className="p-4 border rounded-lg shadow-sm bg-gray-50">
              <h3 className="font-semibold">Research Plan Details</h3>
              <p><strong>Plan ID:</strong> {selectedTopic.researchPlan.planId}</p>
              <p><strong>Description:</strong> {selectedTopic.researchPlan.description}</p>
              <p><strong>Start Date:</strong> {selectedTopic.researchPlan.startDate}</p>
              <p><strong>End Date:</strong> {selectedTopic.researchPlan.endDate}</p>
            </div>

            <div className="mt-4 flex justify-center space-x-4">
              <button
                onClick={() => handleEdit(selectedTopic)}
                className="h-10 px-4 bg-yellow-500 text-white rounded transition duration-300 ease-in-out hover:bg-yellow-600"
              >
                Edit Topic
              </button>
              <button
                onClick={() => handleDelete(selectedTopic.id)}
                className="h-10 px-4 bg-red-500 text-white rounded transition duration-300 ease-in-out hover:bg-red-600"
              >
                Delete Topic
              </button>
              <button
                onClick={closeDetailView}
                className="h-10 px-4 bg-blue-500 text-white rounded"
              >
                Close
              </button>
            </div>
          </div>
        </div>
      )}

      {/* Edit Topic Modal */}
      {isEditing && editedTopic && (
  <div className="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 mt-6">
    <div className="bg-white rounded-lg p-6 max-w-3xl w-full h-[80vh] overflow-y-auto shadow-lg">
      <h2 className="text-2xl font-bold mb-4 text-center">Edit Topic</h2>
      <form onSubmit={handleUpdate} className="space-y-6">

        {/* Thông tin đề tài */}
        <div className="border-b-2 border-gray-400 pb-4">
          <h3 className="text-xl font-semibold mb-2">Topic Information</h3>
          <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label className="block text-sm font-medium mb-1">Topic Name</label>
              <input
                type="text"
                name="name"
                value={editedTopic.name}
                onChange={handleInputChange}
                className="w-full px-4 py-2 border rounded-lg"
              />
            </div>
            <div>
              <label className="block text-sm font-medium mb-1">Description</label>
              <textarea
                name="description"
                value={editedTopic.description}
                onChange={handleInputChange}
                className="w-full px-4 py-2 border rounded-lg"
              />
            </div>
          </div>
        </div>

        {/* Chỉnh sửa thông tin sinh viên */}
        <div className="border-b-2 border-gray-400 pb-4 my-4">
          <h3 className="text-xl font-semibold mb-2">Student Details</h3>
          <div className="space-y-4">
            {editedTopic.students.map((student, index) => (
              <div key={student.id} className="border-2 border-gray-400 rounded-lg p-4">
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label className="block text-sm font-medium">Student ID</label>
                    <input
                      type="text"
                      name="id"
                      value={student.id}
                      readOnly
                      className="w-full px-4 py-2 border rounded-lg bg-gray-100"
                    />
                  </div>
                  <div>
                    <label className="block text-sm font-medium">Student Name</label>
                    <input
                      type="text"
                      name="name"
                      value={student.name}
                      onChange={(e) => handleStudentInputChange(e, index)}
                      className="w-full px-4 py-2 border rounded-lg"
                    />
                  </div>
                  <div>
                    <label className="block text-sm font-medium">Birth Date</label>
                    <input
                      type="date"
                      name="birthDate"
                      value={student.birthDate}
                      onChange={(e) => handleStudentInputChange(e, index)}
                      className="w-full px-4 py-2 border rounded-lg"
                    />
                  </div>
                  <div>
                    <label className="block text-sm font-medium">Phone</label>
                    <input
                      type="text"
                      name="phone"
                      value={student.phone}
                      onChange={(e) => handleStudentInputChange(e, index)}
                      className="w-full px-4 py-2 border rounded-lg"
                    />
                  </div>
                  <div>
                    <label className="block text-sm font-medium">Email</label>
                    <input
                      type="email"
                      name="email"
                      value={student.email}
                      onChange={(e) => handleStudentInputChange(e, index)}
                      className="w-full px-4 py-2 border rounded-lg"
                    />
                  </div>
                  <div>
                    <label className="block text-sm font-medium">Major</label>
                    <input
                      type="text"
                      name="major"
                      value={student.major}
                      onChange={(e) => handleStudentInputChange(e, index)}
                      className="w-full px-4 py-2 border rounded-lg"
                    />
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>

        {/* Chỉnh sửa thông tin giảng viên hướng dẫn */}
        <div className="border-b-2 border-gray-400 pb-4 my-4">
          <h3 className="text-xl font-semibold mb-2">Advisor Details</h3>
          <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label className="block text-sm font-medium">Advisor ID</label>
              <input
                type="text"
                name="advisorId"
                value={editedTopic.advisor.id}
                readOnly
                className="w-full px-4 py-2 border rounded-lg bg-gray-100"
              />
            </div>
            <div>
              <label className="block text-sm font-medium">Advisor Name</label>
              <input
                type="text"
                name="advisorName"
                value={editedTopic.advisor.name}
                onChange={handleInputChange}
                className="w-full px-4 py-2 border rounded-lg"
              />
            </div>
            <div>
              <label className="block text-sm font-medium">Department</label>
              <input
                type="text"
                name="department"
                value={editedTopic.advisor.department}
                onChange={handleInputChange}
                className="w-full px-4 py-2 border rounded-lg"
              />
            </div>
            <div>
              <label className="block text-sm font-medium">Email</label>
              <input
                type="email"
                name="advisorEmail"
                value={editedTopic.advisor.email}
                onChange={handleInputChange}
                className="w-full px-4 py-2 border rounded-lg"
              />
            </div>
            <div>
              <label className="block text-sm font-medium">Phone</label>
              <input
                type="text"
                name="advisorPhone"
                value={editedTopic.advisor.phone}
                onChange={handleInputChange}
                className="w-full px-4 py-2 border rounded-lg"
              />
            </div>
          </div>
        </div>

        {/* Chỉnh sửa kế hoạch nghiên cứu */}
        <div className="border-t-2 border-gray-400 pt-4 my-4">
          <h3 className="text-xl font-semibold mb-2">Research Plan Details</h3>
          <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label className="block text-sm font-medium">Plan ID</label>
              <input
                type="text"
                name="planId"
                value={editedTopic.researchPlan.planId}
                onChange={handleInputChange}
                className="w-full px-4 py-2 border rounded-lg"
              />
            </div>
            <div>
              <label className="block text-sm font-medium">Description</label>
              <textarea
                name="description"
                value={editedTopic.researchPlan.description}
                onChange={handleInputChange}
                className="w-full px-4 py-2 border rounded-lg"
              />
            </div>
            <div>
              <label className="block text-sm font-medium">Start Date</label>
              <input
                type="date"
                name="startDate"
                value={editedTopic.researchPlan.startDate}
                onChange={handleInputChange}
                className="w-full px-4 py-2 border rounded-lg"
              />
            </div>
            <div>
              <label className="block text-sm font-medium">End Date</label>
              <input
                type="date"
                name="endDate"
                value={editedTopic.researchPlan.endDate}
                onChange={handleInputChange}
                className="w-full px-4 py-2 border rounded-lg"
              />
            </div>
          </div>
        </div>
        <div className="border-t border-gray-600 my-4 w-full"></div> 
        <div className="flex justify-center space-x-10">
          <button type="submit" className="w-full px-4 py-2 bg-blue-600 text-white rounded-lg transition duration-300 ease-in-out hover:bg-blue-700">
            Update
          </button>
          <button
            onClick={() => setIsEditing(false)}
            className="w-full px-4 py-2 bg-red-600 text-white rounded-lg transition duration-300 ease-in-out hover:bg-red-700"
          >
            Cancel
          </button>
          
        </div>
      </form>
    </div>
  </div>
)}

      {/* Student Detail Modal */}
      {showStudentDetail && selectedStudent && (
        <StudentDetailModal student={selectedStudent} onClose={() => setShowStudentDetail(false)} />
      )}

      {/* Advisor Detail Modal */}
      {showAdvisorDetail && selectedTopic && (
        <AdvisorDetailModal advisor={selectedTopic.advisor} onClose={() => setShowAdvisorDetail(false)} />
      )}
    </div>
  );
};

export default TopicList;