<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from '../axios';

const order = ref(null);
const errorMessage = ref('');
const route = useRoute();

onMounted(async () => {
    const { uuid } = route.params;

    try {
        const response = await axios.get(`/api/orders/${uuid}`);
        order.value = response.data;
    } catch (error) {
        console.error('Failed to fetch order details:', error);
        errorMessage.value = 'Failed to load order details. Please try again.';
    }
});
</script>


<template>
    <div class="order">
        <h1 class="text-xl font-bold">Order Details</h1>

        <div v-if="errorMessage" class="text-red-500 mt-4">
            {{ errorMessage }}
        </div>

        <!-- Order Details -->
        <div v-else-if="order" class="mt-6 p-4 border rounded shadow">
            <p><strong>Order Date:</strong> {{ order.created_at || 'N/A' }}</p>
            <p><strong>Total:</strong> ${{ order.total_price ? order.total_price : 'N/A' }}</p>
            <h3 class="mt-4 text-md font-semibold">Products:</h3>
            <ul class="list-disc ml-6">
                <li v-for="product in order.products" :key="product.id">
                    {{ product.name }} - ${{ product.price }} (Qty: {{ product.pivot?.quantity || 1 }})
                </li>
            </ul>
        </div>

        <div v-else>
            <p>Loading order details...</p>
        </div>
    </div>
</template>
