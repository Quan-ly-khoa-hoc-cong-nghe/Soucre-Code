import React, { useState } from 'react';
import TopicList from './TopicList';

const LecturerTopicApproval = () => {
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
           <div className="flex items-center justify-between mb-6">
                    <h1 className="text-2xl font-semibold">Quản lý đề tài giảng viên</h1>
                </div>

            <TopicList />
        </div>
    );
};

export default LecturerTopicApproval;
