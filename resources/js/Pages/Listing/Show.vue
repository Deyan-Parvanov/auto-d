<template>
  <div>
    <div v-if="loading">Loading...</div>
    <div v-if="error">{{ error }}</div>
    <div v-if="listing" class="flex flex-col-reverse md:grid md:grid-cols-12 gap-4">
      <Box class="md:col-span-7 flex items-center">
        <div v-if="listing.images?.length" class="grid grid-cols-2 gap-1">
          <img v-for="image in listing.images" :key="image.id" :src="image.src" />
        </div>
        <div v-else class="w-full text-center font-medium text-gray-500">No images</div>
      </Box>
      <div class="md:col-span-5 flex flex-col gap-4">
        <Box>
          <template #header>
            Basic info
          </template>
          <Price :price="listing.price" class="text-2xl font-bold" />
          <ListingSpace :listing="listing" class="text-lg" />
          <ListingAddress :listing="listing" :id="listing.id" class="text-gray-500" />
        </Box>

        <Box>
          <template #header>
            Monthly Payment
          </template>
          <div>
            <label class="label">Interest rate ({{ interestRate }}%)</label>
            <input v-model.number="interestRate" type="range" min="0.1" max="30" step="0.1"
              class="w-full h-4 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700" />

            <label class="label">Duration ({{ duration }})</label>
            <input v-model.number="duration" type="range" min="3" max="35" step="1"
              class="w-full h-4 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700" />

            <div class="text-gray-600 dark:text-gray-300 mt-2">
              <div class="text-gray-400">Your monthly payment</div>
              <Price :price="monthlyPayment(listing.price).monthlyPayment" class="text-3xl" />
            </div>
            <div class="mt-2 text-gray-500">
              <div class="flex justify-between">
                <div>Total paid</div>
                <div>
                  <Price :price="monthlyPayment(listing.price).totalPaid" class="font-medium" />
                </div>
              </div>
              <div class="flex justify-between">
                <div>Principal paid</div>
                <div>
                  <Price :price="listing.price" class="font-medium" />
                </div>
              </div>
              <div class="flex justify-between">
                <div>Interest paid</div>
                <div>
                  <Price :price="monthlyPayment(listing.price).totalInterest" class="font-medium" />
                </div>
              </div>
            </div>
          </div>
        </Box>

        <MakeOffer v-if="user && !offer" :listing-id="listing.id" :price="listing.price" @offerUpdated="handleOfferUpdated" />
        <OfferMade v-if="user && offer" :offer="offer" />
      </div>
    </div>
  </div>
</template>

<script>
import apiClient from '../../api';
import ListingAddress from '../../Components/ListingAddress.vue';
import MakeOffer from './Components/MakeOffer.vue';
import OfferMade from './Components/OfferMade.vue';
import Box from '../../Components/UI/Box.vue';
import ListingSpace from '../../Components/ListingSpace.vue';
import Price from '../../Components/Price.vue';
import { useMonthlyPayment } from '../../Composables/useMonthlyPayment';
import { useUserStore } from '@/stores/useUserStore';

export default {
  name: 'Show',
  components: {
    ListingAddress,
    ListingSpace,
    Price,
    Box,
    MakeOffer,
    OfferMade,
  },
  data() {
    return {
      listing: null,
      loading: false,
      error: null,
      interestRate: 2.5,
      duration: 10,
      updatedOffer: null,
      offer: null,
    };
  },
  computed: {
    monthlyPayment() {
      if (!this.listing || !this.listing.price) return 0;

      return () => useMonthlyPayment(this.updatedOffer, this.interestRate, this.duration);
    },
    user() {
      const userStore = useUserStore();

      return userStore.user;
    },
  },
  methods: {
    async fetchListing() {
      this.loading = true;
      try {
        const response = await apiClient.get(`/listing/${this.$route.params.id}`);
        
        this.listing = response.data.listing;
        this.offer = response.data.offer;
      } catch (err) {
        this.error = err.response?.data?.message || 'Failed to fetch listing';
      } finally {
        this.loading = false;
      }
    },
    handleOfferUpdated(value) {
      this.updatedOffer = value;
    },
  },
  mounted() {
    this.fetchListing();
  },
};
</script>
