<template>
    <div class="mb-4">
        <router-link to="/car-dealer/listing">
            ← Go back to Listings
        </router-link>
    </div>
    <section v-if="isLoading" class="text-center font-medium text-gray-500">
        Loading...
    </section>
    <section v-else class="flex flex-col-reverse md:grid md:grid-cols-12 gap-4">
        <Box v-if="!hasOffers" class="flex md:col-span-7 items-center">
            <div class="w-full text-center font-medium text-gray-500">
                No offers
            </div>
        </Box>

        <div v-else class="md:col-span-7 flex flex-col gap-4">
            <Offer v-for="offer in dealerListing?.data?.listing?.offers" :key="offer.id" :offer="offer"
                :listing-price="dealerListing?.data?.listing?.price"
                :is-sold="dealerListing?.data?.listing?.sold_at != null" :all-offers-disabled="allOffersDisabled"
                @disable-all-offers="disableAllOffers" />
        </div>

        <div class="md:col-span-5">
            <Box>
                <template #header>Basic Info</template>
                <Price :price="dealerListing?.data?.listing?.price" class="text-2xl font-bold" />
                <ListingSpace :listing="dealerListing?.data?.listing" class="text-lg" />
                <ListingAddress :listing="dealerListing?.data?.listing" :id="dealerListing.id" class="text-gray-500" />
            </Box>
        </div>
    </section>
    <section v-if="error" class="text-center text-red-500">
        {{ error }}
    </section>
</template>

<script>
import { mapState, mapActions } from 'pinia';
import { useCarDealerStore } from '@/stores/useCarDealerStore.js';
import Box from '@/Components/UI/Box.vue';
import Price from '@/Components/Price.vue';
import Offer from './Components/Offer.vue';
import ListingSpace from '@/Components/ListingSpace.vue';
import ListingAddress from '@/Components/ListingAddress.vue';

export default {
    name: 'ListingDetails',
    components: {
        Box,
        Price,
        Offer,
        ListingSpace,
        ListingAddress,
    },
    data() {
        return {
            listingId: this.$route.params.id,
            isLoading: false,
            error: null,
            allOffersDisabled: false,
        };
    },
    computed: {
        ...mapState(useCarDealerStore, ['dealerListing']),
        hasOffers() {
            return this.dealerListing.data.listing.offers.length > 0;
        },
    },
    async created() {
        this.isLoading = true;
        try {
            await this.fetchCarDealerListing(this.listingId);
        } catch (err) {
            this.error = 'Failed to load the listing. Please try again later.';
            console.error(err);
        } finally {
            this.isLoading = false;
        }
    },
    methods: {
        ...mapActions(useCarDealerStore, ['fetchCarDealerListing']),
        disableAllOffers() {
            this.allOffersDisabled = true;
        },
    },
};
</script>
