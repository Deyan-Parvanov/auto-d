import { defineStore } from 'pinia';
import apiClient from '@/api';

export const useListingsStore = defineStore('listings', {
  state: () => ({
    listings: [],
    listing: null,
    availableMakes: [],
    availableEngineTypes: [],
    loading: false,
    error: null,
  }),

  actions: {
    async fetchListings(params) {
      this.loading = true;
      this.error = null;
      
      try {
        const response = await apiClient.get(`/listing`, 
          { params }
        );
        
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
    async createListing(payload) {
      try {
        const response = await apiClient.post('/car-dealer/listing/create', payload);
        
        if (response.errors){
          this.error = response.errors;
        }
        
        return response;
      } catch (error) {
        if (error.response && error.response.data.errors) {
          this.error = 'Invalid data';
          return error.response.data;
        }
      }
    },
    async deleteListing(id) {
      try {
        const response = await apiClient.delete(`/car-dealer/listing/${id}`);
        this.listings = this.listings.filter((listing) => listing.id !== id);

        return response;
      } catch (err) {
        this.error = err.response?.data?.message || 'Failed to delete listing!';
      }
    },
    async restoreListing(id) {
      try {
        const response = await apiClient.put(`/car-dealer/listing/${id}/restore`);
        this.listings = this.listings.filter((listing) => listing.id !== id);

        return response;
      } catch (err) {
        this.error = err.response?.data?.message || 'Failed to restore listing!';
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
    async fetchAvailableMakes() {
      try {
        const response = await apiClient.get('/available-makes');
        this.availableMakes = response.data;
      } catch (error) {
        console.error('Failed to fetch makes:', error);
      }
    },
    async fetchEngineTypes() {
      try {
        const response = await apiClient.get('/available-engines');
        this.availableEngineTypes = response.data;
      } catch (error) {
        console.error('Failed to fetch engine types:', error);
      }
    },
    async setListing(data) {
      this.listing = await this.fetchListing(data.id);
    },
    async deleteImage(imageId) {
      try {
        const response = await apiClient.delete(`/car-dealer/listing/${imageId}/image/delete`);
        
        return response;
      } catch (err) {
        this.error = err.response?.data?.message || 'Failed to delete image!';
      }
    },
  }
});
