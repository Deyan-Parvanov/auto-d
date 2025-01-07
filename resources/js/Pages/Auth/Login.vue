<template>
  <div>
    <div v-if="errorMessage" class="error-popup">
      {{ errorMessage }}
    </div>

    <form @submit.prevent="onLogin">
      <div class="w-1/2 mx-auto">
        <div>
          <label for="email" class="label">Email</label>
          <input id="email" v-model="v$.form.email.$model" type="text" class="input" placeholder="Email..." />
          <div v-if="v$.form.email.$error" class="input-error">
            <div v-if="v$.form.email.$pending">Validating...</div>
            <div v-if="!v$.form.email.$pending && v$.form.email.$error">
              Please enter a valid email
            </div>
          </div>
        </div>
        <div class="mt-4">
          <label for="password" class="label">Password</label>
          <input id="password" v-model="v$.form.password.$model" type="password" class="input"
            placeholder="Password..." />
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
  </div>
</template>

<script>
import { useUserStore } from '@/stores/useUserStore';
import useVuelidate from '@vuelidate/core';
import { required, email } from '@vuelidate/validators';

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
      errorMessage: null,
    };
  },
  validations() {
    return {
      form: {
        email: {
          required,
          email,
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
      if (!isValid) return;

      try {
        const loginResponse = await this.userStore.loginUser(this.form);

        if (!loginResponse.errors) {
          const userStore = useUserStore();
          userStore.initializeUser();
          this.$router.push({ path: '/listing' });
        } else {
          this.errorMessage = loginResponse.errors.message[0];
          setTimeout(() => {
            this.errorMessage = null;
          }, 3000);
        }
      } catch (error) {
        this.errorMessage = error.response?.data?.message || 'Authentication failed.';
        setTimeout(() => {
          this.errorMessage = null;
        }, 3000);
      }
    },
  },
}
</script>


<style scoped>
.error-popup {
  position: fixed;
  top: 0;
  left: 50%;
  transform: translateX(-50%);
  background-color: #f44336;
  color: white;
  padding: 1rem;
  border-radius: 0.25rem;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  z-index: 1000;
  animation: slideDown 0.5s ease-in-out;
}

@keyframes slideDown {
  from {
    top: -50px;
  }

  to {
    top: 0;
  }
}
</style>