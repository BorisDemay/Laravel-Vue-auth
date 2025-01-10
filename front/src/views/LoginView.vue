<script setup lang="ts">
import { ref } from 'vue';
import { useUserStore } from '../stores/user';
import { useRouter } from 'vue-router';

const email = ref('');
const password = ref('');
const message = ref('');

const userStore = useUserStore();
const router = useRouter();

const login = async () => {
    try {
        await userStore.login(email.value, password.value, router);
        message.value = 'Login successful!';
    } catch (error) {
        console.error('Login error:', error);
        message.value = 'Login failed. Please try again.';
    }
};

const showPassword = ref(false);
const togglePasswordVisibility = () => {
    showPassword.value = !showPassword.value;
};
</script>

<template>
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-customWhite shadow-lg rounded-lg p-8 w-full max-w-md">
            <h1 class="text-2xl font-bold text-customBlack mb-6">Login</h1>
            <form @submit.prevent="login" class="space-y-4">

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-customBlack">Email</label>
                    <input id="email" v-model="email" type="email" placeholder="Enter your email"
                        class="mt-1 p-2 block w-full rounded-md shadow-sm border-customBlack border-2 text-sm text-customBlack"
                        required />
                    <small v-if="!email.includes('@') && email.length > 0" class="text-red-500 text-xs">
                        Please enter a valid email.
                    </small>
                </div>

                <!-- Password Field -->
                <div class="relative">
                    <label for="password" class="block text-sm font-medium text-customBlack">Password</label>
                    <div class="relative mt-1">
                        <!-- Password Input -->
                        <input id="password" v-model="password" :type="showPassword ? 'text' : 'password'"
                            placeholder="Enter your password"
                            class="block w-full rounded-md p-2 pr-10 shadow-sm border-customBlack border-2 text-sm text-customBlack"
                            required />
                        <!-- Toggle Icon -->
                        <button type="button"
                            class="absolute inset-y-0 right-2 flex items-center text-customBlack hover:text-gray-700"
                            @click="togglePasswordVisibility">
                            <i v-if="!showPassword" class="fas fa-eye text-lg"></i>
                            <i v-else class="fas fa-eye-slash text-lg"></i>
                        </button>
                    </div>
                </div>
                <!-- forget password button -->
                <div class="flex justify-end">
                    <router-link to="/forgot-password" class="text-sm text-customBlack hover:text-gray-700">
                        Forgot password?
                    </router-link>
                </div>

                <!-- Submit Button -->
                <button :disabled="!email || !password" type="submit"
                    class="w-full bg-customBlack text-customWhite py-2 px-4 rounded-md shadow-sm text-sm font-medium disabled:bg-gray-400">
                    Login
                </button>
            </form>

            <!-- Message -->
            <p v-if="message" class="mt-4 text-sm text-center text-customBlack">
                {{ message }}
            </p>
        </div>
    </div>
</template>

<style>
@import 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css';
</style>
