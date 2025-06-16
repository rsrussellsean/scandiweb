import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import Home from "./pages/Home";
import ProductOverview from "./components/products/ProductItem"; // make sure this exists
import Navbar from "./components/navbar/Navbar";

function App() {
  return (
    <Router>
      <div className="container mx-auto px-6">
        <Navbar />
        <Routes>
          {/* Home route */}
          <Route path="/" element={<Home />} />

          {/* Product details route */}
          <Route path="/product/:id" element={<ProductOverview />} />
        </Routes>
      </div>
    </Router>
  );
}

export default App;
