<template>
  <form @submit.prevent="create">
    <div class="grid grid-cols-6 gap-4">
      <div class="col-span-3" v-for="field in fields" :key="field.name">
        <label class="label">{{ field.label }}</label>
        <input v-model="form[field.name]" :type="field.type" class="input"
          :class="{ 'border-red-500': $v[field.name]?.$invalid && $v[field.name]?.$dirty }" />
        <div v-if="$v[field.name]?.$invalid && $v[field.name]?.$dirty" class="input-error">
          {{ fieldError(field.name) }}
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
import { useListingsStore } from '@/stores/useListingsStore';
import { useRouter } from 'vue-router';
import useVuelidate from '@vuelidate/core';
import { required, numeric, minValue, maxValue } from '@vuelidate/validators';

const fields = [
  { name: 'category', label: 'Category', type: 'text' },
  { name: 'make', label: 'Make', type: 'text' },
  { name: 'model', label: 'Model', type: 'text' },
  { name: 'year', label: 'Year', type: 'text' },
  { name: 'engine_type', label: 'Engine Type', type: 'text' },
  { name: 'horsepower', label: 'Horse Power', type: 'number' },
  { name: 'total_kilometers', label: 'Total Kilometers', type: 'number' },
  { name: 'color', label: 'Color', type: 'text' },
  { name: 'city', label: 'City', type: 'text' },
  { name: 'price', label: 'Price', type: 'number' },
];

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
const listingStore = useListingsStore();
const router = useRouter();

const rules = {
  category: { required },
  make: { required },
  model: { required },
  year: {
    required,
    numeric,
    minValue: minValue(1900),
    maxValue: maxValue(new Date().getFullYear()),
  },
  engine_type: {},
  horsepower: { numeric },
  total_kilometers: { numeric },
  color: { required },
  city: { required },
  price: {
    required,
    numeric,
    minValue: minValue(0),
  },
};

const $v = useVuelidate(rules, form);

const create = async () => {
  $v.value.$touch();
  if ($v.value.$invalid) {
    return;
  }

  try {
    const response = await listingStore.createListing(form.value);

    if (response.errors) {
      mapBackendErrors(response.errors);
    } else {
      alert('Listing created successfully');
      router.push('/listing');
    }

    return;
  } catch (error) {
    if (error.response && error.response.data.errors) {
      mapBackendErrors(error.response.data.errors);
    }
    console.error('Failed to create listing', error);
  }
};

const mapBackendErrors = (backendErrors) => {
  errors.value = {};
  for (const field in backendErrors) {
    errors.value[field] = backendErrors[field].join(', ');
  }
  console.log(errors.value);
  
};

const fieldError = (fieldName) => {
  if (errors.value[fieldName]) {
    return errors.value[fieldName];
  }

  const fieldState = $v.value[fieldName];
  if (fieldState && fieldState.$dirty && fieldState.$invalid) {
    const firstError = fieldState.$errors[0]?.$message || 'This field is invalid';
    return firstError;
  }

  return '';
};
</script>

<style scoped>
.label {
  margin-right: 2em;
}

.input {
  border: 1px solid #ccc;
  padding: 0.5em;
}

.input-error {
  color: red;
  font-size: 0.9em;
  margin-top: 0.5em;
}

.border-red-500 {
  border-color: red;
}
</style>
