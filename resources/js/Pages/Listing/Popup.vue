<template>
  <div v-if="message" class="popup">
    <p>{{ message }}</p>
  </div>
</template>

<script>
import { useListingsStore } from '../../stores/useListingsStore';
import { computed, watch } from 'vue';

export default {
  setup() {
    const listingStore = useListingsStore();
    const message = computed(() => listingStore.error);

    watch(
      () => listingStore.error,
      (newMessage) => {
        if (newMessage) {
          console.log(listingStore.newMessage);
          setTimeout(() => {
            listingStore.error = null;
          }, 3000);
        }
      }
    );

    return {
      message: message,
    };
  },
};
</script>

<style scoped>
.popup {
  position: fixed;
  top: 20px;
  left: 50%;
  transform: translateX(-50%);
  background: #f8d7da;
  color: #721c24;
  padding: 10px;
  border: 1px solid #f5c6cb;
  border-radius: 5px;
  z-index: 1000;
}
</style>