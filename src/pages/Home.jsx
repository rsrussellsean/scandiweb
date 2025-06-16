import React from "react";
import Navbar from "../components/navbar/Navbar";
import ProductList from "../components/products/ProductList";
const Home = () => {
  return (
    <div>
      <div className="">
        <h1 className="text-4xl pt-5">All</h1>
        <ProductList />
      </div>
    </div>
  );
};

export default Home;
