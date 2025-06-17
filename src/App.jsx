import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import Home from "./pages/Home";
import ProductList from "./components/products/ProductList";
import ProductOverview from "./components/products/ProductItem";
import Navbar from "./components/navbar/Navbar";

import "./App.css";

function App() {
  return (
    <Router>
      <div className="container mx-auto">
        <Navbar />
        <Routes>
          {/* Home route */}
          <Route path="/" element={<Home />} />

          {/* <Route path="/" element={<ProductList />} /> */}
          <Route path="/category/:categoryName" element={<ProductList />} />
          {/* Product details route */}
          <Route path="/product/:id" element={<ProductOverview />} />
        </Routes>
      </div>
    </Router>
  );
}

export default App;
