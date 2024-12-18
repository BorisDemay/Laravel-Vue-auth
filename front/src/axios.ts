import axios from 'axios';
import type { AxiosInstance } from 'axios';

const instance: AxiosInstance = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL, // Vite environment variable
  withCredentials: true,
  headers: { 'Accept': 'application/json' },
});

// Request interceptor to handle CSRF token
instance.interceptors.request.use(async (config) => {
  const csrfToken = localStorage.getItem('XSRF-TOKEN');
  if (csrfToken) {
    config.headers['X-XSRF-TOKEN'] = csrfToken;
  }
  return config;
});

export default instance;
