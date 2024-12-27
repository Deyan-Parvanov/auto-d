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
            <div class="text-sm text-gray-500">{{ user.name }}</div>
              <router-link to="/listing/create" class="btn-primary">+ New Listing</router-link>
              <button @click="logout">Logout</button>
          </div>
          <div v-else class="flex items-center gap-2">
            <router-link to="/register">Register</router-link>
            <router-link to="/login">Sign In</router-link>
          </div>
        </nav>
      </div>
    </header>

    <main class="container mx-auto p-4 w-full">
      <!-- <div v-if="flashSuccess" class="mb-4 border rounded-md shadow-sm border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900 p-2">
        {{ flashSuccess }}
      </div> -->
      
      <router-view />
      <Popup />
      
    </main>
  </div>
</template>
  
<script>
import Popup from './Pages/Listing/Popup.vue';
import { useUserStore } from './stores/useUserStore';
import { onMounted, computed } from 'vue';

export default {
  name: 'App',
  setup() {
    const userStore = useUserStore();
    onMounted(() => {
      userStore.initializeUser();
    });
    
    return {
      user: computed(() => userStore.user),
      logout: userStore.logout,
    };
  },
  components: {
    Popup,
  },
};
</script>
