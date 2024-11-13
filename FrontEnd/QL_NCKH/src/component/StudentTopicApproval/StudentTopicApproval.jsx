import React, { useState } from 'react';
import TopicList from './TopicList';

const StudentTopicApproval = () => {
    const [showForm, setShowForm] = useState(false);
    const [topicData, setTopicData] = useState({
        topicName: '',
        advisor: {
            id: '',
            name: '',
            department: '',
            email: '',
            phone: '',
            major: '',
            birthDate: ''
        },
        students: [],
        studentCount: 1
    });
    
    const [file, setFile] = useState(null);
    const [imagePreview, setImagePreview] = useState(null);

    const handleInputChange = (e) => {
        const { name, value } = e.target;
        setTopicData(prevState => ({
            ...prevState,
            [name]: value
        }));
    };

    const handleAdvisorChange = (e) => {
        const { name, value } = e.target;
        setTopicData(prevState => ({
            ...prevState,
            advisor: {
                ...prevState.advisor,
                [name]: value
            }
        }));
    };

    const handleStudentChange = (e, index) => {
        const { name, value } = e.target;
        const updatedStudents = [...topicData.students];
        updatedStudents[index] = {
            ...updatedStudents[index],
            [name]: value
        };
        setTopicData(prevState => ({
            ...prevState,
            students: updatedStudents
        }));
    };

    const addStudent = () => {
        setTopicData(prevState => ({
            ...prevState,
            students: [...prevState.students, { id: '', name: '', birthDate: '', phone: '', email: '', major: '', description: '', startDate: '', endDate: '' }]
        }));
    };

    const handleFormSubmit = (e) => {
        e.preventDefault();
        console.log(topicData);
        setShowForm(false);
    };

    const handleFileUpload = (e) => {
        const selectedFile = e.target.files[0];
        setFile(selectedFile);

        if (selectedFile) {
            const fileURL = URL.createObjectURL(selectedFile);
            setImagePreview(fileURL);
        }
    };

    return (
        <div className="min-h-screen bg-gray-50 p-8">
            <div className="container mx-auto">
                <div className="flex items-center justify-between mb-6">
                    <h1 className="text-2xl font-semibold">Topic Approval</h1>
                    <nav className="text-sm">
                        <span className="text-gray-500">Dashboard</span>
                        <span className="mx-2">/</span>
                        <span>Topic Approval</span>
                    </nav>
                </div>

                <div className="flex justify-between items-center mb-6">
                    <div className="flex items-center space-x-4">
                        <input
                            type="text"
                            placeholder="Search"
                            className="px-4 py-2 border rounded-lg"
                        />
                        <button className="px-4 py-2 border rounded-lg">Filters</button>
                    </div>
                    <button
                        className="px-4 py-2 bg-blue-600 text-white rounded-lg flex items-center space-x-2"
                        onClick={() => setShowForm(true)}
                    >
                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>New Topic</span>
                    </button>
                </div>

                {/* Modal form */}
                {showForm && (
                    <div className="fixed inset-0 flex justify-center items-center bg-gray-500 bg-opacity-50">
                        <div className="bg-white p-8 rounded-lg w-[600px] max-h-[80vh] overflow-y-auto mt-10 shadow-lg">
                            <h3 className="text-xl font-semibold mb-4">Enter Topic Details</h3>
                            <form onSubmit={handleFormSubmit}>
                                <div className="mb-4">
                                    <label className="block text-sm font-medium mb-2">Topic Name</label>
                                    <input
                                        type="text"
                                        name="topicName"
                                        value={topicData.topicName}
                                        onChange={handleInputChange}
                                        className="w-full px-4 py-2 border rounded-lg"
                                        placeholder="Enter topic name"
                                    />
                                </div>
                                <div className="mb-4">
                                    <label className="block text-sm font-medium mb-2">Description</label>
                                    <input
                                        type="text"
                                        name="description"
                                        value={topicData.description}
                                        onChange={handleInputChange}
                                        className="w-full px-4 py-2 border rounded-lg"
                                        placeholder="Enter Description"
                                    />
                                </div>
                                <div className="mb-4">
                                        <label className="block text-sm font-medium mb-2">Start Date</label>
                                        <input
                                            type="date"
                                            name="birthDate"
                                            value={topicData.advisor.birthDate}
                                            onChange={handleAdvisorChange}
                                            className="w-full px-4 py-2 border rounded-lg"
                                        />
                                    </div>
                                    <div className="mb-4">
                                        <label className="block text-sm font-medium mb-2">End Date</label>
                                        <input
                                            type="date"
                                            name="birthDate"
                                            value={topicData.advisor.birthDate}
                                            onChange={handleAdvisorChange}
                                            className="w-full px-4 py-2 border rounded-lg"
                                        />
                                    </div>
                                
                                <h4 className="text-lg font-semibold mb-2">Advisor</h4>
                                <div className="border border-gray-400 p-4 mb-4 rounded-lg">
                                    <div className="grid grid-cols-3 gap-4 mb-4">
                                        <div className="col-span-1">
                                            <label className="block text-sm font-medium mb-2">ID</label>
                                            <input
                                                type="text"
                                                name="id"
                                                value={topicData.advisor.id}
                                                onChange={handleAdvisorChange}
                                                className="w-full px-4 py-2 border rounded-lg"
                                            />
                                        </div>
                                        <div className="col-span-2">
                                            <label className="block text-sm font-medium mb-2">Name</label>
                                            <input
                                                type="text"
                                                name="name"
                                                value={topicData.advisor.name}
                                                onChange={handleAdvisorChange}
                                                className="w-full px-4 py-2 border rounded-lg"
                                            />
                                        </div>
                                    </div>
                                    <div className="mb-4">
                                        <label className="block text-sm font-medium mb-2">Birth Date</label>
                                        <input
                                            type="date"
                                            name="birthDate"
                                            value={topicData.advisor.birthDate}
                                            onChange={handleAdvisorChange}
                                            className="w-full px-4 py-2 border rounded-lg"
                                        />
                                    </div>
                                    <div className="grid grid-cols-3 gap-4 mb-4">
                                        <div>
                                            <label className="block text-sm font-medium mb-2">Email</label>
                                            <input
                                                type="email"
                                                name="email"
                                                value={topicData.advisor.email}
                                                onChange={handleAdvisorChange}
                                                className="w-full px-4 py-2 border rounded-lg"
                                            />
                                        </div>
                                        <div>
                                            <label className="block text-sm font-medium mb-2">Phone</label>
                                            <input
                                                type="text"
                                                name="phone"
                                                value={topicData.advisor.phone}
                                                onChange={handleAdvisorChange}
                                                className="w-full px-4 py-2 border rounded-lg"
                                            />
                                        </div>
                                        <div>
                                            <label className="block text-sm font-medium mb-2">Major</label>
                                            <input
                                                type="text"
                                                name="major"
                                                value={topicData.advisor.major}
                                                onChange={handleAdvisorChange}
                                                className="w-full px-4 py-2 border rounded-lg"
                                            />
                                        </div>
                                    </div>
                                </div>

                                {/* Student Information */}
                                <h4 className="text-lg font-semibold mb-2">Students</h4>
                                <div className="mb-4">
                                    <label className="block text-sm font-medium mb-2">Number of Students</label>
                                    <input
                                        type="number"
                                        value={topicData.studentCount}
                                        onChange={(e) => setTopicData(prevState => ({
                                            ...prevState,
                                            studentCount: parseInt(e.target.value)
                                        }))}
                                        className="w-full px-4 py-2 border rounded-lg"
                                    />
                                </div>

                                {Array.from({ length: topicData.studentCount }).map((_, index) => (
                                    <div key={index} className="mb-4">
                                        <h5 className="font-semibold">Student {index + 1}</h5>
                                        <div className="border border-gray-400 p-4 mb-4 rounded-lg">
                                            <div className="grid grid-cols-3 gap-4 mb-4">
                                                <div className="col-span-1">
                                                    <label className="block text-sm font-medium mb-2">ID</label>
                                                    <input
                                                        type="text"
                                                        name="id"
                                                        value={topicData.students[index]?.id || ''}
                                                        onChange={(e) => handleStudentChange(e, index)}
                                                        className="w-full px-4 py-2 border rounded-lg"
                                                    />
                                                </div>
                                                <div className="col-span-2">
                                                    <label className="block text-sm font-medium mb-2">Name</label>
                                                    <input
                                                        type="text"
                                                        name="name"
                                                        value={topicData.students[index]?.name || ''}
                                                        onChange={(e) => handleStudentChange(e, index)}
                                                        className="w-full px-4 py-2 border rounded-lg"
                                                    />
                                                </div>
                                            </div>
                                            <div className="mb-4">
                                                <label className="block text-sm font-medium mb-2">Birth Date</label>
                                                <input
                                                    type="date"
                                                    name="birthDate"
                                                    value={topicData.students[index]?.birthDate || ''}
                                                    onChange={(e) => handleStudentChange(e, index)}
                                                    className="w-full px-4 py-2 border rounded-lg"
                                                />
                                            </div>
                                            <div className="grid grid-cols-3 gap-4 mb-4">
                                                <div>
                                                    <label className="block text-sm font-medium mb-2">Email</label>
                                                    <input
                                                        type="email"
                                                        name="email"
                                                        value={topicData.students[index]?.email || ''}
                                                        onChange={(e) => handleStudentChange(e, index)}
                                                        className="w-full px-4 py-2 border rounded-lg"
                                                    />
                                                </div>
                                                <div>
                                                    <label className="block text-sm font-medium mb-2">Phone</label>
                                                    <input
                                                        type="text"
                                                        name="phone"
                                                        value={topicData.students[index]?.phone || ''}
                                                        onChange={(e) => handleStudentChange(e, index)}
                                                        className="w-full px-4 py-2 border rounded-lg"
                                                    />
                                                </div>
                                                <div>
                                                    <label className="block text-sm font-medium mb-2">Major</label>
                                                    <input
                                                        type="text"
                                                        name="major"
                                                        value={topicData.students[index]?.major || ''}
                                                        onChange={(e) => handleStudentChange(e, index)}
                                                        className="w-full px-4 py-2 border rounded-lg"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                ))}

                               {/* File Upload for Registration Document */}
                                <div className="mb-4">
                                    <label className="block text-sm font-medium mb-2">Upload Registration Document</label>
                                    <input
                                        type="file"
                                        onChange={handleFileUpload}
                                        className="w-full px-4 py-2 border rounded-lg"
                                    />
                                    {file && (
                                        <p className="mt-2 text-sm text-gray-600">Selected file: {file.name}</p>
                                    )}
                                    {imagePreview && (
                                        <div className="mt-4">
                                            <h5 className="text-sm font-medium mb-2">Image Preview:</h5>
                                            <img src={imagePreview} alt="Preview" className="w-full h-auto rounded-lg" />
                                        </div>
                                    )}
                                </div>

                                <div className="border-t border-gray-600 my-4 w-full"></div> 

                                {/* Buttons */}
                                <div className="flex justify-center space-x-2">
                                    <button
                                        type="submit"
                                        className="w-full px-4 py-2 bg-blue-600 text-white rounded-lg transition duration-300 ease-in-out hover:bg-blue-700"
                                    >
                                        Submit
                                    </button>
                                    <button
                                        type="button"
                                        onClick={() => setShowForm(false)}
                                        className="w-full px-4 py-2 bg-red-600 text-white rounded-lg transition duration-300 ease-in-out hover:bg-red-700"
                                    >
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                )}
            </div>

            <TopicList />
        </div>
    );
};

export default StudentTopicApproval;
