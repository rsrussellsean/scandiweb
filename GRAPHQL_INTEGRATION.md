# GraphQL Integration

This application has been updated to use GraphQL for all API communication instead of direct PHP REST endpoints.

## Changes Made

### Backend

- Added a `product` query to the GraphQL schema in `backend/src/Controller/GraphQL.php` to fetch individual products by ID
- The existing GraphQL endpoint (`/api/graphql.php`) now handles all data requests

### Frontend

- Created `src/utils/graphql.js` with GraphQL utility functions and query definitions
- Updated all components to use GraphQL instead of REST API calls:
  - `ProductList.jsx` - Now uses GraphQL query to fetch all products
  - `ProductItem.jsx` - Now uses GraphQL query to fetch a single product by ID
  - `Navbar.jsx` - Now uses GraphQL query to fetch categories
  - `Cart.jsx` - Now uses GraphQL mutation to place orders

### API Endpoints

#### Available GraphQL Queries:

```graphql
# Get all products
query GetProducts {
  products {
    id
    name
    inStock
    gallery
    description
    category
    attributes {
      id
      name
      type
      items {
        displayValue
        value
        id
      }
    }
    prices {
      currency {
        label
        symbol
      }
      amount
    }
    brand
  }
}

# Get single product by ID
query GetProduct($id: ID!) {
  product(id: $id) {
    # ... same fields as above
  }
}

# Get categories
query GetCategories {
  categories {
    id
    name
  }
}
```

#### Available GraphQL Mutations:

```graphql
# Place an order
mutation PlaceOrder($items: [OrderInputType!]!) {
  placeOrder(items: $items)
}
```

### Usage Examples

```javascript
import {
  graphqlRequest,
  GET_PRODUCTS,
  GET_PRODUCT_BY_ID,
  PLACE_ORDER,
} from "../utils/graphql";

// Fetch all products
const data = await graphqlRequest(GET_PRODUCTS);
const products = data.products;

// Fetch single product
const data = await graphqlRequest(GET_PRODUCT_BY_ID, { id: "product-id" });
const product = data.product;

// Place order
const orderItems = [
  {
    productId: "1",
    quantity: 2,
    price: 29.99,
    selectedAttributes: ["size:M", "color:red"],
  },
];
const data = await graphqlRequest(PLACE_ORDER, { items: orderItems });
```

## Benefits

1. **Unified API**: All data requests now go through a single GraphQL endpoint
2. **Type Safety**: GraphQL provides strong typing and validation
3. **Efficient Queries**: Clients can request exactly the data they need
4. **Better Error Handling**: Centralized error handling through GraphQL
5. **Easier Testing**: Single endpoint to mock for testing

## Database Connection

The database connection logic remains unchanged - GraphQL resolvers use the existing repository classes and database configuration.
