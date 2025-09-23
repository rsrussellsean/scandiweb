import { gql } from '@apollo/client';

// Query to get all products
export const GET_ALL_PRODUCTS = gql`
  query GetAllProducts {
    products {
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
`;

// Query to get products by category
export const GET_PRODUCTS_BY_CATEGORY = gql`
  query GetProductsByCategory($category: String!) {
    productsByCategory(category: $category) {
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
`;

// Query to get a single product by ID
export const GET_PRODUCT_BY_ID = gql`
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
`;

// Query to get all categories
export const GET_ALL_CATEGORIES = gql`
  query GetAllCategories {
    categories {
      id
      name
    }
  }
`;
