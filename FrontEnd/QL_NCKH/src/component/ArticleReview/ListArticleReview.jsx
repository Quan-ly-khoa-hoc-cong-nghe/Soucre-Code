import React, { useState } from 'react';
import { FaCheck, FaTimes, FaEye } from 'react-icons/fa';

// Mock Data
const nguoiDung = [
    { ma_nguoi_dung: 1, ten_nguoi_dung: 'Nguyễn Văn A', vai_tro: 'sinh viên', mat_khau: 'password123' },
    { ma_nguoi_dung: 2, ten_nguoi_dung: 'Trần Thị B', vai_tro: 'sinh viên', mat_khau: 'password123' },
    { ma_nguoi_dung: 3, ten_nguoi_dung: 'PGS. TS. Trần Văn B', vai_tro: 'giảng viên', mat_khau: 'password123' },
    { ma_nguoi_dung: 4, ten_nguoi_dung: 'TS. Nguyễn Thị D', vai_tro: 'giảng viên', mat_khau: 'password123' },
];

const hoSo = [
    { ma_hosoh: 1, ten_hosoh: 'Hồ sơ Nguyễn Văn A', ma_nguoi_dung: 1 },
    { ma_hosoh: 2, ten_hosoh: 'Hồ sơ Trần Thị B', ma_nguoi_dung: 2 },
    { ma_hosoh: 3, ten_hosoh: 'Hồ sơ PGS. TS. Trần Văn B', ma_nguoi_dung: 3 },
    { ma_hosoh: 4, ten_hosoh: 'Hồ sơ TS. Nguyễn Thị D', ma_nguoi_dung: 4 },
];

const sinhVien = [
    { ma_sinh_vien: 1, ho_ten_sinh_vien: 'Nguyễn Văn A', so_dien_thoai: '0123456789', email: 'nguyenvana@example.com' },
    { ma_sinh_vien: 2, ho_ten_sinh_vien: 'Trần Thị B', so_dien_thoai: '0123456790', email: 'tranthib@example.com' },
    { ma_sinh_vien: 3, ho_ten_sinh_vien: 'Lê Văn C', so_dien_thoai: '0123456791', email: 'levanc@example.com' },
    { ma_sinh_vien: 4, ho_ten_sinh_vien: 'Nguyễn Thị D', so_dien_thoai: '0123456792', email: 'nguyenthid@example.com' },
    { ma_sinh_vien: 5, ho_ten_sinh_vien: 'Trần Văn E', so_dien_thoai: '0123456793', email: 'tranve@example.com' },
    { ma_sinh_vien: 6, ho_ten_sinh_vien: 'Lê Thị F', so_dien_thoai: '0123456794', email: 'lethif@example.com' },
];

const giangVien = [
    { ma_giang_vien: 1, ho_ten_giang_vien: 'PGS. TS. Trần Văn B', so_dien_thoai: '0123456780', email: 'tranvanb@example.com' },
    { ma_giang_vien: 2, ho_ten_giang_vien: 'TS. Nguyễn Thị D', so_dien_thoai: '0123456781', email: 'nguyentd@example.com' },
    { ma_giang_vien: 3, ho_ten_giang_vien: 'PGS. TS. Lê Văn G', so_dien_thoai: '0123456782', email: 'levang@example.com' },
    { ma_giang_vien: 4, ho_ten_giang_vien: 'TS. Trần Thị H', so_dien_thoai: '0123456783', email: 'tranthih@example.com' },
];

const tacGia = [
    { ma_sinh_vien: 1, ma_giang_vien: 1, ho_so: 'Hồ sơ Nguyễn Văn A', ma_de_tai: 1 },
    { ma_sinh_vien: 2, ma_giang_vien: 2, ho_so: 'Hồ sơ Trần Thị B', ma_de_tai: 2 },
    { ma_sinh_vien: 3, ma_giang_vien: 3, ho_so: 'Hồ sơ Lê Văn C', ma_de_tai: 3 },
    { ma_sinh_vien: 4, ma_giang_vien: 4, ho_so: 'Hồ sơ Nguyễn Thị D', ma_de_tai: 4 },
    { ma_sinh_vien: 5, ma_giang_vien: 1, ho_so: 'Hồ sơ Trần Văn E', ma_de_tai: 5 },
    { ma_sinh_vien: 6, ma_giang_vien: 2, ho_so: 'Hồ sơ Lê Thị F', ma_de_tai: 6 },
];

