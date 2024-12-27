import { defineStore } from 'pinia';
import { loginUser, getToken, registerUser } from '../services/authServices';
import apiClient from '@/api';

export const useUserStore = defineStore('userStore', {
  state() {
    return {
      user: null,
    };
  },
  actions: {
    async loginUser(loginData) {
      try {
        await apiClient.get('/sanctum/csrf-cookie');

        const profile = await loginUser(loginData);
        if (!profile)
          return false;
        this.user = profile;
        apiClient.defaults.headers.common['Authorization'] = `Bearer ${profile.token}`;

        return true;
      } catch {
        console.error('Login failed:', error);
        return false;
      }
    },
    async initializeUser() {
      const token = getToken();
      
      if (token) {
        apiClient.defaults.headers.common['Authorization'] = `Bearer ${token}`;
        try {
          const { data } = await apiClient.get('/user');
          this.user = data;
          
        } catch (e) { 
          console.error('Unable to fetch user data', e);
          this.user = null;
        }
      }
    },
    async logout() {
      try {
        await apiClient.delete('/logout');
        this.user = null;
        localStorage.removeItem('authToken');
        delete apiClient.defaults.headers.common['Authorization'];
      } catch (error) {
        console.error('Logout failed', error);
      }
    },
    async registerUser(registerData) {
      try {
        const profile = await registerUser(registerData);
        if (!profile)
          return false;

        this.user = profile.data;
        const token = profile.token;
        localStorage.setItem('authToken', token);
        apiClient.defaults.headers.common['Authorization'] = `Bearer ${token}`;

        return true;
      } catch {
        console.error('Registration failed:', error);
        return false;
      }
    },
  },
});
