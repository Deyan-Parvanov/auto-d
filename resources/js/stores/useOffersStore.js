import { defineStore } from 'pinia';
import apiClient from '../api';

export const useOffersStore = defineStore('listingOffersStore', {
  state: () => ({
    offers: [],
    errors: {},
  }),

  actions: {
    async makeOffer(payload) {
        try {
            const response = await apiClient.post('/listing/offer', payload);
            this.errors = {};
            return response.data;
        } catch (error) {
            if (error.response && error.response.data.errors) {
            this.errors = error.response.data.errors;
            }
            throw error;
        }
    },
    async acceptOfferAction(offerId) {
        try {
            await apiClient.put(`/car-dealer/listing/${offerId}/accept`);
        } catch (error) {
            console.error("Error accepting offer:", error);
        }
    },
  },
});
