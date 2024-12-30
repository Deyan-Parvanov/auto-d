<template>
    <form>
        <div class="mb-4 mt-4 flex flex-wrap gap-2">
            <div class="flex flex-nowrap items-center gap-2">
                <input id="deleted" v-model="filterForm.deleted" type="checkbox"
                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                <label for="deleted">Deleted</label>
            </div>
            <div>
                <select v-model="filterForm.by" class="input-filter-l w-24">
                    <option value="created_at">Added</option>
                    <option value="price">Price</option>
                </select>
                <select v-model="filterForm.order" class="input-filter-r w-32">
                    <option v-for="option in sortOptions" :key="option.value" :value="option.value">
                        {{ option.label }}
                    </option>
                </select>
            </div>
        </div>
    </form>
</template>

<script>
import { mapActions } from 'pinia';
import { defineComponent } from 'vue';
import { useCarDealerStore } from '@/stores/useCarDealerStore';
import { debounce } from 'lodash';

export default defineComponent({
    name: 'CarDealerFilters',
    props: {
        filters: {
            type: Object,
            default: () => ({}),
        },
    },
    data() {
        return {
            filterForm: {
                deleted: this.filters.deleted ?? null,
                by: this.filters.by ?? null,
                order: this.filters.order ?? null,
            },
            sortLabels: {
                created_at: [
                    { label: 'Latest', value: 'desc', },
                    { label: 'Oldest', value: 'asc', },
                ],
                price: [
                    { label: 'Expensive', value: 'desc', },
                    { label: 'Cheapest', value: 'asc', },
                ],
            },
        };
    },
    computed: {
        sortOptions() {
            return this.sortLabels[this.filterForm.by];
        },
    },
    methods: {
        ...mapActions(useCarDealerStore, ['fetchCarDealerListings']),
        async updateQueryParams() {
            const { deleted, by, order } = this.filterForm;

            await this.$router.push({
                path: this.$route.path,
                query: {
                    ...this.routeQuery,
                    page: 1,
                    deleted,
                    by,
                    order,
                },
            });
        },
        async fetchListings() {
            await this.updateQueryParams();
            const params = { ...this.$route.query };

            this.fetchCarDealerListings(params);
        },
    },
    watch: {
        filterForm: {
            handler: debounce(function () {
                this.updateQueryParams();
            }, 1000),
            deep: true,
        },
        '$route.query': {
            handler(newQuery) {
                this.filterForm = {
                    deleted: newQuery.deleted ?? null,
                    by: newQuery.by ?? null,
                    order: newQuery.order ?? null,
                };
            },
            immediate: true,
        },
    },
});
</script>
