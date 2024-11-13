import React, { useState } from 'react';
import ListArticleReview from './ListArticleReview'; 

const ArticleReview = () => {
    const [showForm, setShowForm] = useState(false);
    const [formData, setFormData] = useState({
        author: '',
        publishDate: '',
        articleTitle: '',
        articleLink: '',
        image: null
    });
    const [imagePreview, setImagePreview] = useState(null);

    const handleInputChange = (e) => {
        const { name, value } = e.target;
        setFormData({
            ...formData,
            [name]: value
        });
    };

    const handleFileUpload = (e) => {
        const selectedFile = e.target.files[0];
        if (selectedFile) {
            const fileURL = URL.createObjectURL(selectedFile);
            setImagePreview(fileURL);
            setFormData(prevData => ({ ...prevData, image: selectedFile }));
        }
    };

    const handleFormSubmit = (e) => {
        e.preventDefault();
        console.log(formData);
        setShowForm(false);
    };

    return (
        <div className="min-h-screen bg-gray-50 p-8">
            <div className="container mx-auto">
                <div className="flex items-center justify-between mb-6">
                    <h1 className="text-2xl font-semibold">Article Review</h1>
                    <div className="flex items-center space-x-4">
                        <nav className="text-sm">
                            <span className="text-gray-500">Dashboard</span>
                            <span className="mx-2">/</span>
                            <span>Article Review</span>
                        </nav>
                    </div>
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
                        <span>New Article</span>
                    </button>
                </div>

                {/* Modal form */}
                {showForm && (
                    <div className="fixed inset-0 flex justify-center items-start bg-gray-500 bg-opacity-50 pt-20">
                        <div className="bg-white p-8 rounded-lg w-[800px] max-h-[80vh] overflow-y-auto shadow-lg">
                            <h3 className="text-xl font-semibold mb-4">Enter Article Details</h3>
                            <form onSubmit={handleFormSubmit}>
                                <div className="mb-4">
                                    <label className="block text-sm font-medium mb-2">Author Name</label>
                                    <input
                                        type="text"
                                        name="author"
                                        value={formData.author}
                                        onChange={handleInputChange}
                                        className="w-full px-4 py-2 border rounded-lg"
                                        placeholder="Enter author's name"
                                    />
                                </div>
                                <div className="mb-4">
                                    <label className="block text-sm font-medium mb-2">Publish Date</label>
                                    <input
                                        type="date"
                                        name="publishDate"
                                        value={formData.publishDate}
                                        onChange={handleInputChange}
                                        className="w-full px-4 py-2 border rounded-lg"
                                    />
                                </div>
                                <div className="mb-4">
                                    <label className="block text-sm font-medium mb-2">Article Title</label>
                                    <input
                                        type="text"
                                        name="articleTitle"
                                        value={formData.articleTitle}
                                        onChange={handleInputChange}
                                        className="w-full px-4 py-2 border rounded-lg"
                                        placeholder="Enter article title"
                                    />
                                </div>
                                <div className="mb-4">
                                    <label className="block text-sm font-medium mb-2">Article Link</label>
                                    <input
                                        type="url"
                                        name="articleLink"
                                        value={formData.articleLink}
                                        onChange={handleInputChange}
                                        className="w-full px-4 py-2 border rounded-lg"
                                        placeholder="Enter article link"
                                    />
                                </div>
                                <div className="mb-4">
                                    <label className="block text-sm font-medium mb-2">Upload Image</label>
                                    <input
                                        type="file"
                                        accept="image/*"
                                        onChange={handleFileUpload}
                                        className="w-full px-4 py-2 border rounded-lg"
                                    />
                                </div>
                                {imagePreview && (
                                    <div className="mb-4">
                                        <img src={imagePreview} alt="Preview" className="w-full h-auto rounded-lg" />
                                    </div>
                                )}
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

                {/* Include the ListArticleReview component here */}
                <ListArticleReview />
            </div>
        </div>
    );
};

export default ArticleReview;
