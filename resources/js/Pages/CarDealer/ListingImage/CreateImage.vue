<template>
    <Box>
        <template #header>Upload New Images</template>
        <form @submit.prevent="upload" enctype="multipart/form-data">
            <section class="flex items-center gap-2 my-4">
                <input
                    class="border rounded-md file:px-4 file:py-2 border-gray-200 dark:border-gray-700 file:text-gray-700 file:dark:text-gray-400 file:border-0 file:bg-gray-100 file:dark:bg-gray-800 file:font-medium file:hover:bg-gray-200 file:dark:hover:bg-gray-700 file:hover:cursor-pointer file:mr-4"
                    type="file" multiple name="images[]" @change="handleFileChange" />
                <button type="submit" class="btn-outline disabled:opacity-25 disabled:cursor-not-allowed"
                    :disabled="!canUpload">Upload</button>
                <button type="reset" class="btn-outline" @click="reset">Reset</button>
            </section>
            <div v-if="error" class="input-error">
                {{ error }}
            </div>
        </form>
    </Box>

    <Box v-if="this.listing?.data?.listing?.images?.length" class="mt-4">
        <template #header>Current Listing Images</template>
        <section class="mt-4 grid grid-cols-3 gap-2">
            <div v-for="image in this.listing.data.listing.images" :key="image.id" class="flex flex-col justify-between">
                <img :src="image.src" class="rounded-md" />
                <button v-if="!listing.deleted_at" @click="deleteImage(image.id)"
                    class="btn-outline text-xs font-medium">
                    Delete
                </button>
            </div>
        </section>
    </Box>
</template>


<script>
import Box from '../../../Components/UI/Box.vue';
import apiClient from '../../../api';
import { useFlashMessageStore } from '@/stores/useFlashMessageStore';
import { useListingsStore } from '@/stores/useListingsStore';
import useVuelidate from '@vuelidate/core';

export default {
    data() {
        return {
            listing: null,
            files: [],
            error: '',
        };
    },
    components: {
        Box,
    },
    computed: {
        canUpload() {
            return this.files.length;
        },
        imageErrors() {
            const errors = !this.errors ? [] : Object.values(this.errors);
        
            return errors;
        },
    },
    methods: {
        handleFileChange(event) {
            this.files = event.target.files;
        },
        async upload() {
            if (!this.files || this.files.length === 0) {
                this.error = 'Please select at least one file to upload.';
                return;
            }

            const listingId = this.$route.params.id;

            const formData = new FormData();
            for (let i = 0; i < this.files.length; i++) {
                formData.append('images[]', this.files[i]);
            }

            const flashMessageStore = useFlashMessageStore();

            try {
                const response = await apiClient.post(
                    `/car-dealer/listing/${listingId}/image`,
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        },
                    }
                );
                flashMessageStore.setSuccessMessage(response.data.message);
                this.reset();
                this.error = '';

                // this.listing = response.data;
            } catch (err) {
                this.error = err.response?.data?.errors?.images?.[0] || err.response?.data?.message || 'Failed to upload images.';
            }
        },
        reset() {
            this.files = [];
            this.error = '';
        },
        async deleteImage(imageId) {
            const listingsStore = useListingsStore();
            const flashMessageStore = useFlashMessageStore();
            try {
                const response = await listingsStore.deleteImage(imageId);
                this.listing.data.images = this.listing.data.images.filter(image => image.id !== imageId);

                flashMessageStore.setSuccessMessage(response.data.message || 'Image deleted successfully.');
            } catch (error) {
                this.error = error.response?.message || 'Failed to delete image.';
            }
        },
    },
    async mounted() {
        const listingsStore = useListingsStore();
        const id = this.$route.params.id;
        this.listing = await listingsStore.fetchListing(id);
    },
    setup() {
        return { v: useVuelidate() };
    },
};
</script>
