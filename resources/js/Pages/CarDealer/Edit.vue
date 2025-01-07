<template>
  <form @submit.prevent="updateListing">
    <div class="grid grid-cols-6 gap-4">
      <div class="col-span-3" v-for="field in fields" :key="field.name">
        <label class="label">{{ field.label }}</label>
        <input v-model="form[field.name]" :type="field.type" class="input"
          :class="{ 'border-red-500': showError(field.name) }" @input="clearFieldError(field.name)" />
        <div v-if="showError(field.name)" class="input-error">
          {{ getFieldError(field.name) }}
        </div>
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
import useVuelidate from '@vuelidate/core';
import { required, numeric, minValue, maxValue } from '@vuelidate/validators';
import apiClient from '@/api';

const fields = [
  { name: 'category', label: 'Category', type: 'text' },
  { name: 'make', label: 'Make', type: 'text' },
  { name: 'model', label: 'Model', type: 'text' },
  { name: 'year', label: 'Year', type: 'text' },
  { name: 'engine_type', label: 'Engine Type', type: 'text' },
  { name: 'horsepower', label: 'Horsepower', type: 'number' },
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
  horsepower: null,
  total_kilometers: null,
  color: null,
  city: null,
  price: null,
});

const errors = ref({});
const route = useRoute();
const router = useRouter();
const id = route.params.id;

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

const fetchListing = async () => {
  try {
    const response = await apiClient.get(`/car-dealer/listing/${id}/edit`);
    Object.assign(form.value, response.data);
  } catch (error) {
    console.error(error);
  }
};

const updateListing = async () => {
  $v.value.$touch();
  if ($v.value.$invalid) {
    console.error('Invalid data.');
    return;
  }

  try {
    await apiClient.put(`/car-dealer/listing/${id}`, form.value);
    router.push('/car-dealer/listing');
  } catch (error) {
    if (error.response && error.response.data.errors) {
      mapBackendErrors(error.response.data.errors);
    }
    console.error(error);
  }
};

const mapBackendErrors = (backendErrors) => {
  for (const field in backendErrors) {
    errors.value[field] = backendErrors[field].join(', ');
  }
};

const clearFieldError = (fieldName) => {
  if (errors.value[fieldName]) {
    delete errors.value[fieldName];
  }
  $v.value[fieldName]?.$reset();
};

const getFieldError = (fieldName) => {
  if (errors.value[fieldName]) {
    return errors.value[fieldName];
  }

  const fieldValidation = $v.value[fieldName];
  if (fieldValidation?.$error) {
    if (fieldValidation?.required?.$invalid) return 'This field is required.';
    if (fieldValidation?.numeric?.$invalid) return 'This field must be numeric.';
    if (fieldName === 'year') {
      if (fieldValidation?.minValue?.$invalid) return 'Year must be at least 1900.';
      if (fieldValidation?.maxValue?.$invalid) return `Year cannot exceed ${new Date().getFullYear()}.`;
    }
    if (fieldName === 'price' && fieldValidation?.minValue?.$invalid) {
      return 'Price must be at least 0.';
    }
  }
  return '';
};

const showError = (fieldName) => {
  return errors.value[fieldName] || ($v.value[fieldName]?.$dirty && $v.value[fieldName]?.$invalid);
};

onMounted(() => {
  fetchListing();
});
</script>
