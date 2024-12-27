<template>
    <div>Index</div>
    <Link href="/show">Show Page</Link>
    <div>
        The message is: {{ message }}
    </div>
</template>

<script setup>
import { useListingsStore } from '../../stores/useListingsStore';

defineProps({
    message: String,
}),
export default {
    setup() {
        const listingsStore = useListingsStore();

        // Fetch listings when the component is mounted
        listingsStore.fetchListings();

        return {
        listings: listingsStore.listings,
        loading: listingsStore.loading,
        error: listingsStore.error,
        };
    },
};
</script>
<!-- 
<script>
    // import MainLayout from '../../Layouts/MainLayout.vue';
    // export default {
    //     layout: MainLayout
    // }
</script> -->