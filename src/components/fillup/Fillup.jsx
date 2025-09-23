import React, { useState, Fragment } from "react";
import { createPortal } from "react-dom";
import { Dialog, Transition } from "@headlessui/react";

const CheckoutPage = ({ cartItems, setOrderId, setIsCartOpen, clearCart }) => {
  const [showSuccessModal, setShowSuccessModal] = useState(false);
  const [orderedItems, setOrderedItems] = useState([]);

  const [formData, setFormData] = useState({
    email: "",
    firstName: "",
    lastName: "",
    address: "",
    country: "",
    zip: "",
    city: "",
    state: "",
    phone: "",
    paymentMethod: "",
    agreeTerms: false,
  });

  const handleChange = (e) => {
    const { name, value, type, checked } = e.target;
    setFormData((prev) => ({
      ...prev,
      [name]: type === "checkbox" ? checked : value,
    }));
  };

  const handlePlaceOrder = async () => {
    try {
      const res = await fetch("/api/place_order.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ items: cartItems }),
      });

      const result = await res.json();

      if (res.ok) {
        setOrderId(result.orderId);
        setOrderedItems(cartItems); // your parent state
        setShowSuccessModal(true);
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

  const handleSubmit = (e) => {
    e.preventDefault();
    if (!formData.agreeTerms) {
      alert("You must agree to the terms and conditions.");
      return;
    }
    handlePlaceOrder();
  };

  return (
    <>
      <div className="max-w-3xl mx-auto p-4 mt-40">
        <h1 className="text-3xl font-semibold mb-6">Checkout</h1>
        <form className="space-y-4" onSubmit={handleSubmit}>
          <input
            data-testid="checkout-email"
            type="email"
            name="email"
            placeholder="Email"
            className="w-full border rounded p-2 border-gray-300"
            value={formData.email}
            onChange={handleChange}
            required
          />
          <div className="flex gap-2">
            <input
              data-testid="checkout-first-name"
              type="text"
              name="firstName"
              placeholder="First Name"
              className="w-1/2 border rounded p-2 border-gray-300"
              value={formData.firstName}
              onChange={handleChange}
              required
            />
            <input
              data-testid="checkout-last-name"
              type="text"
              name="lastName"
              placeholder="Last Name"
              className="w-1/2 border rounded p-2 border-gray-300"
              value={formData.lastName}
              onChange={handleChange}
              required
            />
          </div>
          <input
            data-testid="checkout-address"
            type="text"
            name="address"
            placeholder="Address"
            className="w-full border rounded p-2 border-gray-300"
            value={formData.address}
            onChange={handleChange}
            required
          />
          <select
            data-testid="checkout-country"
            name="country"
            className="w-full border rounded p-2 border-gray-300"
            value={formData.country}
            onChange={handleChange}
            required
          >
            <option value="">Select Country</option>
            <option value="PH">Philippines</option>
            <option value="US">United States</option>
            <option value="CA">Canada</option>
          </select>
          <div className="flex gap-2">
            <input
              data-testid="checkout-zip"
              type="text"
              name="zip"
              placeholder="Zip Code"
              className="w-1/3 border rounded p-2 border-gray-300"
              value={formData.zip}
              onChange={handleChange}
              required
            />
            <input
              data-testid="checkout-city"
              type="text"
              name="city"
              placeholder="City"
              className="w-1/3 border rounded p-2 border-gray-300"
              value={formData.city}
              onChange={handleChange}
              required
            />
            <input
              data-testid="checkout-state"
              type="text"
              name="state"
              placeholder="State"
              className="w-1/3 border rounded p-2 border-gray-300"
              value={formData.state}
              onChange={handleChange}
              required
            />
          </div>
          <input
            data-testid="checkout-phone"
            type="tel"
            name="phone"
            placeholder="Mobile Phone"
            className="w-full border rounded p-2 border-gray-300"
            value={formData.phone}
            onChange={handleChange}
            required
          />
          <div>
            <label className="font-medium">Payment Method</label>
            <div className="flex items-center gap-4 mt-1">
              <label className="flex items-center gap-1">
                <input
                  data-testid="checkout-payment-cash"
                  type="radio"
                  name="paymentMethod"
                  value="cash"
                  onChange={handleChange}
                />
                Cash
              </label>
              <label className="flex items-center gap-1">
                <input
                  data-testid="checkout-payment-card"
                  type="radio"
                  name="paymentMethod"
                  value="card"
                  onChange={handleChange}
                />
                Card
              </label>
            </div>
          </div>

          <label className="flex items-center gap-2">
            <input
              data-testid="checkout-terms"
              type="checkbox"
              name="agreeTerms"
              checked={formData.agreeTerms}
              onChange={handleChange}
            />
            I agree to the terms and conditions
          </label>

          <div className="flex justify-end gap-2 pt-4">
            <button
              type="button"
              className="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400"
              onClick={() => window.history.back()} // or navigate if you use react-router
            >
              Cancel
            </button>
            <button
              data-testid="checkout-submit"
              type="submit"
              className="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600"
            >
              Place Order
            </button>
          </div>
        </form>
      </div>
      {createPortal(
        <Transition show={showSuccessModal} as={Fragment}>
          <Dialog
            as="div"
            className="fixed inset-0 z-30 flex items-center justify-center"
            onClose={() => setShowSuccessModal(false)}
          >
            <Transition.Child
              as={Fragment}
              enter="ease-out duration-200"
              enterFrom="opacity-0"
              enterTo="opacity-100"
              leave="ease-in duration-150"
              leaveFrom="opacity-100"
              leaveTo="opacity-0"
            >
              <div className="fixed inset-0 bg-gray-500/75" />
            </Transition.Child>

            <Transition.Child
              as={Fragment}
              enter="ease-out duration-200 transform"
              enterFrom="opacity-0 scale-95"
              enterTo="opacity-100 scale-100"
              leave="ease-in duration-150 transform"
              leaveFrom="opacity-100 scale-100"
              leaveTo="opacity-0 scale-95"
              className="fixed inset-0 flex items-center justify-center"
            >
              <Dialog.Panel className="relative w-full max-w-sm sm:max-w-md lg:max-w-lg bg-white rounded-xl p-6 sm:p-8 shadow-xl text-center">
                <Dialog.Title className="text-xl font-semibold text-green-600">
                  Order Placed Successfully!
                </Dialog.Title>

                {orderedItems.length > 0 && (
                  <div className="mt-4 text-left">
                    <p className="text-md font-bold text-black">
                      Order Summary:
                    </p>
                    <ul className="mt-2 max-h-50 lg:max-h-100 overflow-y-auto text-sm text-gray-700 space-y-2">
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
              </Dialog.Panel>
            </Transition.Child>
          </Dialog>
        </Transition>,
        document.getElementById("cart-portal-root")
      )}
    </>
  );
};

export default CheckoutPage;
