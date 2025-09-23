# GraphQL Integration Testing

This file contains information about testing the GraphQL integration.

## GraphQL Endpoint

- URL: `/graphql` (proxied to `http://rsx.onlinewebshop.net/graphql`)
- Method: POST
- Content-Type: application/json

## Available Queries

### Get All Products

```graphql
query GetAllProducts {
  products {
    id
    name
    inStock
    category
    brand
    gallery
    prices {
      amount
      currency {
        label
        symbol
      }
    }
    attributes {
      id
      name
      type
      items {
        id
        value
        displayValue
      }
    }
  }
}
```

### Get Products by Category

```graphql
query GetProductsByCategory($category: String!) {
  productsByCategory(category: $category) {
    id
    name
    inStock
    category
    brand
    gallery
    prices {
      amount
      currency {
        label
        symbol
      }
    }
  }
}
```

### Get Single Product

```graphql
query GetProductById($id: ID!) {
  product(id: $id) {
    id
    name
    inStock
    description
    category
    brand
    gallery
    prices {
      amount
      currency {
        label
        symbol
      }
    }
    attributes {
      id
      name
      type
      items {
        id
        value
        displayValue
      }
    }
  }
}
```

### Get All Categories

```graphql
query GetAllCategories {
  categories {
    id
    name
  }
}
```

## Available Mutations

### Place Order

```graphql
mutation PlaceOrder($items: [OrderInput!]!) {
  placeOrder(items: $items) {
    orderId
    status
    message
    totalAmount
    itemCount
  }
}
```

## Components Updated

### Frontend Components

- ✅ **ProductList** - Now uses GraphQL queries for fetching products
- ✅ **ProductItem** - Now uses GraphQL query for single product
- ✅ **Navbar** - Now uses GraphQL query for categories
- ✅ **Cart** - Now uses GraphQL mutation for placing orders
- ✅ **Fillup** - Now uses GraphQL mutation for placing orders
- ✅ **Signup** - Now uses GraphQL mutation for placing orders

### Backend Components

- ✅ **GraphQL Controller** - Enhanced with product, productsByCategory queries
- ✅ **ProductRepository** - Added getByCategoryName method
- ✅ **OrderMutation** - Returns structured data instead of string
- ✅ **TypeRegistry** - Added OrderResponse type

## Testing Steps

1. **Start the backend server** (ensure rsx.onlinewebshop.net is running)
2. **Start the frontend** (`npm run dev`)
3. **Test the following flows:**
   - Navigate to different categories (All, Clothes, Tech)
   - View individual product pages
   - Add items to cart
   - Place orders through cart
   - Check that loading states work properly
   - Verify error handling

## Database Requirements

Ensure the following tables exist and have data:

- `products`
- `categories`
- `galleries`
- `prices`
- `attributes`
- `attribute_items`
- `orders`
- `order_items`
