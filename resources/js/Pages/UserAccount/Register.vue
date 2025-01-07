<template>
    <div>
        <div v-if="errorMessage" class="error-popup">
            {{ errorMessage }}
        </div>

        <form @submit.prevent="onRegister">
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
                <div>
                    <label for="username" class="label">Username</label>
                    <input id="username" v-model="v$.form.name.$model" type="text" class="input"
                        placeholder="Username..." />
                    <div v-if="v$.form.name.$error" class="input-error">
                        Please enter a valid username
                    </div>
                </div>
                <div class="mt-4">
                    <label for="password" class="label">Password</label>
                    <input id="password" v-model="v$.form.password.$model" type="password" class="input"
                        placeholder="Password..." />
                    <div v-if="v$.form.password.$error" class="input-error">
                        Password must be at least 3 characters long
                    </div>
                </div>
                <div class="mt-4">
                    <label for="password_confirmation" class="label">Confirm Password</label>
                    <input id="password_confirmation" v-model="v$.form.confirmPassword.$model" type="password"
                        class="input" placeholder="Confirm password..." />
                    <div v-if="v$.form.confirmPassword.$error" class="input-error">
                        Passwords do not match
                    </div>
                </div>
                <div class="mt-4">
                    <button class="btn-primary w-full" type="submit">Register</button>
                    <div class="mt-2 text-center">
                        <router-link to="/login" class="text-sm text-gray-500">
                            Already have an account? Click here
                        </router-link>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import { useUserStore } from '@/stores/useUserStore';
import useVuelidate from '@vuelidate/core';
import { required, email, minLength, sameAs } from '@vuelidate/validators';

export default {
    setup() {
        return { v$: useVuelidate(), userStore: useUserStore() };
    },
    data() {
        return {
            form: {
                email: null,
                name: null,
                password: null,
                confirmPassword: null,
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
                name: {
                    required,
                },
                password: {
                    required,
                    minLength: minLength(3),
                },
                confirmPassword: {
                    required,
                    matchesPassword: {
                        $validator: (confirmPassword) => confirmPassword === this.form.password,
                        $message: 'Passwords must match',
                    },
                },
            },
        };
    },
    methods: {
        async onRegister() {
            const isValid = await this.v$.$validate();
            if (!isValid) return;

            try {
                const registerResponse = await this.userStore.registerUser(this.form);

                if (!registerResponse.errors) {
                    this.$router.push({ path: '/listing' });
                } else {
                    this.errorMessage = registerResponse.message;

                    setTimeout(() => {
                        this.errorMessage = null;
                    }, 3000);
                }
            } catch (error) {
                this.errorMessage = error.response?.data?.message || 'Registration failed. Please try again.';
                setTimeout(() => {
                    this.errorMessage = null;
                }, 3000);
            }
        },
    },
    watch: {
        'form.password'() {
            this.v$.form.confirmPassword.$touch();
        },
    },
};
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
