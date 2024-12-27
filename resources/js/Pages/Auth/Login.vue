<template>
<form @submit.prevent="onLogin">
    <div class="w-1/2 mx-auto">
    <div>
        <label for="email" class="label">Email</label>
        <input id="email" v-model="v$.form.email.$model" type="text" class="input" placeholder="Email..." />
        <div v-if="v$.form.email.$error" class="input-error">
          Please enter a valid email
      </div>
    </div>
    <div class="mt-4">
        <label for="password" class="label">Password</label>
        <input id="password" v-model="v$.form.password.$model" type="password" class="input" placeholder="Password..." />
        <div v-if="v$.form.password.$error" class="input-error">
          Please enter a valid password
      </div>
    </div>
    <div class="mt-4">
        <button class="btn-primary w-full" type="submit">Login</button>
        <div class="mt-2 text-center">
          <router-link to="/register" class="text-sm text-gray-500">Need an account? Click here</router-link>
        </div>
    </div>
    </div>
</form>
</template>
  
<script>
import { useUserStore } from '@/stores/useUserStore';
import useVuelidate from '@vuelidate/core';
import { required } from '@vuelidate/validators';


export default {
  setup() {
    return { v$: useVuelidate(), userStore: useUserStore() };
  },
  data() {
    return {
        form: {
            email: null,
            password: null,
        },
    };
  },
  validations() {
    return {
      form: {
        email: {
          required,
        },
        password: {
          required,
        },
      },
    };
  },
  methods: {
    async onLogin() {
      const isValid = await this.v$.$validate();
      if (!isValid)
        return;

      const loginSuccessful = await this.userStore.loginUser(this.form);

      if (loginSuccessful) {
        this.$router.push({ path: '/listing' });
      }
    },
  },
}
</script>