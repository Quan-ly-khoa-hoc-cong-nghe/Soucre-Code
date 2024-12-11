import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { FaEye, FaCheck, FaTimes } from "react-icons/fa";

const ThamDinhBaiBao = () => {
    const [articles, setArticles] = useState([]); // State to store fetched articles
    const [evaluationData, setEvaluationData] = useState([]); // Dữ liệu thẩm định bài báo
    const [loading, setLoading] = useState(true); // State to handle loading state

    useEffect(() => {
        // Fetch articles and evaluation data together
        Promise.all([
            axios.get('http://localhost/Soucre-Code/BackEnd/Api/ThamDinhBaiBaoApi/BaiBaoKhoaHoc_Api.php?action=get'),
            axios.get('http://localhost/Soucre-Code/BackEnd/Api/ThamDinhBaiBaoApi/ThamDinhBaiBao_Api.php?action=get')
        ])
        .then(([articlesResponse, evaluationResponse]) => {
            setArticles(articlesResponse.data); // Lưu bài báo vào state
            setEvaluationData(evaluationResponse.data); // Lưu dữ liệu thẩm định vào state
            setLoading(false); // Dừng loading
        })
        .catch((error) => {
            console.error("Error fetching data:", error);
            setLoading(false); // Dừng loading trong trường hợp có lỗi
        });
    }, []);

    if (loading) {
        return <div>Loading...</div>; // Hiển thị loading khi đang tải dữ liệu
    }

    // Hàm để kiểm tra xem bài báo đã được thẩm định hay chưa và thêm màu sắc
    const getEvaluationStatus = (maBaiBao) => {
        const evaluation = evaluationData.find(e => e.MaBaiBao === maBaiBao);
        if (evaluation) {
            return (
                <span className="bg-green-100 text-green-800 px-2 py-1 rounded-full">Đã thẩm định</span>
            );
        } else {
            return (
                <span className="bg-blue-100 text-blue-800 px-2 py-1 rounded-full">Chưa thẩm định</span>
            );
        }
    };

    return (
        <div className="min-h-screen bg-gray-50 p-8">
            <div className="container mx-auto">
                <div className="flex items-center justify-between mb-6">
                    <h1 className="text-2xl font-semibold">Thẩm định bài báo</h1>
                </div>

                {/* Table displaying articles */}
                <div className="overflow-x-auto bg-white shadow-lg rounded-lg">
                    <table className="min-w-full table-auto">
                        <thead className="bg-gray-200">
                            <tr>
                                <th className="px-4 py-2 text-left">Mã bài báo</th>
                                <th className="px-4 py-2 text-left">Tên bài báo</th>
                                <th className="px-4 py-2 text-left">Ngày xuất bản</th>
                                <th className="px-4 py-2 text-left">URL bài báo</th>
                                <th className="px-4 py-2 text-left">Mã hồ sơ</th>
                                <th className="px-4 py-2 text-left">Trạng thái thẩm định</th>
                                <th className="px-4 py-2 text-left">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            {articles.length > 0 ? (
                                articles.map((article) => (
                                    <tr key={article.MaBaiBao} className="border-t hover:bg-gray-50">
                                        <td className="px-4 py-2">{article.MaBaiBao}</td>
                                        <td className="px-4 py-2">{article.TenBaiBao}</td>
                                        <td className="px-4 py-2">{article.NgayXuatBan}</td>
                                        <td className="px-4 py-2">
                                            <a
                                                href={article.urlBaiBao}
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                className="text-blue-500 hover:underline"
                                            >
                                                {article.urlBaiBao}
                                            </a>
                                        </td>
                                        <td className="px-4 py-2">{article.MaHoSo}</td>
                                        <td className="px-4 py-2">{getEvaluationStatus(article.MaBaiBao)}</td>
                                        <td className="py-4 px-2 text-right">
                                            <div className="flex justify-end space-x-2">
                                                {/* Nếu bài báo đã thẩm định, chỉ hiển thị nút "Khen thưởng" */}
                                                {evaluationData.find(e => e.MaBaiBao === article.MaBaiBao) ? (
                                                    <button
                                                        className="text-green-600 hover:underline text-sm font-medium"
                                                    >
                                                        Khen thưởng
                                                    </button>
                                                ) : (
                                                    <>
                                                      

                                                        <button
                                                            className="p-2 text-red-600 hover:bg-red-100 rounded-full"
                                                            title="Reject"
                                                        >
                                                            Thẩm định
                                                        </button>
                                                    </>
                                                )}

                                                <button
                                                    className="p-2 text-blue-600 hover:bg-blue-100 rounded-full"
                                                    title="View Details"
                                                >
                                                    <FaEye className="w-5 h-5" />
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                ))
                            ) : (
                                <tr>
                                    <td colSpan="7" className="px-4 py-2 text-center text-gray-500">
                                        Không có bài báo nào.
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    );
};

export default ThamDinhBaiBao;
