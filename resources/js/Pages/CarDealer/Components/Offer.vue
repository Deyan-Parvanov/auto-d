<template>
    <Box>
        <template #header>Offer #{{ offer.id }} <span v-if="offer.accepted_at" class="dark:bg-green-900 dark:text-green-200 bg-green-200 text-green-900 p-1 rounded-md uppercase ml-1">accepted</span></template>
        <section class="flex items-center justify-between">
            <div>
                <Price :price="offer.amount" class="text-xl" />
                <div class="text-gray-500">
                    Difference
                    <Price :price="difference" />
                </div>
                <div class="text-gray-500 text-sm">
                    Made by {{ offer.bidder.name }}
                </div>
                <div class="text-gray-500 text-sm">
                    Made on {{ madeOn }}
                </div>
            </div>
            <div>
                <button v-if="!isSold" class="btn-outline text-xs font-medium" as="button" @click="acceptOffer">
                    Accept
                </button>
            </div>
        </section>
    </Box>
</template>

<script>
import Price from '@/Components/Price.vue'
import Box from '@/Components/UI/Box.vue'
import { useOffersStore } from '@/stores/useOffersStore.js';
import { useFlashMessageStore } from '@/stores/useFlashMessageStore';

export default {
    components: {
        Price,
        Box,
    },
    props: {
        offer: {
            type: Object,
            required: true,
        },
        listingPrice: {
            type: Number,
            required: true,
        },
        isSold: Boolean,
    },
    computed: {
        difference() {
            return this.offer.amount - this.listingPrice;
        },
        madeOn() {
            return new Date(this.offer.created_at).toDateString();
        },
    },
    methods: {
        async acceptOffer() {
            const offersStore = useOffersStore();
            const flashMessageStore = useFlashMessageStore();

            try {
                const response = await offersStore.acceptOfferAction(this.offer.id);
                flashMessageStore.setSuccessMessage(response.message);
            } catch (error) {
                console.error('Error accepting the offer:', error);
            }
        },
    },
};
</script>
