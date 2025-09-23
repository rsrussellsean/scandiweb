import { useEffect, useState, useRef } from "react";
import { NavLink, useLocation, useNavigate } from "react-router-dom";
import { useQuery } from "@apollo/client";
import { GET_ALL_CATEGORIES } from "../../graphql/queries";
import { Cart } from "../cart/Cart";
import ShoppingBag from "../../assets/bag.svg";

export default function Navbar({ loggedIn, onLogout }) {
  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
  const location = useLocation();
  const navigate = useNavigate();

  const { data, loading, error } = useQuery(GET_ALL_CATEGORIES, {
    errorPolicy: 'all'
  });

  // Get categories from GraphQL response, provide fallback
  const categories = data?.categories || [];

  if (error) {
    console.error("Failed to fetch categories", error);
  }

  const CategoryNavLink = ({ to, children, onClick, className }) => {
    const ref = useRef();
    const isActive = location.pathname === to;

    useEffect(() => {
      if (ref.current) {
        ref.current.setAttribute(
          "data-testid",
          isActive ? "active-category-link" : "category-link"
        );
      }
    }, [isActive]);

    return (
      <NavLink ref={ref} to={to} onClick={onClick} className={className}>
        {children}
      </NavLink>
    );
  };

  const handleLogoutClick = () => {
    onLogout();
    navigate("/login");
  };

  return (
    <div className="w-full bg-white fixed top-0 left-0 z-50 shadow">
      <nav className="max-w-screen-2xl px-5 mx-auto relative z-50 text-black py-6 bg-white flex items-center justify-between">
        <div className="flex items-center">
          {/* Burger Button - Mobile */}
          <div className="sm:hidden mr-4">
            <button
              onClick={() => setIsMobileMenuOpen(!isMobileMenuOpen)}
              className="p-2 rounded-md text-gray-700 hover:bg-gray-200"
            >
              {isMobileMenuOpen ? "✖" : "☰"}
            </button>
          </div>

          {/* Desktop Navigation */}
          <ul className="hidden sm:flex space-x-4">
            {categories.map((cat) => {
              const categoryPath =
                cat.name.toLowerCase() === "all"
                  ? "/all"
                  : `/${cat.name.toLowerCase()}`;
              return (
                <li key={cat.id}>
                  <CategoryNavLink
                    to={categoryPath}
                    className={({ isActive }) =>
                      `block py-2 underline-offset-4 decoration-green-500 decoration-2 hover:underline hover:text-green-500 ${
                        isActive ? "underline text-green-500" : "text-black"
                      }`
                    }
                  >
                    {cat.name.toUpperCase()}
                  </CategoryNavLink>
                </li>
              );
            })}
          </ul>
        </div>

        {/* Center Logo */}
        <div className="absolute left-1/2 transform -translate-x-1/2">
          <img src={ShoppingBag} alt="Shopping Bag" width="30" height="30" />
        </div>

        {/* Right Section: Cart + Logout */}
        <div className="ml-auto flex items-center space-x-4">
          {loggedIn && <Cart />}
          {loggedIn && (
            <button
              onClick={handleLogoutClick}
              className="bg-gray-600 text-white px-3 py-1 rounded hover:bg-red-500 ml-4 cursor-pointer"
              data-testid="logout-button"
            >
              Logout
            </button>
          )}
        </div>
      </nav>

      {/* Mobile Menu Dropdown */}
      {isMobileMenuOpen && (
        <div className="sm:hidden bg-white px-4 pb-4">
          <ul className="space-y-2">
            {categories.map((cat) => (
              <li key={cat.id}>
                <CategoryNavLink
                  to={
                    cat.name.toLowerCase() === "all"
                      ? "/all"
                      : `/${cat.name.toLowerCase()}`
                  }
                  onClick={() => setIsMobileMenuOpen(false)}
                  className={({ isActive }) =>
                    `block py-2 text-black hover:text-green-500 ${
                      isActive ? "font-bold text-green-500" : ""
                    }`
                  }
                >
                  {cat.name.toUpperCase()}
                </CategoryNavLink>
              </li>
            ))}
          </ul>
        </div>
      )}
    </div>
  );
}
