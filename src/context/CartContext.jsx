import { createContext, useContext, useState, useEffect } from "react";

const CartContext = createContext();

export const CartProvider = ({ children, initialCartItems = [] }) => {
  const [cartItems, setCartItems] = useState(() => {
    return initialCartItems.length
      ? initialCartItems
      : JSON.parse(localStorage.getItem("cartItems")) || [];
  });

  useEffect(() => {
    localStorage.setItem("cartItems", JSON.stringify(cartItems));
  }, [cartItems]);

  const isSameCartItem = (a, b) =>
    a.id === b.id &&
    JSON.stringify(a.selectedAttributes) ===
      JSON.stringify(b.selectedAttributes);

  const addToCart = (product) => {
    setCartItems((prev) => {
      const existingItemIndex = prev.findIndex((item) =>
        isSameCartItem(item, product)
      );

      if (existingItemIndex !== -1) {
        const updatedCart = [...prev];
        updatedCart[existingItemIndex] = {
          ...updatedCart[existingItemIndex],
          quantity: updatedCart[existingItemIndex].quantity + 1,
        };
        return updatedCart;
      } else {
        return [...prev, { ...product, quantity: 1 }];
      }
    });
  };

  const removeFromCart = (productToRemove) => {
    setCartItems((prev) =>
      prev.filter((item) => !isSameCartItem(item, productToRemove))
    );
  };

  const updateQty = (productToUpdate, newQty) => {
    setCartItems((prev) =>
      prev.map((item) =>
        isSameCartItem(item, productToUpdate)
          ? { ...item, quantity: newQty }
          : item
      )
    );
  };

  const updateAttributes = (itemToUpdate, newSelectedAttrs) => {
    setCartItems((prevCart) => {
      const updatedKey = JSON.stringify({
        id: itemToUpdate.id,
        selectedAttributes: newSelectedAttrs,
      });

      let existingIndex = -1;

      const newCart = prevCart.reduce((acc, item, index) => {
        const currentKey = JSON.stringify({
          id: item.id,
          selectedAttributes: item.selectedAttributes,
        });

        if (item === itemToUpdate) {
          // We'll update this later if needed
          return acc;
        }

        if (currentKey === updatedKey) {
          existingIndex = acc.length; // Mark where to merge
        }

        acc.push(item);
        return acc;
      }, []);

      if (existingIndex !== -1) {
        // Merge with existing
        newCart[existingIndex] = {
          ...newCart[existingIndex],
          quantity: newCart[existingIndex].quantity + itemToUpdate.quantity,
        };
      } else {
        // Insert updated item in the same position as original
        const originalIndex = prevCart.findIndex((i) => i === itemToUpdate);
        newCart.splice(originalIndex, 0, {
          ...itemToUpdate,
          selectedAttributes: newSelectedAttrs,
        });
      }

      return newCart;
    });
  };

  const clearCart = () => setCartItems([]);

  return (
    <CartContext.Provider
      value={{
        cartItems,
        addToCart,
        removeFromCart,
        updateQty,
        updateAttributes,
        clearCart,
      }}
    >
      {children}
    </CartContext.Provider>
  );
};

export const useCart = () => useContext(CartContext);
