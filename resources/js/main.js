import { createApp } from 'vue'; // Import Vue
import { createPinia } from 'pinia'; // Import Pinia for state management
import App from './App.vue'; // Root Vue component
import router from './router'; // Vue Router configuration
import '../css/app.css';
import { useUserStore } from './stores/useUserStore';

// Create Vue app instance
const app = createApp(App);

// Create and register the Pinia instance
const pinia = createPinia();
app.use(pinia);

// Register Vue Router
app.use(router);

// Mount the app to the DOM
app.mount('#app');