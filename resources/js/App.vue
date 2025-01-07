<template>
  <div id="app">
    <header class="border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 w-full">
      <div class="container mx-auto">
        <nav class="p-4 flex items-center justify-between">
          <div class="text-lg font-medium">
            <router-link to="/listing">Listings</router-link>
          </div>
          <div class="text-xl text-indigo-600 dark:text-indigo-300 font-bold text-center">
            <router-link to="/listing">AutoD</router-link>
          </div>
          <div v-if="user" class="flex items-center gap-4">
            <router-link to="/notification" class="text-gray-500 relative pr-2 py-2 text-lg">
              🔔
              <div v-if="user && user.notifications_count"
                class="absolute right-0 top-0 w-5 h-5 bg-red-700 dark:bg-red-400 text-white font-medium border border-white dark:border-gray-900 rounded-full text-xs text-center">
                {{ user.notifications_count }}
              </div>
            </router-link>
            <router-link to="/car-dealer/listing" class="text-sm text-gray-500">{{ user.name }}</router-link>
            <router-link to="/car-dealer/listing/create" class="btn-primary">+ New Listing</router-link>
            <router-link to="/listing" @click="logout">Logout</router-link>
          </div>
          <div v-else class="flex items-center gap-2">
            <router-link to="/register">Register</router-link>
            <router-link to="/login">Sign In</router-link>
          </div>
        </nav>
      </div>
    </header>

    <main class="container mx-auto p-4 w-full">
      <div v-if="flashMessageStore.successMessage"
        class="mb-4 border rounded-md shadow-sm border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900 p-2">
        {{ flashMessageStore.successMessage }}
      </div>

      <router-view />
      <Popup />

    </main>
  </div>
</template>

<script>
import Popup from './Pages/Listing/Popup.vue';
import { useUserStore } from './stores/useUserStore';
import { useFlashMessageStore } from './stores/useFlashMessageStore';
import { onMounted, computed } from 'vue';

export default {
  setup() {
    const userStore = useUserStore();
    onMounted(() => {
      userStore.initializeUser();
    });

    const flashMessageStore = useFlashMessageStore();

    return {
      user: computed(() => userStore.user),
      logout: userStore.logout,
      flashMessageStore,
    };
  },
  components: {
    Popup,
  },
};
</script>
