import './assets/main.css'
import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import { createPinia } from 'pinia';
import piniaPluginPersistedState from 'pinia-plugin-persistedstate';
import axios from './axios';

// axios.get('/sanctum/csrf-cookie').then(() => {
//     console.log('CSRF cookie initialized.');
//   });

const app = createApp(App)
const pinia = createPinia();
pinia.use(piniaPluginPersistedState); // Persist the store state to localStorage

app.use(router)
app.use(pinia);

app.mount('#app')
