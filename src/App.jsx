import { Routes, Route } from "react-router-dom";
import Home from "./pages/Home";
import ProductList from "./components/products/ProductList";
import ProductItem from "./components/products/ProductItem";
import Navbar from "./components/navbar/Navbar";

import "./App.css";

function App() {
  return (
    <div className="container mx-auto">
      <Navbar />
      <Routes>
        <Route path="/" element={<Home />} />
        <Route path="/all" element={<Home />} />
        <Route path="/:categoryName" element={<ProductList />} />
        <Route path="/product/:id" element={<ProductItem />} />
      </Routes>
    </div>
  );
}

export default App;
