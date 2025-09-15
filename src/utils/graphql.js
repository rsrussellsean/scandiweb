// GraphQL utility functions for API communication

/**
 * Makes a GraphQL request to the server
 * @param {string} query - The GraphQL query or mutation
 * @param {Object} variables - Variables for the GraphQL query
 * @returns {Promise} - Promise that resolves to the response data
 */
export const graphqlRequest = async (query, variables = {}) => {
  try {
    // Now using REAL GraphQL endpoint with schema and resolvers
    const response = await fetch('http://localhost:8000/api/graphql-real.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        query,
        variables,
      }),
    });

    const result = await response.json();
    
    if (result.errors) {
      throw new Error(result.errors[0].message);
    }
    
    return result.data;
  } catch (error) {
    console.error('GraphQL request failed:', error);
    throw error;
  }
};

// GraphQL Queries
export const GET_PRODUCTS = `
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
`;

export const GET_CATEGORIES = `
  query GetCategories {
    categories {
      name
    }
  }
`;

export const GET_PRODUCT_BY_ID = `
  query GetProduct($id: ID!) {
    product(id: $id) {
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
`;

// GraphQL Mutations
export const PLACE_ORDER = `
  mutation PlaceOrder($items: [OrderInputType!]!) {
    placeOrder(items: $items)
  }
`;
