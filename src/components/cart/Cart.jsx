import { useState, useRef, Fragment } from "react";
import { createPortal } from "react-dom";
import "./Cart.css";

import { useCart } from "../../context/CartContext";
import {
  Dialog,
  DialogBackdrop,
  DialogPanel,
  DialogTitle,
  Transition,
} from "@headlessui/react";
import {
  XMarkIcon,
  ShoppingCartIcon,
  PlusIcon,
  MinusIcon,
} from "@heroicons/react/24/outline";

export const Cart = () => {
  // const [open, setOpen] = useState(false);

  const {
    cartItems,
    removeFromCart,
    updateQty,
    updateAttributes,
    clearCart,
    isCartOpen,
    setIsCartOpen,
  } = useCart();
  const cartBtnRef = useRef(null);

  const [showSuccessModal, setShowSuccessModal] = useState(false);
  const [orderId, setOrderId] = useState(null);
  const [orderedItems, setOrderedItems] = useState([]);

  const handlePlaceOrder = async () => {
    try {
      // const res = await fetch(
      //   `${import.meta.env.VITE_API_URL}/api/place_order.php`,
      //   {
      const res = await fetch("/api/place_order.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ items: cartItems }),
      });

      const result = await res.json();

      if (res.ok) {
        setOrderId(result.orderId);
        setOrderedItems(cartItems);
        setShowSuccessModal(true);
        // setOpen(false);
        setIsCartOpen(false);
        clearCart();
      } else {
        alert("Failed to place order: " + result.error);
      }
    } catch (err) {
      console.error("Error placing order", err);
      alert("Unexpected error placing order");
    }
  };

  return (
    <div>
      <button
        ref={cartBtnRef}
        data-testid="cart-btn"
        className="relative hover:text-green-500 cursor-pointer z-50"
        onClick={() => setIsCartOpen((prev) => !prev)}
      >
        <ShoppingCartIcon className="h-6 w-6" />
        {cartItems.reduce((acc, item) => acc + item.quantity, 0) > 0 && (
          <span className="absolute -top-2 -right-2 bg-black text-white text-xs rounded-full px-1">
            {cartItems.reduce((acc, item) => acc + item.quantity, 0)}
          </span>
        )}
      </button>

      {createPortal(
        <Transition show={isCartOpen} as="div">
          <Dialog
            as="div"
            className="relative z-20"
            open={isCartOpen}
            onClose={(value) => {
              if (
                document.activeElement === cartBtnRef.current ||
                cartBtnRef.current?.contains(document.activeElement)
              ) {
                return;
              }
              setIsCartOpen(value);
            }}
            static
          >
            {/* Backdrop transition */}
            <Transition.Child
              as="div"
              enter="ease-out duration-300"
              enterFrom="opacity-0"
              enterTo="opacity-100"
              leave="ease-in duration-200"
              leaveFrom="opacity-100"
              leaveTo="opacity-0"
            >
              <DialogBackdrop className="fixed inset-0 bg-gray-500/75" />
            </Transition.Child>
            <div className="fixed inset-0 overflow-hidden">
              <div className="absolute inset-0 overflow-hidden">
                <Transition.Child
                  as="div"
                  enter="transform transition ease-in-out duration-300"
                  enterFrom="-translate-y-full"
                  enterTo="translate-y-0"
                  leave="transform transition ease-in-out duration-300"
                  leaveFrom="translate-y-0"
                  leaveTo="-translate-y-full"
                  className="my-panel"
                  // className="pointer-events-auto fixed right-0 xl:right-40 max-w-[500px] h-[100vh] w-screen overflow-hidden z-40"
                >
                  {/* <div
                    className="pointer-events-auto fixed lg:right-30 mt-19
               sm:max-w-[500px] sm:h-[80vh] bg-white shadow-xl transform transition duration-500 ease-in-out "
                  > */}
                  <DialogPanel
                    data-testid="cart-overlay"
                    className="pointer-events-auto w-screen max-w-[500px] transform transition duration-500 ease-in-out mr-40 pt-19"
                  >
                    {/* Height */}
                    <div className="flex h-[80vh] flex-col overflow-y-auto bg-white shadow-xl ">
                      <div className="flex-1 overflow-y-auto px-4 py-6 sm:px-6">
                        <div className="flex items-start justify-between">
                          <DialogTitle className="text-lg font-bold text-gray-900">
                            My Bag
                            {cartItems.length > 0 && (
                              <span className="pl-2 font-normal">
                                {cartItems.reduce(
                                  (acc, item) => acc + item.quantity,
                                  0
                                )}{" "}
                                {cartItems.reduce(
                                  (acc, item) => acc + item.quantity,
                                  0
                                ) > 1
                                  ? "items"
                                  : "item"}
                              </span>
                            )}
                          </DialogTitle>

                          <div className="ml-3 flex h-7 items-center">
                            <button
                              aria-label="X button"
                              type="button"
                              onClick={() => setIsCartOpen(false)}
                              className="cursor-pointer p-2 text-gray-400 hover:text-gray-500"
                            >
                              <XMarkIcon className="w-6 h-6" />
                            </button>
                          </div>
                        </div>

                        <div className="flex-1">
                          {cartItems.length === 0 ? (
                            <div className="flex items-center justify-center h-100">
                              <p className="text-gray-500 text-sm">
                                Your bag is empty.
                              </p>
                            </div>
                          ) : (
                            <div className="flow-root">
                              <ul className="divide-y divide-gray-200">
                                {cartItems.map((item, idx) => (
                                  <li
                                    key={idx}
                                    className="flex py-6 items-center"
                                  >
                                    <div className="flex-1 pr-4 flex flex-col justify-between">
                                      <div>
                                        <h3 className="text-lg pb-4 text-gray-900">
                                          {item.name}
                                        </h3>
                                        <p className="text-lg font-semibold">
                                          {item.price?.currency?.symbol}
                                          {item.price?.amount?.toFixed(2)}
                                        </p>
                                        {item.attributes?.map((attribute) => (
                                          <div
                                            key={attribute.id}
                                            className="mt-2"
                                            data-testid={`cart-item-attribute-${attribute.name
                                              .toLowerCase()
                                              .replace(/\s+/g, "-")}`}
                                          >
                                            <p className="text-sm font-semibold">
                                              {attribute.name}:
                                            </p>
                                            <div className="flex gap-2 mt-1 flex-wrap">
                                              {attribute.items.map(
                                                (attrItem) => {
                                                  const isSelected =
                                                    item.selectedAttributes?.[
                                                      attribute.name
                                                    ] === attrItem.displayValue;
                                                  const isColor =
                                                    attribute.name.toLowerCase() ===
                                                    "color";

                                                  return (
                                                    <div
                                                      key={attrItem.id}
                                                      data-testid={`cart-item-attribute-${attribute.name
                                                        .toLowerCase()
                                                        .replace(
                                                          /\s+/g,
                                                          "-"
                                                        )}-${attrItem.displayValue
                                                        .toLowerCase()
                                                        .replace(/\s+/g, "-")}${
                                                        isSelected
                                                          ? "-selected"
                                                          : ""
                                                      }`}
                                                      onClick={() => {
                                                        const newSelected = {
                                                          ...item.selectedAttributes,
                                                          [attribute.name]:
                                                            attrItem.displayValue,
                                                        };
                                                        updateAttributes(
                                                          item,
                                                          newSelected
                                                        );
                                                      }}
                                                      className={`cursor-pointer rounded border flex items-center justify-center transition duration-150
            ${isColor ? "w-8 h-8" : "px-3 py-1 text-sm"}
            ${
              isColor
                ? isSelected
                  ? "border-[3px] border-green-500"
                  : "border border-gray-300"
                : isSelected
                ? "bg-black text-white border-black"
                : "border border-gray-300 text-gray-800 hover:bg-black hover:text-white"
            }`}
                                                      style={{
                                                        backgroundColor: isColor
                                                          ? attrItem.value
                                                          : undefined,
                                                        color: isColor
                                                          ? "transparent"
                                                          : undefined,
                                                      }}
                                                    >
                                                      {!isColor &&
                                                        ({
                                                          Small: "S",
                                                          Medium: "M",
                                                          Large: "L",
                                                          "Extra Small": "XS",
                                                          "Extra Large": "XL",
                                                        }[
                                                          attrItem.displayValue
                                                        ] ||
                                                          attrItem.displayValue)}
                                                    </div>
                                                  );
                                                }
                                              )}
                                            </div>
                                          </div>
                                        ))}
                                      </div>
                                    </div>

                                    <div className="flex flex-col items-center mx-6 gap-8">
                                      <button
                                        aria-label="Increase Quantity"
                                        data-testid="cart-item-amount-increase"
                                        onClick={() =>
                                          updateQty(item, item.quantity + 1)
                                        }
                                        className="cursor-pointer text-black hover:text-green-600 border p-1"
                                      >
                                        <PlusIcon className="h-5 w-5" />
                                      </button>
                                      <span
                                        data-testid="cart-item-amount"
                                        className="font-medium text-gray-900"
                                      >
                                        {item.quantity}
                                      </span>
                                      <button
                                        aria-label="Decrease Quantity"
                                        data-testid="cart-item-amount-decrease"
                                        onClick={() =>
                                          item.quantity > 1
                                            ? updateQty(item, item.quantity - 1)
                                            : removeFromCart(item)
                                        }
                                        className="cursor-pointer text-black hover:text-red-600 border p-1"
                                      >
                                        <MinusIcon className="h-5 w-5" />
                                      </button>
                                    </div>

                                    <div className="flex-shrink-0 pt-12">
                                      <img
                                        src={item.imageSrc}
                                        alt={item.imageAlt}
                                        className="h-38 w-38 rounded-md object-cover object-center"
                                      />
                                    </div>
                                  </li>
                                ))}
                              </ul>
                            </div>
                          )}
                        </div>
                      </div>

                      <div className="border-t border-gray-200 px-4 py-6 sm:px-6">
                        <div className="flex justify-between text-base font-medium text-gray-900">
                          <p>Total</p>
                          <p data-testid="cart-total">
                            $
                            {cartItems
                              .reduce(
                                (acc, item) =>
                                  acc + item.price.amount * item.quantity,
                                0
                              )
                              .toFixed(2)}
                          </p>
                        </div>

                        <div className="mt-6">
                          {cartItems.length === 0 ? (
                            <button
                              aria-label="Place Order"
                              className="flex w-full items-center justify-center bg-gray-300 px-6 py-3 text-base font-medium text-white shadow-xs cursor-not-allowed disabled"
                            >
                              PLACE ORDER
                            </button>
                          ) : (
                            <button
                              aria-label="Place Order"
                              onClick={handlePlaceOrder}
                              className="flex w-full items-center justify-center  bg-green-500 px-6 py-3 text-base font-medium text-white shadow-xs hover:bg-green-600 cursor-pointer"
                            >
                              PLACE ORDER
                            </button>
                          )}
                        </div>
                      </div>
                    </div>
                  </DialogPanel>
                  {/* </div> */}
                </Transition.Child>
              </div>
            </div>
          </Dialog>
        </Transition>,
        document.getElementById("cart-portal-root")
      )}

      {createPortal(
        <Transition show={showSuccessModal} as="div">
          <Dialog
            as="div"
            className="fixed inset-0 z-30 flex items-center justify-center"
            onClose={() => setShowSuccessModal(false)}
          >
            <Transition.Child
              as="div"
              enter="ease-out duration-200"
              enterFrom="opacity-0"
              enterTo="opacity-100"
              leave="ease-in duration-150"
              leaveFrom="opacity-100"
              leaveTo="opacity-0"
            >
              <DialogBackdrop className="fixed inset-0 bg-gray-500/75" />
            </Transition.Child>

            <Transition.Child
              as="div"
              enter="ease-out duration-200 transform"
              enterFrom="opacity-0 scale-95"
              enterTo="opacity-100 scale-100"
              leave="ease-in duration-150 transform"
              leaveFrom="opacity-100 scale-100"
              leaveTo="opacity-0 scale-95"
              className="fixed inset-0 flex items-center justify-center"
            >
              <DialogPanel className="relative w-full max-w-sm sm:max-w-md lg:max-w-lg bg-white rounded-xl p-6 sm:p-8 shadow-xl text-center">
                <DialogTitle className="text-xl font-semibold text-green-600">
                  Order Placed Successfully!
                </DialogTitle>

                {orderedItems.length > 0 && (
                  <div className="mt-4 text-left ">
                    <p className="text-md font-bold text-black">
                      Order Summary:
                    </p>
                    <ul className="mt-2 lg:max-h-100 max-h-50 overflow-y-auto text-sm text-gray-700 space-y-2">
                      {orderedItems.map((item, idx) => (
                        <li key={idx} className="pb-1 border-b border-gray-300">
                          <div className="font-medium pt-4">{item.name}</div>
                          <div className="pt-2 text-sm">
                            Qty:{" "}
                            <span className="font-medium">{item.quantity}</span>
                          </div>
                          <div className="text-sm">
                            Price:{" "}
                            <span className="font-medium">
                              {item.price?.currency?.symbol}
                              {(item.price?.amount * item.quantity).toFixed(2)}
                            </span>
                          </div>

                          {item.selectedAttributes && (
                            <div className="mt-2 space-y-1 text-sm text-black">
                              {Object.entries(item.selectedAttributes).map(
                                ([key, value]) => (
                                  <div key={key}>
                                    {key}:{" "}
                                    <span className="font-medium">{value}</span>
                                  </div>
                                )
                              )}
                            </div>
                          )}
                        </li>
                      ))}
                    </ul>
                  </div>
                )}

                <button
                  aria-label="Close button"
                  onClick={() => setShowSuccessModal(false)}
                  className="mt-4 cursor-pointer bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600"
                >
                  Close
                </button>
              </DialogPanel>
            </Transition.Child>
          </Dialog>
        </Transition>,
        document.getElementById("cart-portal-root")
      )}
    </div>
  );
};
