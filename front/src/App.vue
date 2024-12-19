<script setup lang="ts">
import { useUserStore } from './stores/user';
import { useCartStore } from './stores/cart';
import { computed } from 'vue';

import { useRouter } from 'vue-router';

const userStore = useUserStore();
const cartStore = useCartStore();

const router = useRouter();

const logout = async () => {
  await userStore.logout(router);
};
const productsCounter = () => {
  return cartStore.totalProducts;
};

</script>

<template>
  <header>
    <div class="mx-auto flex items-center justify-between p-4">
      <div>
        <router-link to="/" class="text-xl font-bold hover:text-gray-300">Home</router-link>
      </div>
      <nav class="flex space-x-4">
        <router-link v-if="!userStore.user" to="/login" class="text-sm font-medium hover:text-gray-300">
          Login
        </router-link>
        <router-link v-if="!userStore.user" to="/register" class="text-sm font-medium hover:text-gray-300">
          Register
        </router-link>
        <router-link to="/cart" class="relative inline-block text-sm font-medium hover:text-gray-300">
          <i class="fas fa-cart-shopping text-lg"></i>
          <!-- Counter -->
          <span v-if="productsCounter() > 0"
            class="absolute top-0 right-0 transform translate-x-1/2 -translate-y-1/2 text-xs font-semibold bg-orange-400 text-white rounded-full w-5 h-5 flex items-center justify-center">
            {{ productsCounter() }}
          </span>
        </router-link>
        <button v-if="userStore.user" @click="logout"
          class="text-sm font-medium hover:text-gray-300 focus:outline-none">
          Logout
        </button>
      </nav>
    </div>
    <div class="p-2 text-center" v-if="userStore.user">
      <p>Hello, {{ userStore.user.name }}</p>
    </div>
  </header>
  <main class="p-6">
    <router-view />
  </main>
</template>


<style>
@import 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css';
</style>
