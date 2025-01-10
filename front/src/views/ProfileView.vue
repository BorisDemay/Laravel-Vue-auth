<script>
import { useUserStore } from '../stores/user';
import { computed } from 'vue';

export default {
    setup() {
        const userStore = useUserStore();

        return {
            user: computed(() => userStore.user)
        };
    },
};
</script>

<template>
    <div>
        <h1>Profile</h1>
        <p>{{ user.name }}</p>
        <p>{{ user.email }}</p>
        <router-link to="/forgot-password" class="text-sm text-customWhite">
            Forgot password?
        </router-link>
    </div>
</template>