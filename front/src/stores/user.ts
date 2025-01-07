import { defineStore } from 'pinia';
import axios from '../axios';
import type { PersistenceOptions } from 'pinia-plugin-persistedstate';

export const useUserStore = defineStore('user', {
  state: () => ({
    user: null as null | { id: number; name: string; email: string },
    token: null as null | string,
    error: null as null | string,
    loading: false,
  }),

  actions: {
    async login(email: string, password: string, router: any) {
        this.loading = true;
        try {
          const response = await axios.post('/api/login', { email, password });
          this.token = response.data.token;
          if (this.token) {
            localStorage.setItem('token', this.token);
            router.push('/dashboard');
          }
          await this.fetchUser(router);
        } finally {
          this.loading = false;
        }        
    },

    async fetchUser(router: any) {
      const token = this.token || localStorage.getItem('token');
      if (!token) {
        console.warn('No token found. User not authenticated.');
        return;
      }

      try {
        const response = await axios.get('/api/user', {
          headers: { Authorization: `Bearer ${token}` },
        });
        this.user = response.data;
      } catch (error: any) {
        if (error.response?.status === 401) {
          this.logout(router);
        }
        console.error('Fetching user failed:', error.response?.data || error.message);
      }
    },

    async logout(router: any) {
      try {
        await axios.post('/api/logout', {}, {
          headers: { Authorization: `Bearer ${this.token}` },
        });
      } catch (error: any) {
        console.error('Logout API failed:', error.response?.data || error.message);
      } finally {
        this.user = null;
        this.token = null;
        localStorage.removeItem('token');
        router.push('/login');
      }
    },

    // api function : 
    // public function forgotPassword(Request $request)
    // {
    //     $request->validate(['email' => 'required|email']);

    //     $status = Password::sendResetLink(
    //         $request->only('email')
    //     );

    //     return $status === Password::RESET_LINK_SENT
    //         ? response()->json(['message' => 'Reset link sent to your email.', 'status' => true])
    //         : response()->json(['message' => 'Unable to send reset link', 'status' => false], 500);
    // }
    async forgotPassword(email: string) {
      try {
          const response = await axios.post('/api/forgot-password', { email });
          return response.data.message; // Return the API message
      } catch (error: any) {
          console.error('Forgot password failed:', error.response?.data || error.message);
          return error.response?.data?.message || 'An error occurred.';
      }
  }
  
  },

  persist: {
    key: 'userStore', // Name of the key to use in localStorage
    storage: localStorage, // Use localStorage as the storage
    paths: ['user', 'token'], // The state properties to persist
  } as PersistenceOptions,
});
