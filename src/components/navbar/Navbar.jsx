import { useEffect, useState } from "react";
import { NavLink } from "react-router-dom";
import { Cart } from "../cart/Cart";
import ShoppingBag from "../../assets/bag.svg";

const Navbar = () => {
  const [categories, setCategories] = useState([]);
  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);

  useEffect(() => {
    // fetch(`${import.meta.env.VITE_API_URL}/api/categories.php`)
    fetch("/api/categories.php")
      .then((res) => res.json())
      .then((data) => {
        setCategories(data);
        // console.log(data);
      })
      .catch((err) => console.error("Failed to fetch categories", err));
  }, []);

  return (
    <div className="w-full bg-white fixed top-0 left-0 z-50 shadow">
      <nav className="max-w-screen-2xl px-5 mx-auto relative z-50 text-black py-6 bg-white flex items-center justify-between">
        <div className="flex items-center">
          {/* Burger Button - Mobile */}
          <div className="sm:hidden mr-4">
            <button
              aria-label="Menu button"
              type="button"
              onClick={() => setIsMobileMenuOpen(!isMobileMenuOpen)}
              className="inline-flex items-center justify-center rounded-md p-2 text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-green-500"
              aria-controls="mobile-menu"
              aria-expanded={isMobileMenuOpen}
            >
              <span className="sr-only">Open main menu</span>
              <svg
                className={`${isMobileMenuOpen ? "hidden" : "block"} size-6`}
                fill="none"
                viewBox="0 0 24 24"
                strokeWidth={1.5}
                stroke="currentColor"
              >
                <path
                  strokeLinecap="round"
                  strokeLinejoin="round"
                  d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"
                />
              </svg>
              <svg
                className={`${isMobileMenuOpen ? "block" : "hidden"} size-6`}
                fill="none"
                viewBox="0 0 24 24"
                strokeWidth={1.5}
                stroke="currentColor"
              >
                <path
                  strokeLinecap="round"
                  strokeLinejoin="round"
                  d="M6 18L18 6M6 6l12 12"
                />
              </svg>
            </button>
          </div>

          {/* Desktop Navigation */}
          <ul className="hidden sm:flex space-x-4">
            {categories.map((cat) => (
              <NavLink
                data-testid="category-link"
                key={cat.id}
                to={
                  cat.name.toLowerCase() === "all"
                    ? "/all"
                    : `/${cat.name.toLowerCase()}`
                }
              >
                {({ isActive }) => (
                  <li
                    role="link"
                    data-testid="category-link"
                    {...(isActive && {
                      "data-testid": "active-category-link",
                    })}
                    className={`block py-2 underline-offset-4 decoration-green-500 decoration-2 hover:underline hover:text-green-500 ${
                      isActive ? "underline text-green-500" : "text-black"
                    }`}
                  >
                    {cat.name.toUpperCase()}
                  </li>
                )}
              </NavLink>
            ))}
          </ul>
        </div>

        {/* Center Logo */}
        <div className="absolute left-1/2 transform -translate-x-1/2 text-2xl font-bold whitespace-nowrap">
          <img
            src={ShoppingBag}
            alt="Shopping Bag"
            style={{ width: "30px", height: "30px" }}
          />
        </div>

        {/* Right Section: Cart */}
        <div className="ml-auto pr-4">
          <Cart />
        </div>
      </nav>

      {/* Mobile Menu Dropdown */}
      {isMobileMenuOpen && (
        <div id="mobile-menu" className="sm:hidden bg-white px-4 pb-4">
          <ul className="space-y-2">
            {categories.map((cat) => (
              <li key={cat.id}>
                <NavLink
                  to={
                    cat.name.toLowerCase() === "all"
                      ? "/all"
                      : `/${cat.name.toLowerCase()}`
                  }
                  onClick={() => setIsMobileMenuOpen(false)}
                  children={({ isActive }) => (
                    <a
                      data-testid={
                        isActive ? "active-category-link" : "category-link"
                      }
                      className={`block py-2 text-black hover:text-green-500 ${
                        isActive ? "font-bold text-green-500" : ""
                      }`}
                    >
                      {cat.name.toUpperCase()}
                    </a>
                  )}
                />
              </li>
            ))}
          </ul>
        </div>
      )}
    </div>
  );
};

export default Navbar;
