import { useListingsStore } from '@/stores/useListingsStore';
import { useUserStore } from '@/stores/useUserStore';
import { useFlashMessageStore } from '@/stores/useFlashMessageStore';

export const handleListingAction = async ({ id, action, successMessage, router, fetchCallback }) => {
    const userStore = useUserStore();
    const listingsStore = useListingsStore();
    const flashMessageStore = useFlashMessageStore();

    const token = userStore.user || localStorage.getItem('authToken');
    if (!token) {
        router.push({ name: 'login' });
        return;
    }

    const listing = await listingsStore.fetchListing(id);
    if (!listing) {
        listingsStore.error = 'Listing not found.';
        return;
    }

    const userId = token.id;
    if (!token.is_admin && listing.data.listing.by_user_id !== userId) {
        listingsStore.error = 'You are not authorized to perform this action.';
        return;
    }

    if (action === 'delete' && !confirm('Are you sure you want to delete this listing?')) {
        return;
    }

    const response =
        action === 'delete'
            ? await listingsStore.deleteListing(id)
            : await listingsStore.restoreListing(id);

    fetchCallback({ page: router.currentRoute.value.query.page || 1 });
    flashMessageStore.setSuccessMessage(response.data.message || successMessage);
};
