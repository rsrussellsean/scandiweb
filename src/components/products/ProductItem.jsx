import { useParams } from "react-router-dom";
import { useState, useEffect, useRef } from "react";
import { useCart } from "../../context/CartContext";
import parse from "html-react-parser";

import productData from "../../json/data.json";
import { ChevronLeftIcon, ChevronRightIcon } from "@heroicons/react/24/solid";

const ProductItem = () => {
  const { id } = useParams();
  // const product = productData.data.products.find((p) => p.id === id);
  const [currentImage, setCurrentImage] = useState(0);
  const [selectedAttributes, setSelectedAttributes] = useState({});
  const { addToCart } = useCart();

  const [product, setProduct] = useState(null); // ✅ added
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetch(`http://localhost:8080/public/product.php?id=${id}`)
      .then((res) => res.json())
      .then((data) => {
        setProduct(data);
        setLoading(false);
      })
      .catch((err) => {
        console.error("Failed to fetch product", err);
        setLoading(false);
      });
  }, [id]);

  if (loading) return <p className="pt-24">Loading product...</p>;
  if (!product) return <p>Product not found</p>;

  const nextImage = () => {
    setCurrentImage((prev) => (prev + 1) % product.gallery.length);
  };

  const prevImage = () => {
    setCurrentImage((prev) =>
      prev === 0 ? product.gallery.length - 1 : prev - 1
    );
  };

  const handleSelectAttribute = (attrName, value, displayValue) => {
    setSelectedAttributes((prev) => ({
      ...prev,
      [attrName]: {
        value,
        displayValue,
      },
    }));
  };

  const handleAddToCart = () => {
    const allSelected = product.attributes.every(
      (attr) => selectedAttributes[attr.name]
    );
    if (!allSelected) {
      alert("Please select all product options (e.g., color, size).");
      return;
    }

    const attributesToCart = {};
    for (const [key, obj] of Object.entries(selectedAttributes)) {
      attributesToCart[key] = obj.displayValue;
    }

    const selectedCurrencyIndex = 0;
    const selectedPrice = product.prices[selectedCurrencyIndex];

    addToCart({
      id: product.id,
      name: product.name,
      price: selectedPrice,
      imageSrc: product.gallery[0],
      imageAlt: product.name,
      selectedAttributes: attributesToCart,
      attributes: product.attributes,
    });
  };

  const allSelected = product.attributes.every(
    (attr) => selectedAttributes[attr.name]
  );

  return (
    <div className="bg-white py-16 px-4 sm:px-6 lg:px-8 pt-30">
      <div className="max-w-7xl mx-auto flex flex-col lg:flex-row gap-10">
        {/* Image Section */}
        <div
          className="w-full lg:w-1/2 flex gap-4"
          data-testid="product-gallery"
        >
          {/* Thumbnails */}
          <div
            className="flex flex-col gap-2 pt-5 overflow-y-auto scrollbar-hide"
            style={{ maxHeight: "800px" }}
          >
            {product.gallery.map((img, index) => (
              <img
                key={index}
                data-index={index}
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
          <div className="relative w-full flex justify-center items-center h-[600px] w-full lg:w-[600px]">
            <img
              src={product.gallery[currentImage]}
              alt={product.name}
              className="rounded-lg object-contain h-full w-full"
            />
            <button
              onClick={prevImage}
              className="absolute top-1/2 left-2 transform -translate-y-1/2 bg-black opacity-50 hover:opacity-80 text-white p-2"
            >
              <ChevronLeftIcon className="w-6 h-6" />
            </button>
            <button
              onClick={nextImage}
              className="absolute top-1/2 right-2 transform -translate-y-1/2 bg-black opacity-50 hover:opacity-80 text-white p-2"
            >
              <ChevronRightIcon className="w-6 h-6" />
            </button>
          </div>
        </div>

        {/* Product Details */}
        <div className="w-full lg:w-1/2 flex flex-col justify-between">
          <div>
            <h1 className="text-2xl font-bold text-gray-900">{product.name}</h1>

            {/* Attributes */}
            {product.attributes?.length > 0 && (
              <div className="mt-6 space-y-6">
                {product.attributes.map((attribute) => (
                  <div
                    key={attribute.id}
                    data-testid={`product-attribute-${attribute.name
                      .toLowerCase()
                      .replace(/[^a-z0-9]+/g, "-")
                      .replace(/(^-|-$)/g, "")}`}
                  >
                    <p className="text-sm font-bold uppercase text-gray-800">
                      {attribute.name}:
                    </p>
                    <div className="mt-2 flex gap-2 flex-wrap">
                      {attribute.items.map((item) => {
                        const isSelected =
                          selectedAttributes[attribute.name]?.value ===
                          item.value;

                        return (
                          <div
                            key={item.id}
                            onClick={() =>
                              handleSelectAttribute(
                                attribute.name,
                                item.value,
                                item.displayValue
                              )
                            }
                            className={`cursor-pointer border rounded flex items-center justify-center transition duration-200 box-border
${
  attribute.name.toLowerCase() === "color"
    ? isSelected
      ? "border-[3px] border-green-500"
      : "border border-gray-300"
    : isSelected
    ? "bg-black text-white border-black"
    : "border border-gray-300 text-gray-700 hover:bg-black hover:text-white"
}
${
  attribute.name.toLowerCase() === "color" ? "w-10 h-10" : "px-4 py-1 text-sm"
}`}
                            style={{
                              backgroundColor:
                                attribute.name.toLowerCase() === "color"
                                  ? item.value
                                  : undefined,
                              color:
                                attribute.name.toLowerCase() === "color"
                                  ? "transparent"
                                  : undefined,
                            }}
                          >
                            {attribute.name.toLowerCase() !== "color" &&
                              ({
                                Small: "S",
                                Medium: "M",
                                Large: "L",
                                "Extra Small": "XS",
                                "Extra Large": "XL",
                              }[item.displayValue] ||
                                item.displayValue)}
                          </div>
                        );
                      })}
                    </div>
                  </div>
                ))}
              </div>
            )}

            {/* Price */}
            <p className="mt-4 text-sm font-bold">PRICE:</p>
            <p className="mt-2 text-xl text-black font-bold">
              {product.prices[0].currency.symbol}
              {product.prices[0].amount}
            </p>

            {/* Add to Cart */}
            <button
              data-testid="add-to-cart"
              className={`mt-10 px-6 py-3 text-white text-sm font-bold w-50 transition 
    ${
      allSelected
        ? "bg-green-500 hover:bg-green-700 cursor-pointer"
        : "bg-gray-400 cursor-not-allowed"
    }`}
              onClick={handleAddToCart}
              disabled={!allSelected}
            >
              ADD TO CART
            </button>

            {/* Description */}
            <div
              className="mt-6 text-black text-sm w-100"
              data-testid="product-description"
            >
              {parse(product.description)}
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default ProductItem;
