<template>
    <div class="checkout">
        <h1 class="text-2xl font-bold">Checkout</h1>
        <button @click="handleCheckout" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Proceed to Checkout
        </button>
    </div>
</template>

<script setup>
import axios from '@/axios';
import { useCartStore } from '@/stores/cart';
import { computed } from 'vue';

const cartStore = useCartStore();
const cartProducts = computed(() => cartStore.products);

// Publishable Key
const stripePublishableKey = import.meta.env.VITE_STRIPE_PUBLISHABLE_KEY;

// Checkout Handler
const handleCheckout = async () => {
    try {
        const stripe = await loadStripe(import.meta.env.VITE_STRIPE_PUBLISHABLE_KEY);

        // Prepare cart products for backend
        const checkoutItems = cartProducts.value.map((product) => ({
            id: product.id,
            name: product.name,
            price: product.price,
            quantity: product.quantity,
        }));

        // Create a checkout session
        const response = await axios.post('/api/create-checkout-session', {
            items: checkoutItems,
        });

        // Redirect to Stripe Checkout
        await stripe.redirectToCheckout({ sessionId: response.data.id });
    } catch (error) {
        console.error('Error during checkout:', error);
    }
};

// Load Stripe SDK
import { loadStripe } from '@stripe/stripe-js';
</script>