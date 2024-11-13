import React, { useEffect, useState } from 'react';
import { FaEye, FaCheck, FaTimes } from 'react-icons/fa';

const ScienceSeminarList = () => {
  const [seminars, setSeminars] = useState([]);

  useEffect(() => {
    // Simulate fetching seminars from the database
    const fetchSeminars = () => {
      return [
        {
          id: 1,
          name: 'AI Research Seminar',
          sponsor: { name: 'TechCorp' },
          location: 'New York, USA',
          startDate: '2024-12-01',
          endDate: '2024-12-03',
          status: 'Khoa đã duyệt',
        },
        {
          id: 2,
          name: 'IoT in Agriculture Seminar',
          sponsor: { name: 'AgriTech' },
          location: 'Los Angeles, USA',
          startDate: '2024-12-05',
          endDate: '2024-12-07',
          status: 'Đã duyệt',
        },
        {
          id: 3,
          name: 'Blockchain Technology Seminar',
          sponsor: { name: 'BlockCorp' },
          location: 'San Francisco, USA',
          startDate: '2024-12-10',
          endDate: '2024-12-12',
          status: 'Hủy',
        },
        // Add more seminars as needed
      ];
    };

    setSeminars(fetchSeminars());
  }, []);

  // Handle status change (approve or reject)
  const handleStatusChange = (id, newStatus) => {
    setSeminars((prevSeminars) =>
      prevSeminars.map((seminar) =>
        seminar.id === id ? { ...seminar, status: newStatus } : seminar
      )
    );
  };

  return (
    <div className="bg-white rounded-lg shadow p-6">
      <table className="w-full">
        <thead>
          <tr className="border-b">
            <th className="text-left py-4 px-2">Seminar Name</th>
            <th className="text-left py-4 px-2">Sponsor</th>
            <th className="text-left py-4 px-2">Location</th>
            <th className="text-left py-4 px-2">Start Date</th>
            <th className="text-left py-4 px-2">End Date</th>
            <th className="text-left py-4 px-2">Status</th>
            <th className="text-right py-4 px-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          {seminars.map((seminar) => (
            <tr key={seminar.id} className="border-b hover:bg-gray-50">
              <td className="py-4 px-2">{seminar.name}</td>
              <td className="py-4 px-2">{seminar.sponsor.name}</td>
              <td className="py-4 px-2">{seminar.location}</td>
              <td className="py-4 px-2">{seminar.startDate}</td>
              <td className="py-4 px-2">{seminar.endDate}</td>
              <td className="py-4 px-2">
                <span
                  className={`px-2 py-1 rounded-full text-sm ${
                    seminar.status === 'Khoa đã duyệt'
                      ? 'bg-blue-100 text-blue-800'
                      : seminar.status === 'Đã duyệt'
                      ? 'bg-green-100 text-green-800'
                      : 'bg-red-100 text-red-800'
                  }`}
                >
                  {seminar.status}
                </span>
              </td>
              <td className="py-4 px-2 text-right">
                <div className="flex justify-end space-x-2">
                  {/* All three buttons for "Khoa đã duyệt" */}
                  {seminar.status === 'Khoa đã duyệt' && (
                    <>
                      <button
                        className="p-2 text-green-600 hover:bg-green-100 rounded-full"
                        title="Approve"
                        onClick={() => handleStatusChange(seminar.id, 'Đã duyệt')}
                      >
                        <FaCheck className="w-5 h-5" />
                      </button>

                      <button
                        className="p-2 text-red-600 hover:bg-red-100 rounded-full"
                        title="Reject"
                        onClick={() => handleStatusChange(seminar.id, 'Hủy')}
                      >
                        <FaTimes className="w-5 h-5" />
                      </button>

                      <button
                        className="p-2 text-blue-600 hover:bg-blue-100 rounded-full"
                        title="View Details"
                        onClick={() => console.log(`View details for seminar ID: ${seminar.id}`)} // Implement your view logic
                      >
                        <FaEye className="w-5 h-5" />
                      </button>
                    </>
                  )}

                  {/* View Details Button for "Đã duyệt" and "Hủy" */}
                  {(seminar.status === 'Đã duyệt' || seminar.status === 'Hủy') && (
                    <button
                      className="p-2 text-blue-600 hover:bg-blue-100 rounded-full"
                      title="View Details"
                      onClick={() => console.log(`View details for seminar ID: ${seminar.id}`)} // Implement your view logic
                    >
                      <FaEye className="w-5 h-5" />
                    </button>
                  )}
                </div>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
};

export default ScienceSeminarList;
