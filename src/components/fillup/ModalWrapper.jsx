import React from "react";
import { createPortal } from "react-dom";
import {
  Transition,
  Dialog,
  DialogBackdrop,
  DialogPanel,
  DialogTitle,
} from "@headlessui/react";

const ModalWrapper = ({ show, setShow, orderedItems }) => {
  if (!show) return null;

  return createPortal(
    <Transition show={show} as="div" appear>
      <Dialog
        as="div"
        className="fixed inset-0 z-30 flex items-center justify-center"
        onClose={() => setShow(false)}
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
                <p className="text-md font-bold text-black">Order Summary:</p>
                <ul className="mt-2 max-h-60 overflow-y-auto text-sm text-gray-700 space-y-2">
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
              onClick={() => setShow(false)}
              className="mt-4 cursor-pointer bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600"
            >
              Close
            </button>
          </DialogPanel>
        </Transition.Child>
      </Dialog>
    </Transition>,
    document.getElementById("cart-portal-root")
  );
};

export default ModalWrapper;
