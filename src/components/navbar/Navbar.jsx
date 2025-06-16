import { ShoppingCartIcon } from "@heroicons/react/24/outline";
import { Link } from "react-router-dom";

const Navbar = () => {
  return (
    <nav className="pt-8 relative text-black py-4 flex items-center justify-between">
      <ul className="flex space-x-6">
        <li>
          <Link
            to={"/"}
            className="hover:underline underline-offset-4 decoration-green-500"
          >
            ALL
          </Link>
        </li>
        <li>
          <Link
            to={"category/clothes"}
            className="hover:underline underline-offset-4 decoration-green-500"
          >
            CLOTHES
          </Link>
        </li>
        <li>
          <Link
            to={"category/technology"}
            className="hover:underline underline-offset-4 decoration-green-500"
          >
            TECHNOLOGY
          </Link>
        </li>
      </ul>

      <div className="absolute left-1/2 transform -translate-x-1/2 text-2xl font-bold">
        MyLogo
      </div>

      <div>
        <button className="relative hover:text-green-500">
          <ShoppingCartIcon className="h-6 w-6" />
          <span className="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full px-1">
            3
          </span>
        </button>
      </div>
    </nav>
  );
};

export default Navbar;
