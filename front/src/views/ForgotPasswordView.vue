<script setup lang="ts">
import { ref } from 'vue';
import { useUserStore } from '../stores/user';

const email = ref('');
const message = ref('');
const userStore = useUserStore();

const forgotPassword = async () => {
    message.value = ''; // Clear the previous message
    try {
        const result = await userStore.forgotPassword(email.value); // Get the message from the API
        message.value = result; // Display the message
    } catch (error) {
        message.value = 'Failed to send email. Please try again.';
    }
};
</script>

<template>
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-customWhite shadow-lg rounded-lg p-8 w-full max-w-md">
            <h1 class="text-2xl font-bold text-customBlack mb-6">Forgot Password</h1>
            <form @submit.prevent="forgotPassword" class="space-y-4">

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

                <!-- Submit Button -->
                <button :disabled="!email" type="submit"
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
