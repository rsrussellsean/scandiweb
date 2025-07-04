import { useParams, Link } from "react-router-dom";
import { useCart } from "../../context/CartContext";
import { useEffect, useState } from "react";

import { ShoppingCartIcon } from "@heroicons/react/24/outline";
import Loading from "../Loading/Loading";

const ProductList = () => {
  const { addToCart } = useCart();
  const { categoryName } = useParams();
  const [products, setProducts] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetch(`${import.meta.env.VITE_API_URL}/api/products.php`)
      // fetch("/api/products.php")
      .then((res) => res.json())
      .then((data) => {
        // console.log("Fetched data:", data);
        setProducts(data.data.products);
        setLoading(false);
      })
      .catch((err) => {
        console.error("Fetch error:", err);
        setLoading(false);
      });
  }, []);

  const filteredProducts =
    !categoryName || categoryName === "all"
      ? products
      : products.filter(
          (product) =>
            product.category?.toLowerCase() === categoryName.toLowerCase()
        );

  const getHeading = () => {
    if (!categoryName || categoryName === "all") return "All";
    if (categoryName === "clothes") return "Clothes";
    if (categoryName === "tech") return "Technology";
    return categoryName;
  };

  const createDefaultProduct = (product) => {
    const selectedAttributes = {};

    product.attributes?.forEach((attr) => {
      selectedAttributes[attr.name] = attr.items[0].displayValue;
    });

    return {
      id: product.id,
      name: product.name,
      price: product.prices[0],
      imageSrc: product.gallery[0],
      imageAlt: product.name,
      attributes: product.attributes,
      selectedAttributes,
      quantity: 1,
    };
  };

  if (loading) return <Loading />;
  if (!Array.isArray(filteredProducts)) return <p>Product data invalid</p>;

  return (
    <div className="bg-white pt-20 ">
      <h1 className="text-4xl pt-8 pl-4 lg:pl-5">{getHeading()}</h1>
      <div className="mx-auto max-w-screen-md lg:max-w-none px-4 md:px-6 py-12">
        <div className="grid grid-cols-1 gap-x-10 gap-y-16 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 xl:gap-x-12">
          {filteredProducts.map((product) => (
            <div
              key={product.id}
              data-testid={`product-${product.name
                .toLowerCase()
                .replace(/[^a-z0-9]+/g, "-")
                .replace(/^-+|-+$/g, "")}`}
              className="relative group bg-white p-4 shadow-sm md:shadow-none md:hover:shadow-md transition-shadow duration-300 ease-in-out  "
            >
              {!product.inStock && (
                <div className="absolute inset-0 flex items-center justify-center bg-white opacity-40 rounded-lg z-2 cursor-not-allowed">
                  <span className="text-black text-lg pb-20">OUT OF STOCK</span>
                </div>
              )}

              <div>
                <Link to={`/product/${product.id}`}>
                  <img
                    alt={product.name}
                    src={product.gallery[0]}
                    className="aspect-square w-full rounded-lg bg-gray-200 object-cover xl:aspect-7/8"
                  />
                </Link>

                <h3 className="mt-4 text-sm text-gray-700">{product.name}</h3>

                <div className="mt-1 flex items-center justify-between">
                  <p className="text-lg font-medium text-gray-900">
                    {product.prices[0].currency.symbol}
                    {product.prices[0].amount}
                  </p>
                  <button
                    aria-label="Shopping Cart Icon"
                    onClick={() => addToCart(createDefaultProduct(product))}
                    className="p-1 cursor-pointer rounded-full bg-green-500 text-white 
             transition-opacity duration-300 
             md:opacity-0 md:group-hover:opacity-100"
                    disabled={!product.inStock}
                  >
                    <ShoppingCartIcon className="h-5 w-5" />
                  </button>
                </div>
              </div>
            </div>
          ))}
        </div>
      </div>
    </div>
  );
};

export default ProductList;
