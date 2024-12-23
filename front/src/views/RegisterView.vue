<template>
    <div class="register">
        <h1>Register</h1>
        <form @submit.prevent="register">
            <input v-model="name" type="text" placeholder="Name" required />
            <input v-model="email" type="email" placeholder="Email" required />
            <input v-model="password" type="password" placeholder="Password" required />
            <input v-model="passwordConfirmation" type="password" placeholder="Confirm Password" required />
            <button type="submit">Register</button>
        </form>
        <p v-if="message" class="message">{{ message }}</p>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import axios from '../axios';

const name = ref('');
const email = ref('');
const password = ref('');
const passwordConfirmation = ref('');
const message = ref('');

const register = async () => {
    try {
        const response = await axios.post('/api/register', {
            name: name.value,
            email: email.value,
            password: password.value,
            password_confirmation: passwordConfirmation.value,
        });
        message.value = 'Registration successful!';
    } catch (error) {
        const err = error as any;
        console.error('Error:', err.response?.data || err.message);
        message.value = 'Registration failed. Please check your input.';
    }
};
</script>

<style scoped>
.register {
    max-width: 400px;
    margin: 2rem auto;
    text-align: center;
}

input {
    display: block;
    width: 100%;
    margin: 0.5rem 0;
    padding: 0.5rem;
}

button {
    padding: 0.5rem 1rem;
    cursor: pointer;
}

.message {
    color: green;
    margin-top: 1rem;
}
</style>