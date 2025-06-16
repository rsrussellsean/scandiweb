import { PlusIcon } from "@heroicons/react/24/outline";
import productData from "../json/data.json";
import { Link } from "react-router-dom";

const ProductList = () => {
  const products = productData.data.products;

  return (
    <div className="bg-white">
      <div className="mx-auto max-w2xl px-0 py-16 sm:px-0 sm:py-24">
        <div className="grid grid-cols-1 gap-x-10 gap-y-16 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 xl:gap-x-12">
          {products.map((product) => (
            <div
              key={product.id}
              className="relative group bg-white p-4 shadow-sm"
            >
              {/* Full-card OUT OF STOCK Overlay */}
              {!product.inStock && (
                <div className="absolute inset-0 z-10 flex items-center justify-center bg-white opacity-40 rounded-lg">
                  <span className="text-black text-lg pb-20">OUT OF STOCK</span>
                </div>
              )}

              <div>
                <Link to={`/product/${product.id}`}>
                  <img
                    alt={product.name}
                    src={product.gallery[0]}
                    className="aspect-square w-full rounded-lg bg-gray-200 object-cover group-hover:opacity-85 xl:aspect-7/8"
                  />
                </Link>

                <h3 className="mt-4 text-sm text-gray-700">{product.name}</h3>

                <div className="mt-1 flex items-center justify-between">
                  <p className="text-lg font-medium text-gray-900">
                    {product.prices[0].currency.symbol}
                    {product.prices[0].amount}
                  </p>
                  <button
                    className="p-1 cursor-pointer rounded-full bg-green-300 hover:bg-green-500"
                    disabled={!product.inStock}
                  >
                    <PlusIcon className="h-5 w-5 text-white" />
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
