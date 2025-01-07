import { defineStore } from 'pinia';
import apiClient from '@/api';

export const useNotificationsStore = defineStore('notifications', {
  state: () => ({
    notifications: [],
    loading: false,
    error: null,
  }),
  actions: {
    async fetchNotifications() {
      this.loading = true;
      this.error = null;
      
      try {
        const response = await apiClient.get(`/notification`);
        
        this.notifications.data = response.data.notifications.data;
        this.notifications.links = response.data.notifications.links;
      } catch (err) {
        this.error = err.response?.data?.message || 'Failed to fetch notifications!';
        console.error(err);
      } finally {
        this.loading = false;
      }
    },
    async markAsRead(id) {
      try {
        const response = await apiClient.put(`/notification/${id}/seen`);
        return response;
      } catch (error) {
        this.error = err.message || 'Failed to fetch notifications!';
        console.error(err);
      }
    }
  },
});
