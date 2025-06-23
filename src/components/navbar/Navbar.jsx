import { useEffect, useState } from "react";
import { NavLink } from "react-router-dom";
import { Cart } from "../cart/Cart";
import ShoppingBag from "../../assets/bag.svg";

const Navbar = () => {
  const [categories, setCategories] = useState([]);

  useEffect(() => {
    fetch("http://localhost:8080/public/categories.php")
      .then((res) => res.json())
      .then((data) => {
        setCategories(data);
      })
      .catch((err) => console.error("Failed to fetch categories", err));
  }, []);

  return (
    <div className="w-full bg-white fixed top-0 left-0 z-50 shadow">
      <nav className="container mx-auto relative z-50 text-black py-6 bg-white flex items-center justify-between">
        <ul className="flex space-x-4">
          {categories.map((cat) => (
            <li key={cat.id} data-testid="category-link">
              <NavLink
                to={
                  cat.name.toLowerCase() === "all"
                    ? "/"
                    : `/category/${cat.name.toLowerCase()}`
                }
                end
                className={({ isActive }) =>
                  `underline-offset-4 decoration-green-500 decoration-2 hover:underline hover:text-green-500 ${
                    isActive ? "underline text-green-500" : ""
                  }`
                }
              >
                {cat.name.toUpperCase()}
              </NavLink>
            </li>
          ))}
        </ul>

        {/* Center Logo */}
        <div className="absolute left-1/2 transform -translate-x-1/2 text-2xl font-bold whitespace-nowrap">
          <img
            src={ShoppingBag}
            alt="Shopping Bag"
            style={{ width: "30px", height: "30px" }}
          />
        </div>

        {/* Cart */}
        <div>
          <Cart />
        </div>
      </nav>
    </div>
  );
};

export default Navbar;
