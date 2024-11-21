import React, { useState, useEffect } from 'react';
import { FaCheck, FaTimes, FaEye } from 'react-icons/fa';

const ListArticleReview = () => {
    const [baiBaoKhoaHoc, setBaiBaoKhoaHoc] = useState([]);
    const [hoSoBaiBaoKH, setHoSoBaiBaoKH] = useState([]);
    const [tacGiaBaiBaoGiangVien, setTacGiaBaiBaoGiangVien] = useState([]);
    const [selectedReview, setSelectedReview] = useState(null);
    const [standardInput, setStandardInput] = useState('');
    const [showStandardModal, setShowStandardModal] = useState(false);

    // Gọi API để lấy dữ liệu
    useEffect(() => {
        const fetchData = async () => {
            try {
                const [baiBaoResponse, hoSoResponse, tacGiaGiangVienResponse] = await Promise.all([
                    fetch('http://localhost/Soucre-Code/BackEnd/Api/ThamDinhBaiBaoApi/BaiBaoKhoaHoc_Api.php?action=get'),
                    fetch('http://localhost/Soucre-Code/BackEnd/Api/ThamDinhBaiBaoApi/HoSoBaiBaoKH_Api.php?action=get'),
                    fetch('http://localhost/Soucre-Code/BackEnd/Api/ThamDinhBaiBaoApi/TacGiaBaiBaoGiangVien_Api.php?action=get')
                ]);

                const baiBaoData = await baiBaoResponse.json();
                const hoSoData = await hoSoResponse.json();
                const tacGiaGiangVienData = await tacGiaGiangVienResponse.json();

                setBaiBaoKhoaHoc(baiBaoData.BaiBaoKhoaHoc || []);
                setHoSoBaiBaoKH(hoSoData.HoSoBaiBaoKhoaHoc || []);
                setTacGiaBaiBaoGiangVien(tacGiaGiangVienData.TacGiaGiangVien || []);
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        };

        fetchData();
    }, []);

    const handleViewDetails = (review) => {
        setSelectedReview(review);
    };

    const handleCloseDetails = () => {
        setSelectedReview(null);
    };

    const handleApprove = (reviewId) => {
        setHoSoBaiBaoKH(prevReviews =>
            prevReviews.map(review =>
                review.MaHoSo === reviewId ? { ...review, TrangThai: 'Đã duyệt' } : review
            )
        );
    };

    const handleReject = (reviewId) => {
        setHoSoBaiBaoKH(prevReviews =>
            prevReviews.map(review =>
                review.MaHoSo === reviewId ? { ...review, TrangThai: 'Hủy' } : review
            )
        );
    };

    // Hàm để tìm TenBaiBao từ danh sách BaiBaoKhoaHoc dựa vào MaBaiBao từ TacGiaGiangVien
    const getPaperTitle = (maTacGia) => {
        const author = tacGiaBaiBaoGiangVien.find(item => item.MaTacGia === maTacGia);
        if (author) {
            const paper = baiBaoKhoaHoc.find(item => item.MaBaiBao === author.MaBaiBao);
            return paper ? paper.TenBaiBao : "N/A";
        }
        return "N/A";
    };

    // Hàm để lấy tên tác giả từ danh sách TacGiaBaiBaoGiangVien dựa vào MaTacGia
    const getAuthorName = (maTacGia) => {
        const author = tacGiaBaiBaoGiangVien.find(item => item.MaTacGia === maTacGia);
        return author ? author.HoTenGV : "N/A";
    };

    return (
        <div className="bg-white rounded-lg shadow p-6">
            <table className="min-w-full">
                <thead>
                    <tr className="border-b">
                        <th className="text-left py-4 px-2">Paper Title</th>
                        <th className="text-left py-4 px-2">Author</th>
                        <th className="text-left py-4 px-2">Submission Date</th>
                        <th className="text-left py-4 px-2">Status</th>
                        <th className="text-right py-4 px-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {hoSoBaiBaoKH.map((review) => (
                        <tr key={review.MaHoSo} className="border-b hover:bg-gray-50">
                            <td className="py-4 px-2">{getPaperTitle(review.MaTacGia)}</td>
                            <td className="py-4 px-2">{getAuthorName(review.MaTacGia)}</td>
                            <td className="py-4 px-2">{review.NgayNop}</td>
                            <td className="py-4 px-2">
                                <span className={`px-2 py-1 rounded-full text-sm ${
                                    review.TrangThai === 'Đã duyệt' ? 'bg-green-100 text-green-800' :
                                    review.TrangThai === 'Chờ duyệt' ? 'bg-blue-100 text-blue-800' :
                                    review.TrangThai === 'Hủy' ? 'bg-red-100 text-red-800' : ''
                                }`}>
                                    {review.TrangThai}
                                </span>
                            </td>
                            <td className="py-4 px-2 text-right">
                                <div className="flex justify-end space-x-2">
                                    {(review.TrangThai === 'Chờ duyệt') && (
                                        <>
                                            <button onClick={() => handleApprove(review.MaHoSo)} className="p-2 text-green-600 hover:bg-green-100 rounded-full" title="Duyệt">
                                                <FaCheck className="w-5 h-5" />
                                            </button>
                                            <button onClick={() => handleReject(review.MaHoSo)} className="p-2 text-red-600 hover:bg-red-100 rounded-full" title="Hủy">
                                                <FaTimes className="w-5 h-5" />
                                            </button>
                                        </>
                                    )}
                                    <button onClick={() => handleViewDetails(review)} className="p-2 text-blue-600 hover:bg-blue-100 rounded-full" title="Xem chi tiết">
                                        <FaEye className="w-5 h-5" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    ))}
                </tbody>
            </table>

            {selectedReview && (
                <div className="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                    <div className="bg-white rounded-lg p-6 max-w-lg w-full shadow-lg">
                        <h3 className="text-2xl font-bold mb-4">Chi tiết thẩm định</h3>
                        <p><strong>Review ID:</strong> {selectedReview.MaHoSo}</p>
                        <p><strong>Status:</strong> {selectedReview.TrangThai}</p>
                        <p><strong>Submission Date:</strong> {selectedReview.NgayNop}</p>
                        <p><strong>Author:</strong> {getAuthorName(selectedReview.MaTacGia)}</p>
                        <div className="mt-4 flex justify-center">
                            <button onClick={handleCloseDetails} className="mt-4 p-2 bg-blue-500 text-white rounded">Đóng</button>
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
};

export default ListArticleReview;
