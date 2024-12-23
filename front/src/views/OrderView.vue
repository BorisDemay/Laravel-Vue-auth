<script setup>
import axios from '../axios';
import { ref, onMounted } from 'vue';

const orders = ref([]);

onMounted(async () => {
    try {
        const response = await axios.get('/api/orders');
        orders.value = response.data.orders;
    } catch (error) {
        console.error('Failed to fetch orders:', error);
    }
});
</script>

<template>
    <div class="order">
        <h1>Order</h1>
        <p>Welcome to the order page.</p>

        <div>
            <div v-for="order in orders" :key="order.id">
                <div>
                    <h2>Order #{{ order.id }}</h2>
                    <p>Order Date: {{ order.created_at || 'N/A' }}</p>
                    <p>Items: {{ order.products?.length || 0 }}</p>
                    <p>Total: ${{ order.total_price ? order.total_price : 'N/A' }}</p>

                    <router-link :to="{ name: 'order-details', params: { uuid: order.uuid } }">
                        View Details
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>
