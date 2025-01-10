<template>
    <div class="text-center mt-16">
        <h1 class="text-4xl font-bold text-green-500">Payment Successful!</h1>
        <p class="mt-4">Thank you for your purchase!</p>
        <router-link to="/" class="mt-6 inline-block text-blue-500 underline">
            Go back to home
        </router-link>
    </div>
</template>

<script setup>
import { useCartStore } from '@/stores/cart';
import { onMounted } from 'vue';

const cartStore = useCartStore();

onMounted(() => {
    cartStore.clearCart();
});
</script>