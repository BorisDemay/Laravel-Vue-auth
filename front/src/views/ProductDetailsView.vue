<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from '../axios';

const product = ref(null);
const errorMessage = ref('');
const route = useRoute();

onMounted(async () => {
    const { id } = route.params;

    try {
        const response = await axios.get(`/api/product/${id}`);
        product.value = response.data;
    } catch (error) {
        console.error('Failed to fetch product details:', error);
        errorMessage.value = 'Failed to load product details. Please try again.';
    }
});
</script>


<template>
    <div class="product">
        <h1 class="text-xl font-bold">product Details</h1>

        <div v-if="errorMessage" class="text-red-500 mt-4">
            {{ errorMessage }}
        </div>

        <!-- product Details -->
        <div v-else-if="product" class="mt-6 p-4">
            <p>{{ product.name }}</p>
            <p>{{ product.description }}</p>
            <p><strong>Price:</strong> {{ product.price }}€</p>

        </div>

        <div v-else>
            <p>Loading product details...</p>
        </div>
    </div>
</template>