const khoa = [
    { ma_khoa: 'K01', ten_khoa: 'Khoa Công nghệ Thông tin' },
    { ma_khoa: 'K02', ten_khoa: 'Khoa Kỹ thuật Điện tử' },
    { ma_khoa: 'K03', ten_khoa: 'Khoa Khoa học máy tính' },
];

const baiBaoKhoaHoc = [
    { ma_de_tai: 1, ten_de_tai: 'Nghiên cứu về AI', ngay_xuat_ban: '2024-01-15', link_bai_bao: 'http://example.com/bai-bao-1' },
    { ma_de_tai: 2, ten_de_tai: 'Phát triển hệ thống IoT', ngay_xuat_ban: '2024-02-10', link_bai_bao: 'http://example.com/bai-bao-2' },
    { ma_de_tai: 3, ten_de_tai: 'Ứng dụng Machine Learning', ngay_xuat_ban: '2024-03-05', link_bai_bao: 'http://example.com/bai-bao-3' },
    { ma_de_tai: 4, ten_de_tai: 'Phát triển phần mềm giáo dục', ngay_xuat_ban: '2024-04-01', link_bai_bao: 'http://example.com/bai-bao-4' },
    { ma_de_tai: 5, ten_de_tai: 'Nghiên cứu dữ liệu lớn', ngay_xuat_ban: '2024-05-15', link_bai_bao: 'http://example.com/bai-bao-5' },
    { ma_de_tai: 6, ten_de_tai: 'Thực tế ảo trong giáo dục', ngay_xuat_ban: '2024-06-20', link_bai_bao: 'http://example.com/bai-bao-6' },
];

const initialThamDinhBaiBao = [
    { ma_tham_dinh: 1, ma_de_tai: 1, ma_giang_vien: 1, ket_qua: 'Hủy', ngay_tham_dinh: '2024-01-20' },
    { ma_tham_dinh: 2, ma_de_tai: 2, ma_giang_vien: 2, ket_qua: 'Đã duyệt', ngay_tham_dinh: '2024-02-15' },
    { ma_tham_dinh: 3, ma_de_tai: 3, ma_giang_vien: 3, ket_qua: 'Khoa đã duyệt', ngay_tham_dinh: '2024-03-25' },
    { ma_tham_dinh: 4, ma_de_tai: 4, ma_giang_vien: 4, ket_qua: 'Hủy', ngay_tham_dinh: '2024-04-30' },
    { ma_tham_dinh: 5, ma_de_tai: 5, ma_giang_vien: 1, ket_qua: 'Khoa đã duyệt', ngay_tham_dinh: '2024-05-10' },
    { ma_tham_dinh: 6, ma_de_tai: 6, ma_giang_vien: 2, ket_qua: 'Khoa đã duyệt', ngay_tham_dinh: '2024-06-05' },
];

