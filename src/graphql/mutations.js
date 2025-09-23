import { gql } from '@apollo/client';

// Mutation to place an order
export const PLACE_ORDER = gql`
  mutation PlaceOrder($items: [OrderInput!]!) {
    placeOrder(items: $items) {
      orderId
      status
      message
      totalAmount
      itemCount
    }
  }
`;
