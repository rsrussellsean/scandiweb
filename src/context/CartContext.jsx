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
    // Convert display values to a key string for easier comparison
    const getKey = (item) =>
      item.id +
      JSON.stringify(item.selectedAttributes || {})
        .replace(/\s+/g, "")
        .toLowerCase();

    const updatedKey =
      itemToUpdate.id +
      JSON.stringify(newSelectedAttrs).replace(/\s+/g, "").toLowerCase();

    const updatedItems = [];
    let merged = false;

    for (const item of cartItems) {
      const currentKey = getKey(item);

      if (item === itemToUpdate) continue; // Skip item being updated

      if (currentKey === updatedKey) {
        // Found a matching item with same new attributes
        updatedItems.push({
          ...item,
          quantity: item.quantity + itemToUpdate.quantity,
        });
        merged = true;
      } else {
        updatedItems.push(item);
      }
    }

    if (!merged) {
      updatedItems.push({
        ...itemToUpdate,
        selectedAttributes: newSelectedAttrs,
      });
    }

    setCartItems(updatedItems);
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
