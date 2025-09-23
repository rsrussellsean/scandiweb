import { ApolloClient, InMemoryCache, createHttpLink } from '@apollo/client';

// Create HTTP link pointing to your GraphQL endpoint
const httpLink = createHttpLink({
  uri: '/graphql', // This will be proxied to your backend in production
  // For development, you might want to use the full URL:
  // uri: 'http://rsx.onlinewebshop.net/graphql',
});

// Create Apollo Client instance
const client = new ApolloClient({
  link: httpLink,
  cache: new InMemoryCache(),
  defaultOptions: {
    watchQuery: {
      errorPolicy: 'all',
    },
    query: {
      errorPolicy: 'all',
    },
  },
});

export default client;
