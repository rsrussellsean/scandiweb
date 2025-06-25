import { render, screen, fireEvent } from "@testing-library/react";
import App from "../App";
import { CartProvider } from "../context/CartContext";
import productData from "../json/data.json";
import { MemoryRouter } from "react-router-dom";
import { cleanup } from "@testing-library/react";
import { afterEach } from "vitest";

afterEach(() => {
  cleanup();
});

beforeAll(() => {
  global.fetch = vi.fn((url) => {
    if (url.includes("categories.php")) {
      return Promise.resolve({
        json: () =>
          Promise.resolve([
            { id: 1, name: "All" },
            { id: 2, name: "Clothes" },
            { id: 3, name: "Tech" },
          ]),
      });
    }

    if (url.includes("products.php")) {
      return Promise.resolve({
        json: () => Promise.resolve(productData),
      });
    }

    if (url.includes("product.php?id=")) {
      const id = url.split("id=")[1];
      const product = productData.data.products.find(
        (p) => p.id.toString() === id.toString()
      );

      return Promise.resolve({
        json: () => Promise.resolve(product || null),
      });
    }

    return Promise.reject(new Error("Unhandled fetch: " + url));
  });
});

afterAll(() => {
  global.fetch.mockRestore?.();
});

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

test("renders category links and cart button", async () => {
  renderApp();

  const categoryLinks = await screen.findAllByTestId("category-link");
  expect(categoryLinks.length).toBeGreaterThan(0);

  expect(screen.getByTestId("cart-btn")).toBeInTheDocument();
  expect(screen.getByTestId("active-category-link")).toBeInTheDocument();
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

  expect(screen.getByTestId("product-attribute-size")).toBeInTheDocument();
});

test("renders cart item and attribute elements", async () => {
  renderApp();

  fireEvent.click(screen.getByTestId("cart-btn"));

  expect(screen.getByTestId("cart-item-attribute-size")).toBeInTheDocument();

  ["s", "m", "l"].forEach((value) => {
    const base = `cart-item-attribute-size-${value}`;
    const el =
      screen.queryByTestId(`${base}`) || screen.getByTestId(`${base}-selected`);
    expect(el).toBeInTheDocument();
  });

  expect(screen.getByTestId("cart-item-amount-decrease")).toBeInTheDocument();
  expect(screen.getByTestId("cart-item-amount-increase")).toBeInTheDocument();
  expect(screen.getByTestId("cart-item-amount")).toHaveTextContent("1");
  expect(screen.getByTestId("cart-total")).toHaveTextContent("$50.00");
});
