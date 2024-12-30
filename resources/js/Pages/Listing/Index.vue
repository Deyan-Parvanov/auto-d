<template>
  <Filters :filters="filters" />
  <div v-if="loading">Loading...</div>
  <div v-else class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
    <Box v-for="listing in listings.data" :key="listing.id">
      <div>
        <div class="flex items-center gap-1">
          <Price :price="listing.price" class="text-2xl font-bold" />
        
          <div class="text-xs text-gray-500">
            <Price :price="monthlyPayment(listing.price).monthlyPayment" /> pm
          </div>
        </div>

        <ListingSpace :listing="listing" class="text-lg" />
        <ListingAddress :listing="listing" :id="listing.id" class="text-gray-500" />
      </div>
    </Box>
  </div>
  <div v-if="listings.links && listings.links.length > 0" class="w-full flex justify-center mt-4 mb-4">
    <Pagination :links="listings.links" @page-changed="fetchListings" />
  </div>
</template>

<script>
import { storeToRefs } from 'pinia';
import { useListingsStore } from '../../stores/useListingsStore';
import { useRouter } from 'vue-router';
import Box from '../../Components/UI/Box.vue';
import ListingAddress from '../../Components/ListingAddress.vue';
import ListingSpace from '../../Components/ListingSpace.vue';
import Price from '../../Components/Price.vue';
import { useMonthlyPayment } from '@/Composables/useMonthlyPayment';
import Pagination from '@/Components/UI/Pagination.vue';
import Filters from './Components/Filters.vue';

export default {
  name: 'Index',
  components: {
    Box,
    ListingAddress,
    ListingSpace,
    Price,
    Pagination,
    Filters,
  },
  data() {
    return {
      listings: {
        data: [],
        links: [],
        meta: {},
      },
      filters: {},
      loading: true,
    };
  },
  methods: {
    async fetchListings({ page = 1 } = {}) {
      const listingsStore = useListingsStore();
      this.loading = true;

      const params = { ...this.filters, page };
      await listingsStore.fetchListings(params);

      const { listings } = storeToRefs(listingsStore);
      this.listings = listings.value;
      
      this.loading = false;
    },
    monthlyPayment(price) {
      return useMonthlyPayment(price, 2.5, 10);
    },
    handlePageChange(link) {
      const router = useRouter();
      const page = new URL(link.url).searchParams.get('page'); // Extract page number from URL
      router.push({ path: '/listings', query: { page } }); // Update the query parameter in the URL
    },
  },
  mounted() {
    // Get the initial page from the route's query parameters
    const page = this.$route.query.page || 1;
    this.fetchListings(page);
  },
  watch: {
    // Watch for route changes to trigger a refetch of listings
    '$route.query.page': function (newPage) {
      this.fetchListings(newPage || 1);
    },
  },
};
</script>
