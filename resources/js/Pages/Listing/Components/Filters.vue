<template>
  <form @submit.prevent="applyFilters">
    <div class="mb-8 mt-4 flex flex-wrap gap-2">
      <div class="flex flex-nowrap items-center">
        <input v-model.number="filterForm.priceFrom" type="text" placeholder="Price from" class="input-filter-l w-28" />
        <input v-model.number="filterForm.priceTo" type="text" placeholder="Price to" class="input-filter-r w-28" />
      </div>
      <div class="flex flex-nowrap items-center">
        <select v-model="filterForm.make" class="input-filter-l w-28">
          <option :value="null">Make</option>
          <option v-for="make in availableMakes" :key="make" :value="make">{{ make }}</option>
        </select>
        <select v-model="filterForm.engine" class="input-filter-r w-28">
          <option :value="null">Engine</option>
          <option v-for="engine in availableEngineTypes" :key="engine" :value="engine">{{ engine }}</option>
        </select>
      </div>
      <div class="flex flex-nowrap items-center">
        <input v-model.number="filterForm.kmFrom" type="text" placeholder="Km from" class="input-filter-l w-28" />
        <input v-model.number="filterForm.kmTo" type="text" placeholder="Km to" class="input-filter-r w-28" />
      </div>
      <button type="submit" class="btn-normal">Filter</button>
      <button type="reset" @click="clearFilters">Clear</button>
    </div>
  </form>
</template>

<script>
import { mapActions, mapState } from 'pinia';
import { useListingsStore } from '@/stores/useListingsStore';

export default {
  name: 'Filters',
  props: {
    filters: {
      type: Object,
      default: () => ({}),
    },
  },
  data() {
    return {
      filterForm: {
        priceFrom: this.filters.priceFrom ?? null,
        priceTo: this.filters.priceTo ?? null,
        make: this.filters.make ?? null,
        engine: this.filters.engine ?? null,
        kmFrom: this.filters.kmFrom ?? null,
        kmTo: this.filters.kmTo ?? null,
      },
    };
  },
  computed: {
    ...mapState(useListingsStore, {
      availableMakes: (state) => state.availableMakes,
      availableEngineTypes: (state) => state.availableEngineTypes,
    }),
    routeQuery() {
      return { ...this.$route.query };
    },
  },
  methods: {
    ...mapActions(useListingsStore, ['fetchListings']),
    async updateQueryParams() {
      const { priceFrom, priceTo, make, engine, kmFrom, kmTo } = this.filterForm;

      await this.$router.push({
        path: this.$route.path,
        query: {
          ...this.routeQuery,
          page: 1,
          priceFrom,
          priceTo,
          make,
          engine,
          kmFrom,
          kmTo,
        },
      });
    },
    async applyFilters() {
      await this.updateQueryParams();
      await this.$nextTick();

      const params = { ...this.$route.query };
      await this.fetchListings(params);
    },
    clearFilters() {
      this.filterForm = {
        priceFrom: null,
        priceTo: null,
        make: null,
        engine: null,
        kmFrom: null,
        kmTo: null,
      };

      this.$router.push({
        path: this.$route.path,
        query: {},
      });

      this.fetchListings({});
    },
  },
  watch: {
    '$route.query': {
      handler(newQuery) {
        this.filterForm = {
          priceFrom: newQuery.priceFrom ?? null,
          priceTo: newQuery.priceTo ?? null,
          make: newQuery.make ?? null,
          engine: newQuery.engine ?? null,
          kmFrom: newQuery.kmFrom ?? null,
          kmTo: newQuery.kmTo ?? null,
        };
      },
      immediate: true,
    },
  },
  async mounted() {
    const listingsStore = useListingsStore();
    await listingsStore.fetchAvailableMakes();
    await listingsStore.fetchEngineTypes();
  },
};
</script>
