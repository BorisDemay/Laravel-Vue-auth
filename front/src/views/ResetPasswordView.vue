<script setup lang="ts">
import { ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useUserStore } from '../stores/user';

const password = ref('');
const password_confirmation = ref('');
const message = ref('');
const userStore = useUserStore();

const route = useRoute();
const router = useRouter();

// Extract token and email from the route query parameters
const token = ref(route.query.token as string || '');
const email = ref(route.query.email as string || '');

const resetPassword = async () => {
    message.value = '';
    try {
        if (!token.value || !email.value) {
            message.value = 'Invalid or missing reset token.';
            return;
        }

        const result = await userStore.resetPassword(token.value, email.value, password.value, password_confirmation.value);
        message.value = result;

        // Optionally redirect to login page after success
        router.push('/login');
    } catch (error) {
        message.value = 'Failed to reset password. Please try again.';
    }
};
</script>

<template>
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md">
            <h1 class="text-2xl text-customBlack font-bold mb-6">Reset Password</h1>

            <form @submit.prevent="resetPassword" class="space-y-4 text-customBlack">
                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium">Email</label>
                    <input id="email" v-model="email" type="email" readonly
                        class="mt-1 p-2 block w-full rounded-md shadow-sm border-customBlack border-2 text-sm bg-gray-100" />
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium">New Password</label>
                    <input id="password" v-model="password" type="password" placeholder="Enter your new password"
                        class="mt-1 p-2 block w-full rounded-md shadow-sm border-customBlack border-2 text-sm"
                        required />
                    <small v-if="password.length < 8 && password.length > 0" class="text-red-500 text-xs">
                        Password must be at least 8 characters.
                    </small>
                </div>

                <!-- Password Confirmation Field -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium">Confirm Password</label>
                    <input id="password_confirmation" v-model="password_confirmation" type="password"
                        placeholder="Confirm your password"
                        class="mt-1 p-2 block w-full rounded-md shadow-sm border-customBlack border-2 text-sm"
                        required />
                    <small v-if="password !== password_confirmation && password_confirmation.length > 0"
                        class="text-red-500 text-xs">
                        Passwords do not match.
                    </small>
                </div>

                <!-- Submit Button -->
                <button :disabled="password.length < 8 || password !== password_confirmation" type="submit"
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-md shadow-sm text-sm font-medium disabled:bg-gray-400">
                    Reset Password
                </button>
            </form>

            <!-- Message -->
            <p v-if="message" class="mt-4 text-sm text-center text-gray-600">
                {{ message }}
            </p>
        </div>
    </div>
</template>
