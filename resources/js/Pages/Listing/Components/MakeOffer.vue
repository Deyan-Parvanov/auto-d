<template>
    <Box>
        <template #header>Make an Offer</template>
        <div>
            <form @submit.prevent="makeOffer">
                <input v-model.number="form.amount" type="text" class="input" />
                <input v-model.number="form.amount" type="range" :min="min" :max="max" step="1000"
                    class="mt-2 w-full h-4 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700" />
                <button type="submit" class="btn-outline w-full mt-2 text-sm">
                    Make an Offer
                </button>
            </form>
        </div>
        <div v-if="errors.amount" class="text-red-500 text-sm mt-2">
            {{ errors.amount[0] }}
        </div>
        <div class="flex justify-between text-gray-500 mt-2">
            <div>Difference</div>
            <div>
                <Price :price="difference" />
            </div>
        </div>
    </Box>
</template>

<script>
import { debounce } from "lodash";
import Price from "@/Components/Price.vue";
import Box from "@/Components/UI/Box.vue";
import { useOffersStore } from "@/stores/useOffersStore";
import { reactive, watch, computed } from "vue";
import { useUserStore } from "@/stores/useUserStore";
import { useFlashMessageStore } from "@/stores/useFlashMessageStore";

export default {
    name: "MakeOffer",
    components: {
        Price,
        Box,
    },
    props: {
        listingId: {
            type: Number,
            required: true,
        },
        price: {
            type: Number,
            required: true,
        },
    },
    emits: ["offerUpdated"],
    data() {
        return {
            form: reactive({
                amount: this.price,
            }),
        };
    },
    computed: {
        min() {
            return Math.round(this.price / 2);
        },
        max() {
            return Math.round(this.price * 2);
        },
        difference() {
            return this.form.amount - this.price;
        },
        errors() {
            const offersStore = useOffersStore();
            return offersStore.errors;
        },
    },
    methods: {
        async makeOffer() {
            const offersStore = useOffersStore();
            const userStore = useUserStore();
            const flashMessageStore = useFlashMessageStore();

            try {
                const response = await offersStore.makeOffer({
                    listing_id: this.listingId,
                    amount: this.form.amount,
                });

                userStore.initializeUser();
                flashMessageStore.setSuccessMessage(response.message);
            } catch (error) {
                console.error("Failed to make an offer:", error);
            }
        },
        emitOfferUpdated(value) {
            this.$emit("offerUpdated", value);
        },
    },
    watch: {
        "form.amount": {
            handler: debounce(function (value) {
                this.emitOfferUpdated(value);
            }, 200),
        },
    },
};
</script>
