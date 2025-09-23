import { useState } from "react";
import { Routes, Route, Navigate } from "react-router-dom";
import Home from "./pages/Home";
import ProductList from "./components/products/ProductList";
import ProductItem from "./components/products/ProductItem";
import Navbar from "./components/navbar/Navbar";
import Login from "./pages/Login";
import Fillup from "./components/fillup/Fillup";

import "./App.css";
import SignupPage from "./components/fillup/Signup";

function App() {
  const [loggedIn, setLoggedIn] = useState(false);

  const handleLogin = () => {
    setLoggedIn(true);
  };

  const handleLogout = () => {
    setLoggedIn(false);
  };

  return (
    <div className="container mx-auto">
      <Navbar loggedIn={loggedIn} onLogout={handleLogout} />

      <Routes>
        {!loggedIn ? (
          <>
            <Route path="/login" element={<Login onLogin={handleLogin} />} />
            <Route path="/signup" element={<SignupPage />} />
            <Route path="*" element={<Navigate to="/login" replace />} />
          </>
        ) : (
          <>
            <Route path="/" element={<Navigate to="/all" replace />} />
            <Route path="/all" element={<Home />} />
            <Route path="/:categoryName" element={<ProductList />} />
            <Route path="/product/:id" element={<ProductItem />} />
            {/* Redirect login/signup to home if already logged in */}
            <Route path="/login" element={<Navigate to="/all" replace />} />
            <Route path="/signup" element={<Navigate to="/all" replace />} />
            <Route path="*" element={<Navigate to="/all" replace />} />
          </>
        )}
      </Routes>
    </div>
  );
}

export default App;
