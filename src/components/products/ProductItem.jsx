import { useParams } from "react-router-dom";
import { useState } from "react";
import productData from "../json/data.json";
import { ChevronLeftIcon, ChevronRightIcon } from "@heroicons/react/24/solid";

const ProductOverview = () => {
  const { id } = useParams();
  const product = productData.data.products.find((p) => p.id === id);
  const [currentImage, setCurrentImage] = useState(0);

  if (!product) return <p>Product not found</p>;

  const nextImage = () => {
    setCurrentImage((prev) => (prev + 1) % product.gallery.length);
  };

  const prevImage = () => {
    setCurrentImage((prev) =>
      prev === 0 ? product.gallery.length - 1 : prev - 1
    );
  };

  return (
    <div className="bg-white py-16 px-4 sm:px-6 lg:px-8">
      <div className="max-w-7xl mx-auto flex flex-col lg:flex-row gap-10">
        {/* Image Left with Slider + Thumbnails */}
        <div className="w-full lg:w-1/2 flex gap-4">
          {/* Thumbnails */}
          <div className="flex flex-col gap-2">
            {product.gallery.map((img, index) => (
              <img
                key={index}
                src={img}
                alt={`${product.name} ${index}`}
                onClick={() => setCurrentImage(index)}
                className={`w-16 h-16 object-cover rounded cursor-pointer border ${
                  currentImage === index ? "border-black" : "border-gray-300"
                }`}
              />
            ))}
          </div>

          {/* Main Image with Arrows */}
          <div className="relative w-full">
            <img
              src={product.gallery[currentImage]}
              alt={product.name}
              className="w-full rounded-lg object-cover aspect-square"
            />

            {/* Left Arrow */}
            <button
              onClick={prevImage}
              className="absolute top-1/2 left-2 transform -translate-y-1/2 bg-black opacity-50 hover:opacity-80 cursor-pointer text-white p-2"
            >
              <ChevronLeftIcon className="w-6 h-6" />
            </button>

            {/* Right Arrow */}
            <button
              onClick={nextImage}
              className="absolute top-1/2 right-2 transform -translate-y-1/2 bg-black opacity-50 hover:opacity-80 cursor-pointer text-white p-2"
            >
              <ChevronRightIcon className="w-6 h-6" />
            </button>
          </div>
        </div>

        {/* Details Right */}
        <div className="w-full lg:w-1/2 flex flex-col justify-between">
          <div>
            {/* Title */}
            <h1 className="text-2xl font-bold text-gray-900">{product.name}</h1>
            {/* Attributes (e.g., sizes) */}
            {product.attributes?.length > 0 && (
              <div className="mt-6">
                <p className="text-sm font-bold uppercase text-gray-800">
                  {product.attributes[0].name}
                </p>
                <div className="mt-2 flex gap-2 flex-wrap ">
                  {product.attributes[0].items.map((item) => (
                    <span
                      key={item.id}
                      className="px-3 py-1 border border-gray-300 rounded text-sm text-gray-700 hover:bg-black hover:text-white cursor-pointer
"
                    >
                      {item.displayValue}
                    </span>
                  ))}
                </div>
              </div>
            )}
            {/* Color */}
            {product.attributes?.color > 0 && (
              <div className="mt-6">
                <h3 className="font-medium text-sm text-gray-800">
                  {product.attributes[0].name}
                </h3>
                <div className="mt-2 flex gap-2 flex-wrap">
                  {product.attributes[0].items.map((item) => (
                    <span
                      key={item.id}
                      className="px-3 py-1 border border-gray-300 rounded text-sm text-gray-700"
                    >
                      {item.displayValue}
                    </span>
                  ))}
                </div>
              </div>
            )}
            {/* Price */}
            <p className="mt-4 text-sm font-bold">PRICE:</p>
            <p className="mt-2 text-xl text-black font-bold">
              {product.prices[0].currency.symbol}
              {product.prices[0].amount}
            </p>

            {/* Add to Cart Button */}
            <button className="mt-10 px-6 py-3 bg-green-500 hover:bg-green-700 text-white text-sm  w-50">
              ADD TO CART
            </button>

            {/* Description */}
            <div
              className="mt-6 text-gray-700 text-sm"
              dangerouslySetInnerHTML={{ __html: product.description }}
            />
          </div>
        </div>
      </div>
    </div>
  );
};

export default ProductOverview;
