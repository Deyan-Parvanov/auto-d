<template>
<form @submit.prevent="onRegister">
    <div class="w-1/2 mx-auto">
    <div>
        <label for="email" class="label">Email</label>
        <input id="email" v-model="v$.form.email.$model" type="text" class="input" placeholder="Email..." />
        <div v-if="v$.form.email.$error" class="input-error">
            Please enter a valid email
        </div>
    </div>
    <div>
        <label for="username" class="label">Username</label>
        <input id="username" v-model="v$.form.name.$model" type="text" class="input" placeholder="Username..." />
        <div v-if="v$.form.name.$error" class="input-error">
            Please enter a valid username
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
        <label for="password_confirmation" class="label">Confirm Password</label>
        <input id="password_confirmation" v-model="v$.form.confirmPassword.$model" type="password" class="input" placeholder="Confirm password..." />
        <div v-if="v$.form.confirmPassword.$error" class="input-error">
            Please enter a valid password
        </div>
    </div>
    <div class="mt-4">
        <button class="btn-primary w-full" type="submit">Register</button>
        <div class="mt-2 text-center">
          <router-link to="/login" class="text-sm text-gray-500">Already have an account? Click here</router-link>
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
                confirmPassword: null,
            },
        };
    },
    validations() {
        return {
            form: {
                email: {
                    required,
                },
                name: {
                    String,
                },
                password: {
                    required,
                },
                confirmPassword: {
                    required,
                }
            },
        };
    },
    methods: {
    async onRegister() {
      const isValid = await this.v$.$validate();
      if (!isValid)
        return;

      const registerSuccessful = await this.userStore.registerUser(this.form);

      if (registerSuccessful) {
        this.$router.push({ path: '/listing' });
      }
    },
  },
}
</script>