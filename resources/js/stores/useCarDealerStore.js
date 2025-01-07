import { defineStore } from 'pinia';
import apiClient from '@/api';

export const useCarDealerStore = defineStore('carDealer', {
  state: () => ({
    dealerListings: [],
    dealerListing: null,
    deleted: false,
    by: '',
    order: 'desc',
    deletedFilter: [],
    loading: false,
    error: null,
  }),

  actions: {
    async fetchCarDealerListings(params) {
      this.loading = true;
      this.error = null;
      
      try {
        const response = await apiClient.get(`/car-dealer/listing`, 
          { params }
        );
        
        this.dealerListings.data = response.data.data;
        this.dealerListings.links = response.data.links;
      } catch (err) {
        this.error = err.response?.data?.message || 'Failed to fetch listings for the current dealer!';
        console.error(err);
      } finally {
        this.loading = false;
      }
    },
    setDeletedFilter(value) {
      this.deleted = value;
    },
    async fetchCarDealerListing(id) {
      try {
        this.dealerListing = await apiClient.get(`/car-dealer/listing/${id}`);
      } catch (error) {
        this.error = err.response?.data?.message || 'Failed to fetch listing!';
      }
    },
  },
});
