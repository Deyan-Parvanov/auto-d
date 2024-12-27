import { defineStore } from 'pinia';
import apiClient from '../api';

export const useListingsStore = defineStore('listings', {
  state: () => ({
    listings: [],
    loading: false,
    error: null,
  }),

  actions: {
    async fetchListings(page) {
      this.loading = true;
      this.error = null;
      
      try {
        const response = await apiClient.get(`/listing?page=${page}`);
        
        this.listings.data = response.data.data;
        this.listings.links = response.data.links;
        this.listings.meta = response.data.meta;
      } catch (err) {
        this.error = err.response?.data?.message || 'Failed to fetch listings!';
        console.error(err);
      } finally {
        this.loading = false;
      }
    },
    async deleteListing(id) {
      try {
        await apiClient.delete(`/listing/${id}`);
        this.listings = this.listings.filter((listing) => listing.id !== id); // Remove the deleted listing from the state
      } catch (err) {
        this.error = err.response?.data?.message || 'Failed to delete listing!';
      }
    },
    async fetchListing(id) {
      try {
        const selectedListing = await apiClient.get(`/listing/${id}`);
        return selectedListing;
      } catch (error) {
        this.error = err.response?.data?.message || 'Failed to fetch listing!';
      }
    },
    setErrorMessage(message) {
      this.error = message;
    },
  },
});
