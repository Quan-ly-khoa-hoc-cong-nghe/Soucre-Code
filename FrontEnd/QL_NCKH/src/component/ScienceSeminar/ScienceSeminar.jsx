import React, { useState } from 'react';
import ScienceSeminarList from './ScienceSeminarList';

const ScienceSeminar = () => {
    const [showForm, setShowForm] = useState(false);
    const [eventData, setEventData] = useState({
        eventName: '',
        startDate: '',
        endDate: '',
        location: '',
        description: '',
        participantCount: 0,  // Number of participants
        sponsorCount: 1,      // Number of sponsors (default 1)
        sponsors: [],        // Array to store sponsors
    });

    // Function to handle input changes for event details
    const handleInputChange = (e) => {
        const { name, value } = e.target;
        setEventData((prevState) => ({
            ...prevState,
            [name]: value,
        }));
    };

    // Function to handle sponsor info for each sponsor
    const handleSponsorChange = (e, index) => {
        const { name, value } = e.target;
        const updatedSponsors = [...eventData.sponsors];
        updatedSponsors[index] = { ...updatedSponsors[index], [name]: value };
        setEventData((prevState) => ({
            ...prevState,
            sponsors: updatedSponsors,
        }));
    };

    // Handle adding new sponsors based on sponsor count
    const handleSponsorCountChange = (e) => {
        const count = e.target.value;
        const updatedSponsors = [...eventData.sponsors];
        while (updatedSponsors.length < count) {
            updatedSponsors.push({
                sponsorName: '',
                sponsorAddress: '',
                sponsorPhone: '',
                sponsorEmail: ''
            });
        }
        while (updatedSponsors.length > count) {
            updatedSponsors.pop();
        }
        setEventData((prevState) => ({
            ...prevState,
            sponsorCount: count,
            sponsors: updatedSponsors,
        }));
    };

    const handleFormSubmit = (e) => {
        e.preventDefault();
        console.log(eventData);
        setShowForm(false); // Close form after submission
    };

    return (
        <div className="min-h-screen bg-gray-50 p-8">
            <div className="container mx-auto">
                <div className="flex items-center justify-between mb-6">
                    <h1 className="text-2xl font-semibold">Create Seminar</h1>
                    <nav className="text-sm">
                        <span className="text-gray-500">Dashboard</span>
                        <span className="mx-2">/</span>
                        <span>Science Seminar</span>
                    </nav>
                </div>

                {/* Modal for Event Details */}
                {showForm && (
                    <div className="fixed inset-0 flex justify-center items-center bg-gray-500 bg-opacity-50">
                        <div className="bg-white p-8 rounded-lg w-[600px] max-h-[80vh] overflow-y-auto mt-10 shadow-lg">
                            <h3 className="text-xl font-semibold mb-4">Enter Seminar Details</h3>
                            <form onSubmit={handleFormSubmit}>
                                {/* Seminar Details */}
                                <div className="border border-gray-300 p-4 rounded mb-6">
                                    <h4 className="text-lg font-semibold mb-4">Seminar Details</h4>
                                    <div className="mb-4">
                                        <label className="block text-sm font-medium mb-2">Seminar Name</label>
                                        <input
                                            type="text"
                                            name="eventName"
                                            value={eventData.eventName}
                                            onChange={handleInputChange}
                                            className="w-full px-4 py-2 border rounded-lg"
                                            placeholder="Enter seminar name"
                                        />
                                    </div>

                                    <div className="mb-4">
                                        <label className="block text-sm font-medium mb-2">Start Date</label>
                                        <input
                                            type="date"
                                            name="startDate"
                                            value={eventData.startDate}
                                            onChange={handleInputChange}
                                            className="w-full px-4 py-2 border rounded-lg"
                                        />
                                    </div>

                                    <div className="mb-4">
                                        <label className="block text-sm font-medium mb-2">End Date</label>
                                        <input
                                            type="date"
                                            name="endDate"
                                            value={eventData.endDate}
                                            onChange={handleInputChange}
                                            className="w-full px-4 py-2 border rounded-lg"
                                        />
                                    </div>

                                    <div className="mb-4">
                                        <label className="block text-sm font-medium mb-2">Seminar Location</label>
                                        <input
                                            type="text"
                                            name="location"
                                            value={eventData.location}
                                            onChange={handleInputChange}
                                            className="w-full px-4 py-2 border rounded-lg"
                                            placeholder="Enter seminar location"
                                        />
                                    </div>

                                    <div className="mb-4">
                                        <label className="block text-sm font-medium mb-2">Number of Participants</label>
                                        <input
                                            type="number"
                                            name="participantCount"
                                            value={eventData.participantCount}
                                            onChange={handleInputChange}
                                            className="w-full px-4 py-2 border rounded-lg"
                                            placeholder="Enter number of participants"
                                        />
                                    </div>
                                </div>

                                {/* Sponsor Information */}
                                <div className="border border-gray-300 p-4 rounded mb-6">
                                    <h4 className="text-lg font-semibold mb-4">Sponsor Information</h4>
                                    <div className="mb-4">
                                        <label className="block text-sm font-medium mb-2">Number of Sponsors</label>
                                        <input
                                            type="number"
                                            name="sponsorCount"
                                            value={eventData.sponsorCount}
                                            onChange={handleSponsorCountChange}
                                            className="w-full px-4 py-2 border rounded-lg"
                                            placeholder="Enter number of sponsors"
                                        />
                                    </div>

                                    {eventData.sponsors.map((sponsor, index) => (
                                        <div key={index} className="border border-gray-300 p-4 rounded mb-6">
                                            <h5 className="font-semibold">Sponsor {index + 1}</h5>
                                            <div className="mb-4">
                                                <label className="block text-sm font-medium mb-2">Sponsor Name</label>
                                                <input
                                                    type="text"
                                                    name="sponsorName"
                                                    value={sponsor.sponsorName}
                                                    onChange={(e) => handleSponsorChange(e, index)}
                                                    className="w-full px-4 py-2 border rounded-lg"
                                                    placeholder="Enter sponsor name"
                                                />
                                            </div>

                                            <div className="mb-4">
                                                <label className="block text-sm font-medium mb-2">Sponsor Address</label>
                                                <input
                                                    type="text"
                                                    name="sponsorAddress"
                                                    value={sponsor.sponsorAddress}
                                                    onChange={(e) => handleSponsorChange(e, index)}
                                                    className="w-full px-4 py-2 border rounded-lg"
                                                    placeholder="Enter sponsor address"
                                                />
                                            </div>

                                            <div className="mb-4">
                                                <label className="block text-sm font-medium mb-2">Sponsor Phone</label>
                                                <input
                                                    type="text"
                                                    name="sponsorPhone"
                                                    value={sponsor.sponsorPhone}
                                                    onChange={(e) => handleSponsorChange(e, index)}
                                                    className="w-full px-4 py-2 border rounded-lg"
                                                    placeholder="Enter sponsor phone"
                                                />
                                            </div>

                                            <div className="mb-4">
                                                <label className="block text-sm font-medium mb-2">Sponsor Email</label>
                                                <input
                                                    type="email"
                                                    name="sponsorEmail"
                                                    value={sponsor.sponsorEmail}
                                                    onChange={(e) => handleSponsorChange(e, index)}
                                                    className="w-full px-4 py-2 border rounded-lg"
                                                    placeholder="Enter sponsor email"
                                                />
                                            </div>
                                        </div>
                                    ))}
                                </div>
                                

                                {/* Submit Button */}
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

            {/* List of existing Science Seminars */}
            <ScienceSeminarList />
        </div>
    );
};

export default ScienceSeminar;
