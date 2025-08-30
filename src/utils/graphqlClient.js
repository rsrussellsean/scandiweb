/**
 * GraphQL Client utility for API communication
 */

const GRAPHQL_ENDPOINT = '/api/graphql.php';

/**
 * Execute a GraphQL query
 * @param {string} query - The GraphQL query string
 * @param {Object} variables - Variables for the query
 * @returns {Promise<Object>} - The response data
 */
export const graphqlRequest = async (query, variables = {}) => {
  try {
    const response = await fetch(GRAPHQL_ENDPOINT, {
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
      throw new Error(result.errors.map(err => err.message).join(', '));
    }

    return result.data;
  } catch (error) {
    console.error('GraphQL request failed:', error);
    throw error;
  }
};

/**
 * GraphQL Queries
 */
export const QUERIES = {
  GET_ALL_PRODUCTS: `
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
  `,

  GET_PRODUCT_BY_ID: `
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
  `,

  GET_ALL_CATEGORIES: `
    query GetAllCategories {
      categories {
        id
        name
      }
    }
  `,
};

/**
 * GraphQL Mutations
 */
export const MUTATIONS = {
  PLACE_ORDER: `
    mutation PlaceOrder($items: [OrderInput!]!) {
      placeOrder(items: $items)
    }
  `,
};

/**
 * Convenience functions for common operations
 */

export const fetchAllProducts = () => {
  return graphqlRequest(QUERIES.GET_ALL_PRODUCTS);
};

export const fetchProductById = (id) => {
  return graphqlRequest(QUERIES.GET_PRODUCT_BY_ID, { id });
};

export const fetchAllCategories = () => {
  return graphqlRequest(QUERIES.GET_ALL_CATEGORIES);
};

export const placeOrder = (items) => {
  return graphqlRequest(MUTATIONS.PLACE_ORDER, { items });
};
