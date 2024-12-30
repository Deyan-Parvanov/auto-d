<template>
    <h1 class="text-3xl mb-4">Your Listings</h1>
    <section>
        <CarDealerFilters :filters="filters" />
    </section>
    <section class="grid grid-cols-1 lg:grid-cols-2 gap-2">
        <Box v-for="listing in dealerListings.data" :key="listing.id" :class="{ 'border-dashed': listing.deleted_at }">
            <div class="flex flex-col md:flex-row gap-2 md:items-center justify-between">
                <div :class="{ 'opacity-25': listing.deleted_at }">
                    <div class="xl:flex items-center gap-2">
                        <Price :price="listing.price" class="text-2xl font-medium" />
                        <ListingSpace :listing="listing" />
                    </div>
                    <ListingAddress :listing="listing" :id="listing.id" />
                </div>
                <div class="flex items-center gap-1 text-gray-600 dark:text-gray-300">
                    <router-link v-if="!listing.deleted_at" :to="{ name: 'listingShow', params: { id: listing.id } }"
                        class="btn-outline text-xs font-medium" target="_blank">
                        Preview
                    </router-link>
                    <router-link v-if="!listing.deleted_at" :to="{ name: 'listingEdit', params: { id: listing.id } }"
                        class="btn-outline text-xs font-medium">
                        Edit
                    </router-link>
                    <div>
                        <button v-if="!listing.deleted_at" @click="deleteListing(listing.id)" class="btn-outline text-xs font-medium">
                            Delete
                        </button>
                        <button v-else @click="restoreListing(listing.id)" class="btn-outline text-xs font-medium" method="put">
                            Restore
                        </button>
                    </div>
                </div>
            </div>
        </Box>
    </section>
    <section v-if="dealerListings.links && dealerListings.links.length > 0"
        class="w-full flex justify-center mt-4 mb-4">
        <Pagination :links="dealerListings.links" @page-changed="fetchCarDealerListings" />
    </section>
</template>

<script>
import ListingAddress from '../../Components/ListingAddress.vue';
import ListingSpace from '../../Components/ListingSpace.vue';
import Price from '../../Components/Price.vue';
import Box from '../../Components/UI/Box.vue';
import CarDealerFilters from './Components/CarDealerFilters.vue';
import Pagination from '../../Components/UI/Pagination.vue';
import { useCarDealerStore } from '../../stores/useCarDealerStore.js';
import { useUserStore } from '../../stores/useUserStore';
import { useListingsStore } from '../../stores/useListingsStore';
import { storeToRefs } from 'pinia';

export default {
    name: 'CarDealerIndex',
    data() {
        return {
            dealerListings: {
                data: [],
                links: [],
                meta: {},
            },
            filters: {},
        };
    },
    components: {
        ListingAddress,
        ListingSpace,
        Price,
        Box,
        Pagination,
        CarDealerFilters,

    },
    methods: {
        async fetchCarDealerListings({ page = 1 } = {}) {
            const carDealerStore = useCarDealerStore();
            this.loading = true;

            const params = { ...this.filters, page };
            // console.log(params);

            await carDealerStore.fetchCarDealerListings(params);

            const { dealerListings } = storeToRefs(carDealerStore);
            this.dealerListings = dealerListings.value;

            this.loading = false;
        },
        async deleteListing(id) {
            // Logic is repeating with Restore method. Refactor it
            const userStore = useUserStore();
            const token = userStore.user || localStorage.getItem('authToken');
            if (!token) {
                this.$router.push({ name: 'login' });
                return;
            }

            const listingsStore = useListingsStore();
            const listing = await listingsStore.fetchListing(id);
            if (!listing) {
                listingsStore.error = 'Listing not found.';
                return;
            }

            const userId = token.id;

            if (!token.is_admin && listing.data.by_user_id !== userId) {
                listingsStore.error = 'You are not authorized to delete this listing.';
                return;
            }

            if (confirm('Are you sure you want to delete this listing?')) {
                await listingsStore.deleteListing(id);
                this.fetchCarDealerListings({ page: this.$route.query.page || 1 });
            }
        },
        async restoreListing(id) {
            const userStore = useUserStore();
            const token = userStore.user || localStorage.getItem('authToken');
            if (!token) {
                this.$router.push({ name: 'login' });
                return;
            }

            const listingsStore = useListingsStore();
            const userId = token.id;

            if (!token.is_admin && listing.data.by_user_id !== userId) {
                listingsStore.error = 'You are not authorized to delete this listing.';
                return;
            }

            await listingsStore.restoreListing(id);
            this.fetchCarDealerListings({ page: this.$route.query.page || 1 });
        },
    },
    mounted() {
        this.filters = {
            deleted: this.$route.query.deleted ?? null,
            by: this.$route.query.by ?? null,
            order: this.$route.query.order ?? null,
        };
        this.fetchCarDealerListings({ page: this.$route.query.page || 1 });
    },
    watch: {
        '$route.query': {
            handler(newQuery) {
                this.filters = {
                    deleted: newQuery.deleted ?? null,
                    by: newQuery.by ?? null,
                    order: newQuery.order ?? null,
                };
                this.fetchCarDealerListings({ page: newQuery.page || 1 });
            },
            immediate: true,
        },
    },
}
</script>
