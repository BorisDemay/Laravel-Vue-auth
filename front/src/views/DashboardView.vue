<template>
    <div class="dashboard">
        <h1>Dashboard</h1>
        <p>Welcome to the dashboard.</p>
    </div>
    <Product />

</template>

<script>
import Product from '../components/Product.vue';

export default {
    components: {
        Product,
    },
};
</script>