const ListArticleReview = () => {
    const [thamDinhBaiBao, setThamDinhBaiBao] = useState(initialThamDinhBaiBao);
    const [selectedReview, setSelectedReview] = useState(null);
    const [standardInput, setStandardInput] = useState(''); // For the standard input
    const [showStandardModal, setShowStandardModal] = useState(false); // For showing the modal

    const handleViewDetails = (review) => {
        setSelectedReview(review);
    };

    const handleCloseDetails = () => {
        setSelectedReview(null);
    };

    const handleApprove = (reviewId) => {
        setShowStandardModal(true); // Show the modal for entering standards
        setSelectedReview(null); // Clear the selected review to avoid showing the details modal
    };

    const handleConfirmStandard = () => {
        // Update the status to "Đã duyệt" and log the standard input
        setThamDinhBaiBao(prevReviews => 
            prevReviews.map(review => 
                review.ma_tham_dinh === selectedReview ? { ...review, ket_qua: 'Đã duyệt' } : review
            )
        );
        console.log(`Standard for review ${selectedReview}: ${standardInput}`);
        setShowStandardModal(false); // Close the modal
        setStandardInput(''); // Reset the input
    };

    const handleReject = (reviewId) => {
        setThamDinhBaiBao(prevReviews => 
            prevReviews.map(review => 
                review.ma_tham_dinh === reviewId ? { ...review, ket_qua: 'Hủy' } : review
            )
        );
    };

    return (
        <div className="bg-white rounded-lg shadow p-6">
            <table className="min-w-full">
                <thead>
                    <tr className="border-b">
                        <th className="text-left py-4 px-2">Paper Title</th>
                        <th className="text-left py-4 px-2">Author</th>
                        <th className="text-left py-4 px-2">Publication Date</th>
                        <th className="text-left py-4 px-2">Status</th>
                        <th className="text-right py-4 px-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {thamDinhBaiBao.map((review) => {
                        const paper = baiBaoKhoaHoc.find(p => p.ma_de_tai === review.ma_de_tai);
                        const author = tacGia.find(a => a.ma_de_tai === review.ma_de_tai);
                        const student = sinhVien.find(s => s.ma_sinh_vien === author?.ma_sinh_vien);
                        const giangVienDetails = giangVien.find(g => g.ma_giang_vien === review.ma_giang_vien);

                        return (
                            <tr key={review.ma_tham_dinh} className="border-b hover:bg-gray-50">
                                <td className="py-4 px-2">{paper?.ten_de_tai}</td>
                                <td className="py-4 px-2">{student?.ho_ten_sinh_vien || giangVienDetails?.ho_ten_giang_vien}</td>
                                <td className="py-4 px-2">{paper?.ngay_xuat_ban}</td>
                                <td className="py-4 px-2">
                                    <span className={`px-2 py-1 rounded-full text-sm ${
                                        review.ket_qua === 'Đã duyệt' ? 'bg-green-100 text-green-800' :
                                        review.ket_qua === 'Khoa đã duyệt' ? 'bg-blue-100 text-blue-800' :
                                        review.ket_qua === 'Hủy' ? 'bg-red-100 text-red-800' : ''
                                    }`}>
                                        {review.ket_qua}
                                    </span>
                                </td>
                                <td className="py-4 px-2 text-right">
                                    <div className="flex justify-end space-x-2">
                                        {(review.ket_qua === 'Chưa thẩm định' || review.ket_qua === 'Khoa đã duyệt') && (
                                            <>
                                                <button onClick={() => handleApprove(review.ma_tham_dinh)} className="p-2 text-green-600 hover:bg-green-100 rounded-full" title="Duyệt">
                                                    <FaCheck className="w-5 h-5" />
                                                </button>
                                                <button onClick={() => handleReject(review.ma_tham_dinh)} className="p-2 text-red-600 hover:bg-red-100 rounded-full" title="Hủy">
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
                        );
                    })}
                </tbody>
            </table>

            {/* Modal for entering article standards */}
            {showStandardModal && (
                <div className="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                    <div className="bg-white rounded-lg p-6 max-w-lg w-full shadow-lg">
                        <h3 className="text-2xl font-bold mb-4">Enter Approval Standards</h3>
                        <textarea 
                            className="w-full border rounded p-2"
                            value={standardInput}
                            onChange={(e) => setStandardInput(e.target.value)}
                            placeholder="Enter the paper standards here..."
                        />
                        <div className="mt-4 flex justify-center space-x-40"> {/* Added 'flex' to apply flexbox */}
                            <button onClick={handleConfirmStandard} className="p-2 bg-green-500 text-white rounded">Confirm</button>
                            <button onClick={() => setShowStandardModal(false)} className="p-2 bg-red-500 text-white rounded">Cancel</button>
                        </div>

                    </div>
                </div>
            )}

            {/* Modal Chi tiết */}
            {selectedReview && !showStandardModal && ( // Ensure details modal only shows when standard modal is not active
                <div className="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                    <div className="bg-white rounded-lg p-6 max-w-lg w-full shadow-lg">
                        <h3 className="text-2xl font-bold mb-4">Chi tiết thẩm định</h3>
                        <p><strong>Review ID:</strong> {selectedReview.ma_tham_dinh}</p>
                        <p><strong>Status:</strong> {selectedReview.ket_qua}</p>
                        <p><strong>Review Date:</strong> {selectedReview.ngay_tham_dinh}</p>
                        <p><strong>Reviewing Lecturer:</strong> {giangVien.find(g => g.ma_giang_vien === selectedReview.ma_giang_vien)?.ho_ten_giang_vien}</p>
                        <p><strong>Article:</strong> {baiBaoKhoaHoc.find(b => b.ma_de_tai === selectedReview.ma_de_tai)?.ten_de_tai}</p>
                        <p><strong>Publication Date:</strong> {baiBaoKhoaHoc.find(b => b.ma_de_tai === selectedReview.ma_de_tai)?.ngay_xuat_ban}</p>
                        <p><strong>Article Link:</strong> <a href={baiBaoKhoaHoc.find(b => b.ma_de_tai === selectedReview.ma_de_tai)?.link_bai_bao} target="_blank" rel="noopener noreferrer">{baiBaoKhoaHoc.find(b => b.ma_de_tai === selectedReview.ma_de_tai)?.link_bai_bao}</a></p>
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