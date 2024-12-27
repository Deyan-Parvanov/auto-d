<template>
  <form @submit.prevent="updateListing">
    <div class="grid grid-cols-6 gap-4">
      <div class="col-span-2">
        <label class="label">Category</label>
        <input v-model="form.category" type="text" class="input" />
        <div v-if="errors.category" class="input-error">{{ errors.category }}</div>
      </div>

      <div class="col-span-2">
        <label class="label">Make</label>
        <input v-model="form.make" type="text" class="input" />
        <div v-if="errors.make">{{ errors.make }}</div>
      </div>

      <div class="col-span-2">
        <label class="label">Model</label>
        <input v-model="form.model" type="text" class="input" />
        <div v-if="errors.model">{{ errors.model }}</div>
      </div>

      <div class="col-span-2">
        <label class="label">Year</label>
        <input v-model="form.year" type="text" class="input" />
        <div v-if="errors.year">{{ errors.model }}</div>
      </div>

      <div class="col-span-2">
        <label class="label">Engine Type</label>
        <input v-model="form.engine_type" type="text" class="input" />
        <div v-if="errors.engine_type">{{ errors.engine_type }}</div>
      </div>

      <div class="col-span-2">
        <label class="label">Horse power</label>
        <input v-model.number="form.horsepower" type="text" class="input" />
        <div v-if="errors.horsepower">{{ errors.horsepower }}</div>
      </div>

      <div class="col-span-2">
        <label class="label">Total Kilometers</label>
        <input v-model.number="form.total_kilometers" type="text" class="input" />
        <div v-if="errors.total_kilometers" class="input-error">{{ errors.total_kilometers }}</div>
      </div>

      <div class="col-span-2">
        <label class="label">Color</label>
        <input v-model="form.color" type="text" class="input" />
        <div v-if="errors.color" class="input-error">{{ errors.color }}</div>
      </div>

      <div class="col-span-2">
        <label class="label">City</label>
        <input v-model="form.city" type="text" class="input" />
        <div v-if="errors.city" class="input-error">{{ errors.city }}</div>
      </div>

      <div class="col-span-6">
        <label class="label">Price</label>
        <input v-model.number="form.price" type="text" class="input" />
        <div v-if="errors.price" class="input-error">{{ errors.price }}</div>
      </div>

      <div class="col-span-2">
        <button type="submit" class="btn-primary">Update</button>
      </div>
    </div>
  </form>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import apiClient from '../../api';

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

const errors = ref({});
const route = useRoute();
const router = useRouter();
const id = route.params.id;

const fetchListing = async () => {
    try {
        const response = await apiClient.get(`/listing/${id}/edit`);
        
        Object.assign(form.value, response.data);
    } catch (error) {
        console.error(error);
    }
};

const updateListing = async () => {
    try {
        await apiClient.put(`/listing/${id}`, form.value);
        router.push('/listing'); // Redirect to listings page
    } catch (error) {
        if (error.response && error.response.data.errors) {
            errors.value = error.response.data.errors;
        }
        console.error(error);
    }
};

onMounted(() => {
    fetchListing();
});
</script>

<style scoped>
label {
  margin-right: 2em;
}
div {
  padding: 2px
}
</style>
