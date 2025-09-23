import { StrictMode } from "react";
import { createRoot } from "react-dom/client";
import "./index.css";
import App from "./App.jsx";
import { CartProvider } from "./context/CartContext.jsx";
import { BrowserRouter } from "react-router-dom";
import { ApolloProvider } from "@apollo/client";
import client from "./graphql/client.js";

createRoot(document.getElementById("root")).render(
  <StrictMode>
    <BrowserRouter>
      <ApolloProvider client={client}>
        <CartProvider>
          <App />
        </CartProvider>
      </ApolloProvider>
    </BrowserRouter>
  </StrictMode>
);
