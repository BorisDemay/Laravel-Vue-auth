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
</script>

<template>
    <div class="login">
        <h1>Login Page</h1>
        <form @submit.prevent="login">
            <input v-model="email" type="email" placeholder="Email" required />
            <small v-if="!email.includes('@') && email.length > 0">Please enter a valid email.</small>
            <input v-model="password" type="password" placeholder="Password" required />
            <button :disabled="!email || !password" type="submit">Login</button>
        </form>
        <p v-if="message" class="message">{{ message }}</p>
    </div>
</template>

<style scoped>
.login {
    max-width: 300px;
    margin: 2rem auto;
    text-align: center;
}

input {
    display: block;
    margin: 1rem auto;
    padding: 0.5rem;
}

button {
    padding: 0.5rem 1rem;
    cursor: pointer;
}
</style>
