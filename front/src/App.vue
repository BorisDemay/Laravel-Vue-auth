<script setup lang="ts">
import { useUserStore } from './stores/user';
import { useRouter } from 'vue-router';

const userStore = useUserStore();
const router = useRouter();

const logout = async () => {
  await userStore.logout(router);
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
        <router-link to="/cart" class="text-sm font-medium hover:text-gray-300">
          Cart
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
