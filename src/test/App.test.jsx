import { render, screen, fireEvent } from "@testing-library/react";
import App from "../App";
import { CartProvider } from "../context/CartContext";
import productData from "../json/data.json";
import { MemoryRouter } from "react-router-dom";

beforeEach(() => {
  const mockCartItems = [
    {
      name: "Test Product",
      quantity: 1,
      price: { amount: 50, currency: { symbol: "$" } },
      imageSrc: "/some-image.jpg",
      imageAlt: "Test product image",
      selectedAttributes: { Size: "M" },
      attributes: [
        {
          id: "size",
          name: "Size",
          items: [
            { id: "s", displayValue: "S", value: "S" },
            { id: "m", displayValue: "M", value: "M" },
            { id: "l", displayValue: "L", value: "L" },
          ],
        },
      ],
    },
  ];

  localStorage.setItem("cartItems", JSON.stringify(mockCartItems));
});

const renderApp = (initialRoute = "/") =>
  render(
    <MemoryRouter initialEntries={[initialRoute]}>
      <CartProvider>
        <App />
      </CartProvider>
    </MemoryRouter>
  );

test("renders category links and cart button", () => {
  renderApp();
  expect(screen.getByTestId("cart-btn")).toBeInTheDocument();
  expect(screen.getAllByTestId("category-link").length).toBeGreaterThan(0);
});

test("renders product cards", async () => {
  renderApp();
  const product = productData.data.products[0];
  const safeTestId = `product-${product.name
    .toLowerCase()
    .replace(/\s+/g, "-")}`;

  expect(await screen.findByTestId(safeTestId)).toBeInTheDocument();
});

test("renders product detail page components", async () => {
  const firstProductId = productData.data.products[0].id;
  renderApp(`/product/${firstProductId}`);

  expect(await screen.findByTestId("product-gallery")).toBeInTheDocument();
  expect(screen.getByTestId("product-description")).toBeInTheDocument();
  expect(screen.getByTestId("add-to-cart")).toBeInTheDocument();
});

test("renders cart item and attribute elements", async () => {
  renderApp();

  fireEvent.click(screen.getByTestId("cart-btn"));

  ["s", "m", "l"].forEach((sizeKey) => {
    const isSelected = sizeKey === "m";
    const testId = `cart-item-attribute-size-${sizeKey}${
      isSelected ? "-selected" : ""
    }`;

    expect(screen.getByTestId(testId)).toBeInTheDocument();
  });

  expect(screen.getByTestId("cart-item-amount-decrease")).toBeInTheDocument();
  expect(screen.getByTestId("cart-item-amount-increase")).toBeInTheDocument();
  expect(screen.getByTestId("cart-item-amount")).toHaveTextContent("1");
  expect(screen.getByTestId("cart-total")).toHaveTextContent("$50.00");
});
