<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useCartStore } from '../stores/cart';
import axios from '../axios';

interface Product {
    id: number;
    name: string;
    description: string;
    price: number;
}

// Cart Store
const cartStore = useCartStore();

// State for products
const products = ref<Product[]>([]);

// Fetch products from the API
onMounted(async () => {
    try {
        const response = await axios.get('/api/products');
        products.value = response.data.data;
    } catch (error) {
        console.error('Failed to fetch products:', error);
    }
});

const addToCart = (product: Product) => {
    const cartProduct = { ...product, quantity: 1 };
    cartStore.addToCart(cartProduct);
};
</script>

<template>
    <div class="max-w-7xl mx-auto p-6">
        <h3 class="text-2xl font-bold text-white mb-6">Products</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white shadow-md rounded-lg p-6 flex flex-col justify-between" v-for="product in products"
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
                </div>
                <button class="mt-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                    @click="addToCart(product)">
                    Add to Cart
                </button>
            </div>
        </div>
    </div>
</template>
