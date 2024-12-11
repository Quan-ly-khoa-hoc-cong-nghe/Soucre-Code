import React from "react";
import ProductManager from "./ProductManager";


const Product = () => {
  return (
    <div className="min-h-screen bg-gray-50 p-8">
      <div className="container mx-auto">
        <div className="flex items-center justify-between mb-6">
          <h1 className="text-2xl font-semibold">Quản lý sản phẩm sinh viên</h1>
          
        </div>
        <ProductManager />
      </div>
    </div>
  );
};

export default Product;
