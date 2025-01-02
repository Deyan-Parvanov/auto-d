import { defineStore } from 'pinia';

export const useFlashMessageStore = defineStore('flashMessage', {
  state: () => ({
    successMessage: null,
  }),
  actions: {
    setSuccessMessage(message) {
      this.successMessage = message;

      setTimeout(() => {
        this.successMessage = null;
      }, 3000);
    },
  },
});
