<script setup>
import axios from '@/axios';
import { useCartStore } from '@/stores/cart';
import { computed, ref } from 'vue';

const cartStore = useCartStore();
const cartProducts = computed(() => cartStore.products);

const clientEmail = ref(localStorage.getItem('user_email') || '');

// Add Stripe logic
const handleCheckout = async () => {
    try {
        if (!clientEmail.value) {
            alert('Please enter your email to proceed.');
            return;
        }

        const stripe = await loadStripe(import.meta.env.VITE_STRIPE_PUBLISHABLE_KEY);

        const checkoutItems = cartProducts.value.map((product) => ({
            id: product.id,
            name: product.name,
            price: product.price,
            quantity: product.quantity,
        }));

        const payload = {
            products: checkoutItems,
            email: clientEmail.value,
        };

        console.log('email:', clientEmail.value);

        const response = await axios.post('/api/create-checkout-session-no-email', payload);

        await stripe.redirectToCheckout({ sessionId: response.data.id });
    } catch (error) {
        console.error('Error during checkout:', error);
    }
};

import { loadStripe } from '@stripe/stripe-js';
</script>

<template>
    <div class="checkout">
        <h1 class="text-2xl font-bold">Checkout</h1>

        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email Address</label>
            <input id="email" type="email" v-model="clientEmail"
                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                placeholder="Enter your email" required />
        </div>

        <button @click="handleCheckout" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Proceed to Checkout
        </button>
    </div>
</template>
