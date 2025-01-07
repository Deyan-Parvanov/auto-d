<template>
    <div>Index</div>
</template>

<script>
import { useListingsStore } from '@/stores/useListingsStore';

export default {
    setup() {
        const listingsStore = useListingsStore();

        listingsStore.fetchListings();

        return {
            listings: listingsStore.listings,
            loading: listingsStore.loading,
            error: listingsStore.error,
        };
    },
};
</script>
