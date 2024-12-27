import { defineStore } from 'pinia';
import apiClient from '../api';

export const useListingCreateStore = defineStore('listingCreateStore', {
  state: () => ({
    listings: [],
    errors: {},
  }),

  actions: {
    async createListing(payload) {
      try {
        const response = await apiClient.post('/listing/create', payload);
        this.errors = {};
        return response.data; // Successfully created listing
      } catch (error) {
        if (error.response && error.response.data.errors) {
          this.errors = error.response.data.errors; // Validation errors
        }
        throw error;
      }
    },
  },
});
