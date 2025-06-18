import { NavLink } from "react-router-dom";
import { Cart } from "../cart/Cart";

const Navbar = () => {
  return (
    <div className="w-full bg-white fixed top-0 left-0 z-50 shadow">
      <nav className="container mx-auto relative z-50 text-black py-6 bg-white px-10 flex items-center justify-between">
        {/* Left Nav Links */}
        <ul className="flex space-x-4">
          <li>
            <NavLink
              to="/"
              end
              className={({ isActive }) =>
                `underline-offset-4 decoration-green-500 decoration-2 hover:underline hover:text-green-500 ${
                  isActive ? "underline text-green-500" : ""
                }`
              }
              {...{
                "data-testid":
                  window.location.pathname === "/"
                    ? "active-category-link"
                    : "category-link",
              }}
            >
              ALL
            </NavLink>
          </li>
          <li>
            <NavLink
              to="/category/clothes"
              end
              className={({ isActive }) =>
                `underline-offset-4 decoration-green-500 decoration-2 hover:underline hover:text-green-500 ${
                  isActive ? "underline text-green-500" : ""
                }`
              }
              {...{
                "data-testid":
                  window.location.pathname === "/"
                    ? "active-category-link"
                    : "category-link",
              }}
            >
              CLOTHES
            </NavLink>
          </li>
          <li>
            <NavLink
              to="/category/tech"
              end
              className={({ isActive }) =>
                `underline-offset-4 decoration-green-500 decoration-2 hover:underline hover:text-green-500 ${
                  isActive ? "underline text-green-500" : ""
                }`
              }
              {...{
                "data-testid":
                  window.location.pathname === "/"
                    ? "active-category-link"
                    : "category-link",
              }}
            >
              TECHNOLOGY
            </NavLink>
          </li>
        </ul>

        {/* Center Logo */}
        <div className="absolute left-1/2 transform -translate-x-1/2 text-2xl font-bold whitespace-nowrap">
          MyLogo
        </div>

        {/* Right Cart */}
        <div>
          <Cart />
        </div>
      </nav>
    </div>
  );
};

export default Navbar;
