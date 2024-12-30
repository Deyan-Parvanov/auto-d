<template>
  <form @submit.prevent="create">
    <div class="grid grid-cols-6 gap-4">
      <div class="col-span-2">
        <label class="label">Category</label>
        <input v-model="form.category" type="text" class="input" />
        <div v-if="listingStore.errors.category">
          {{ listingStore.errors.category }}
        </div>
      </div>

      <div class="col-span-2">
        <label class="label">Make</label>
        <input v-model="form.make" type="text" class="input" />
        <div v-if="listingStore.errors.make" class="input-error">
          {{ listingStore.errors.make }}
        </div>
      </div>

      <div class="col-span-2">
        <label class="label">Model</label>
        <input v-model="form.model" type="text" class="input" />
        <div v-if="listingStore.errors.model" class="input-error">
          {{ listingStore.errors.model }}
        </div>
      </div>

      <div class="col-span-2">
        <label class="label">Year</label>
        <input v-model="form.year" type="text" class="input" />
        <div v-if="listingStore.errors.year" class="input-error">
          {{ listingStore.errors.year }}
        </div>
      </div>

      <div class="col-span-2">
        <label class="label">Engine Type</label>
        <input v-model="form.engine_type" type="text" class="input" />
        <div v-if="listingStore.errors.engine_type" class="input-error">
          {{ listingStore.errors.engine_type }}
        </div>
      </div>

      <div class="col-span-2">
        <label class="label">Horse power</label>
        <input v-model.number="form.horsepower" type="text" class="input" />
        <div v-if="listingStore.errors.horsepower" class="input-error">
          {{ listingStore.errors.horsepower }}
        </div>
      </div>

      <div class="col-span-2">
        <label class="label">Total Kilometers</label>
        <input v-model.number="form.total_kilometers" type="text" class="input" />
        <div v-if="listingStore.errors.total_kilometers" class="input-error">
          {{ listingStore.errors.total_kilometers }}
        </div>
      </div>

      <div class="col-span-2">
        <label class="blabel">Color</label>
        <input v-model="form.color" type="text" class="input" />
        <div v-if="listingStore.errors.color" class="input-error">
          {{ listingStore.errors.color }}
        </div>
      </div>

      <div class="col-span-2">
        <label class="label">City</label>
        <input v-model="form.city" type="text" class="input" />
        <div v-if="listingStore.errors.city" class="input-error">
          {{ listingStore.errors.city }}
        </div>
      </div>

      <div class="col-span-6">
        <label class="label">Price</label>
        <input v-model.number="form.price" type="text" class="input" />
        <div v-if="listingStore.errors.price" class="input-error">
          {{ listingStore.errors.price }}
        </div>
      </div>

      <div class="col-span-6">
        <button type="submit" class="btn-primary">Create</button>
      </div>
    </div>
  </form>
</template>

<script setup>
import { ref } from 'vue';
import { useListingCreateStore } from '../../stores/useListingsCreateStore';
import { useRouter } from 'vue-router';

const form = ref({
  category: null,
  make: null,
  model: null,
  year: null,
  engine_type: null,
  horsepower: 0,
  total_kilometers: 0,
  color: null,
  city: null,
  price: 0,
});

const listingStore = useListingCreateStore();
const router = useRouter();

const create = async () => {
  try {
    await listingStore.createListing(form.value);
    alert('Listing created successfully');
    router.push('/listing');
  } catch (error) {
    console.error('Failed to create listing', error);
  }
};
</script>
  
<style scoped>
label {
margin-right: 2em;
}

div {
padding: 2px
}
</style>
