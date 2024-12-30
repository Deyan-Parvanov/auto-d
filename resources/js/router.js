import { createRouter, createWebHistory } from 'vue-router';
import Index from './Pages/Listing/Index.vue';
import Create from './Pages/CarDealer/Create.vue';
import Edit from './Pages/CarDealer/Edit.vue';
import Show from './Pages/Listing/Show.vue';
import Login from './Pages/Auth/Login.vue';
import Register from './Pages/UserAccount/Register.vue';
import CarDealerIndex from './Pages/CarDealer/CarDealerIndex.vue';
import { useUserStore } from './stores/useUserStore';
import { useListingsStore } from './stores/useListingsStore';

// ToDo: move route guards to separate file and export it
const requireAuthentication = (to, from, next) => {
  const userStore = useUserStore();
  const token = userStore.user || localStorage.getItem('authToken');

  if (token) {
    next();
  } else {
    if (to.name !== 'login') {
      next({ path: '/login' });
    } else {
      next();
    }
  }
};

const requireAuthorization = async (to, from, next) => {
  const userStore = useUserStore();
  const token = userStore.user || localStorage.getItem('authToken');

  if (!token) {
    if (to.name !== 'login') {
      next({ path: '/login' });
    } else {
      next();
    }
    return;
  }

  const listingStore = useListingsStore();

  try {
    const listingId = to.params.id;
    const listing = await listingStore.fetchListing(listingId);
    console.log(token);
    
    if (token.is_admin || (listing && listing.data.by_user_id === token.id)) {
      next();
    } else {
      listingStore.error = 'You are not authorized to edit this listing.';
      next(false);
    }
  } catch (error) {
    listingStore.setErrorMessage('An error occurred while fetching the listing.');
    next(false);
  }
};

const routes = [
  { path: '/hello', name: 'hello' },
  { path: '/listing', name: 'listing', component: Index },
  { path: '/listing/:id', name: 'listingShow', component: Show },
  { path: '/login', name: 'login', component: Login },
  { path: '/logout', name: 'logout' },
  { path: '/register', name: 'register', component: Register },
  { path: '/car-dealer/listing', name: 'carDealerListing', component: CarDealerIndex },
  { path: '/car-dealer/listing/create', name: 'listingCreate', component: Create, beforeEnter: requireAuthentication, },
  { path: '/car-dealer/listing/:id/edit', name: 'listingEdit', component: Edit, beforeEnter: requireAuthorization, },
];

const router = createRouter({
  routes,
  history: createWebHistory(),
});

export default router;
