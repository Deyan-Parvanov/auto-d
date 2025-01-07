<template>
    <h1 class="text-3xl mb-4">Your Listings</h1>
    <section>
        <CarDealerFilters :filters="filters" />
    </section>
    <section v-if="dealerListings.data.length" class="grid grid-cols-1 lg:grid-cols-2 gap-2">
        <Box v-for="listing in dealerListings.data" :key="listing.id" :class="{ 'border-dashed': listing.deleted_at }">
            <div class="flex flex-col md:flex-row gap-2 md:items-center justify-between">
                <div :class="{ 'opacity-25': listing.deleted_at }">
                    <div v-if="listing.sold_at != null"
                        class="text-xs font-bold uppercase border border-dashed p-1 border-green-300 text-green-500 dark:border-green-600 dark:text-green-600 inline-block rounded-md mb-2">
                        sold
                    </div>
                    <div class="xl:flex items-center gap-2">
                        <Price :price="listing.price" class="text-2xl font-medium" />
                        <ListingSpace :listing="listing" />
                    </div>
                    <ListingAddress :listing="listing" :id="listing.id" />
                </div>
                <section>
                    <div class="flex items-center gap-1 text-gray-600 dark:text-gray-300">
                        <router-link v-if="!listing.deleted_at"
                            :to="{ name: 'listingShow', params: { id: listing.id } }"
                            class="btn-outline text-xs font-medium" target="_blank">
                            Preview
                        </router-link>
                        <router-link v-if="!listing.deleted_at"
                            :to="{ name: 'listingEdit', params: { id: listing.id } }"
                            class="btn-outline text-xs font-medium">
                            Edit
                        </router-link>
                        <div>
                            <button v-if="!listing.deleted_at" @click="deleteListing(listing.id)"
                                class="btn-outline text-xs font-medium">
                                Delete
                            </button>
                            <button v-else @click="restoreListing(listing.id)" class="btn-outline text-xs font-medium"
                                method="put">
                                Restore
                            </button>
                        </div>
                    </div>
                    <div v-if="!listing.deleted_at" class="mt-2">
                        <router-link :to="{ name: 'listingImage', params: { id: listing.id } }"
                            class="block w-full btn-outline text-xs font-medium text-center"
                            @click.native="setListingData(listing)">
                            Images ({{ listing.images_count }})
                        </router-link>
                    </div>
                    <div class="mt-2">
                        <router-link :to="{ name: 'carDealerListingShow', params: { id: listing.id } }"
                            class="block w-full btn-outline text-xs font-medium text-center">
                            Offers ({{ listing.offers_count }})
                        </router-link>
                    </div>
                </section>
            </div>
        </Box>
    </section>
    <EmptyState v-else>No listings yet</EmptyState>
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
import EmptyState from '@/Components/UI/EmptyState.vue';
import { useCarDealerStore } from '../../stores/useCarDealerStore.js';
import { useUserStore } from '../../stores/useUserStore';
import { useListingsStore } from '../../stores/useListingsStore';
import { storeToRefs } from 'pinia';
import { useFlashMessageStore } from '@/stores/useFlashMessageStore';

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
        EmptyState,
        Pagination,
        CarDealerFilters,

    },
    methods: {
        async fetchCarDealerListings({ page = 1 } = {}) {
            const carDealerStore = useCarDealerStore();
            this.loading = true;

            const params = { ...this.filters, page };

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

            if (!token.is_admin && listing.data.listing.by_user_id !== userId) {
                listingsStore.error = 'You are not authorized to delete this listing.';
                return;
            }

            if (confirm('Are you sure you want to delete this listing?')) {
                const response = await listingsStore.deleteListing(id);
                this.fetchCarDealerListings({ page: this.$route.query.page || 1 });

                const flashMessageStore = useFlashMessageStore();
                flashMessageStore.setSuccessMessage(response.data.message);
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
            const listing = await listingsStore.fetchListing(id);
            if (!listing) {
                listingsStore.error = 'Listing not found.';
                return;
            }

            const userId = token.id;

            if (!token.is_admin && listing.data.listing.by_user_id !== userId) {
                listingsStore.error = 'You are not authorized to delete this listing.';
                return;
            }

            const response = await listingsStore.restoreListing(id);
            this.fetchCarDealerListings({ page: this.$route.query.page || 1 });

            const flashMessageStore = useFlashMessageStore();
            flashMessageStore.setSuccessMessage(response.data.message);
        },
        async setListingData(listing) {
            const listingStore = useListingsStore();
            listingStore.setListing(listing);
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
