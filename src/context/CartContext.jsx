import { createContext, useContext, useState, useEffect } from "react";

const CartContext = createContext();

export const CartProvider = ({ children }) => {
  const [cartItems, setCartItems] = useState(() => {
    const stored = localStorage.getItem("cartItems");
    return stored ? JSON.parse(stored) : [];
  });

  useEffect(() => {
    localStorage.setItem("cartItems", JSON.stringify(cartItems));
  }, [cartItems]);

  const addToCart = (product) => {
    setCartItems((prev) => {
      // Check if an identical item (same id + same attributes) already exists
      const existing = prev.find((item) => {
        if (item.id !== product.id) return false;

        // Compare attribute keys and values
        const keys = Object.keys(product).filter(
          (key) =>
            ![
              "id",
              "name",
              "price",
              "imageSrc",
              "imageAlt",
              "quantity",
            ].includes(key)
        );

        return (
          keys.every((key) => item[key] === product[key]) &&
          keys.length ===
            Object.keys(item).filter(
              (k) =>
                ![
                  "id",
                  "name",
                  "price",
                  "imageSrc",
                  "imageAlt",
                  "quantity",
                ].includes(k)
            ).length
        );
      });

      if (existing) {
        return prev.map((item) =>
          item === existing ? { ...item, quantity: item.quantity + 1 } : item
        );
      } else {
        return [...prev, { ...product, quantity: 1 }];
      }
    });
  };

  const removeFromCart = (productToRemove) => {
    setCartItems((prev) =>
      prev.filter((item) => {
        if (item.id !== productToRemove.id) return true;

        // Check if all attributes match
        const keys = Object.keys(item).filter(
          (key) =>
            ![
              "id",
              "name",
              "price",
              "imageSrc",
              "imageAlt",
              "quantity",
            ].includes(key)
        );

        return (
          !keys.every((key) => item[key] === productToRemove[key]) ||
          keys.length !==
            Object.keys(productToRemove).filter(
              (key) =>
                ![
                  "id",
                  "name",
                  "price",
                  "imageSrc",
                  "imageAlt",
                  "quantity",
                ].includes(key)
            ).length
        );
      })
    );
  };

  const updateQty = (productToUpdate, newQty) => {
    setCartItems((prev) =>
      prev.map((item) => {
        const keys = Object.keys(item).filter(
          (key) =>
            ![
              "id",
              "name",
              "price",
              "imageSrc",
              "imageAlt",
              "quantity",
            ].includes(key)
        );

        const isSame =
          item.id === productToUpdate.id &&
          keys.every((key) => item[key] === productToUpdate[key]) &&
          keys.length ===
            Object.keys(productToUpdate).filter(
              (key) =>
                ![
                  "id",
                  "name",
                  "price",
                  "imageSrc",
                  "imageAlt",
                  "quantity",
                ].includes(key)
            ).length;

        return isSame ? { ...item, quantity: newQty } : item;
      })
    );
  };

  const clearCart = () => setCartItems([]);

  return (
    <CartContext.Provider
      value={{ cartItems, addToCart, removeFromCart, updateQty, clearCart }}
    >
      {children}
    </CartContext.Provider>
  );
};

export const useCart = () => useContext(CartContext);
