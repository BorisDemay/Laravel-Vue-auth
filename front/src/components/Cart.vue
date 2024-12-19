<template>
    <div class="max-w-7xl mx-auto p-6">
        <h3 class="text-2xl font-bold text-customWhite mb-6">Cart</h3>

        <div v-if="cartProducts.length === 0" class="text-customWhite">
            Your cart is empty.
        </div>

        <!-- Product card -->
        <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white shadow-md rounded-lg p-6 flex flex-col justify-between" v-for="product in cartProducts"
                :key="product.id">
                <div class="mb-4">
                    <h4 class="text-lg font-semibold text-gray-900">{{ product.name }}</h4>
                </div>
                <div class="flex-1">
                    <p class="text-gray-700 text-sm mb-4">
                        {{ product.description || 'No description available.' }}
                    </p>
                </div>
                <div>
                    <p class="text-gray-900 font-medium">
                        <strong>Price:</strong> ${{ product.price.toFixed(2) }}
                    </p>
                    <p class="text-gray-900 font-medium">
                        <strong>Quantity:</strong> {{ product.quantity }}
                    </p>
                </div>
                <button class="mt-4 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                    @click="removeFromCart(product.id)">
                    Remove
                </button>
            </div>
        </div>

        <!-- Total Price -->
        <div v-if="cartProducts.length > 0" class="mt-6 text-right">
            <h4 class="text-xl font-bold text-gray-900">
                Total: ${{ totalPrice.toFixed(2) }}
            </h4>
            <button class="mt-4 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                @click="checkout">
                Checkout
            </button>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { useCartStore } from '@/stores/cart';
import { loadStripe } from '@stripe/stripe-js';
import axios from '@/axios';

// Access the Cart Store
const cartStore = useCartStore();

// Get the list of products in the cart
const cartProducts = computed(() => cartStore.products);

// Get the total price of the cart
const totalPrice = computed(() => cartStore.totalPrice);

const removeFromCart = (productId) => {
    cartStore.removeFromCart(productId);
};

const checkout = async () => {
    try {
        const stripe = await loadStripe(import.meta.env.VITE_STRIPE_PUBLISHABLE_KEY);

        const response = await axios.post('/api/create-checkout-session', {
            products: cartProducts.value,
        });

        await stripe.redirectToCheckout({ sessionId: response.data.id });
    } catch (error) {
        console.error('Checkout failed:', error);
    }
};
</script>
