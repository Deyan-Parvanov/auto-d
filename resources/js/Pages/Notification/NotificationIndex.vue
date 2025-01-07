<template>
    <h1 class="text-3xl mb-4">Your Notifications</h1>
    <section v-if="notifications.data.length" class="text-gray-700 dark:text-gray-400">
        <div v-for="notification in notifications.data" :key="notification.id"
            class="border-b border-gray-200 dark:border-gray-800 py-4 flex justify-between items-center">
            <div>
                <span v-if="notification.type === 'App\\Notifications\\OfferMade'">
                    Offer
                    <Price :price="notification.data.amount" /> for
                    <router-link :to="{ name: 'listingShow', params: { id: notification.data.listing_id } }"
                        class="text-indigo-600 dark:text-indigo-400">listing</router-link> was made
                </span>
            </div>
            <div>
                <button v-if="!notification.read_at" @click="markAsRead(notification.id)"
                    class="btn-outline text-xs font-medium uppercase">
                    Mark as read
                </button>
            </div>
        </div>
    </section>
    <EmptyState v-else>No notifications yet!</EmptyState>
    <section v-if="notifications.data.length" class="w-full flex justify-center mt-8 mb-8">
        <Pagination :links="notifications.links" />
    </section>
</template>

<script>
import Price from '@/Components/Price.vue';
import EmptyState from '@/Components/UI/EmptyState.vue';
import Pagination from '@/Components/UI/Pagination.vue';
import { useFlashMessageStore } from '@/stores/useFlashMessageStore';
import { useNotificationsStore } from '@/stores/useNotificationsStore';
import { useUserStore } from '@/stores/useUserStore';
import { storeToRefs } from 'pinia';

export default {
    data() {
        return {
            notifications: {
                data: [],
                links: [],
                meta: {},
            },
            filters: {},
        };
    },
    components: {
        Price,
        EmptyState,
        Pagination,
    },
    methods: {
        async fetchNotifications() {
            const notificationsStore = useNotificationsStore();
            this.loading = true;

            await notificationsStore.fetchNotifications();

            const { notifications } = storeToRefs(notificationsStore);
            this.notifications = notifications.value;

            this.loading = false;
        },
        async markAsRead(id) {
            const notificationsStore = useNotificationsStore();
            const userStore = useUserStore();
            const flashMessageStore = useFlashMessageStore();

            const response = await notificationsStore.markAsRead(id);

            userStore.initializeUser();
            this.fetchNotifications();
            flashMessageStore.setSuccessMessage(response.data.message);
        },
    },
    mounted() {
        this.fetchNotifications();
    },
}
</script>