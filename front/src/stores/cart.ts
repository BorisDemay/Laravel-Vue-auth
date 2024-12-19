import { defineStore, type StateTree } from 'pinia';
import type { PersistenceOptions } from 'pinia-plugin-persistedstate';

interface Product {
    id: number;
    name: string;
    description: string;
    price: number;
    quantity: number;
}

interface CartState extends StateTree {
    products: Product[];
}

export const useCartStore = defineStore<'cart', CartState>('cart', {
    // State: Holds the list of products in the cart
    state: (): CartState => ({
        products: [],
    }),

    actions: {
        async addToCart(product: Product) {
            const existingProduct = this.products.find((p) => p.id === product.id);
            if (existingProduct) {
                existingProduct.quantity += product.quantity;
            } else {
                this.products.push({ ...product });
            }
        },

        async removeFromCart(productId: number) {
            this.products = this.products.filter((product) => product.id !== productId);
        },

        async clearCart() {
            this.products = [];
        },

        async updateProductQuantity(productId: number, quantity: number) {
            const product = this.products.find((p) => p.id === productId);
            if (product && quantity > 0) {
                product.quantity = quantity;
            } else if (product) {
                this.removeFromCart(productId);
            }
        },
    },

    getters: {
        totalQuantity: (state): number => {
            return state.products.reduce((sum, product) => sum + product.quantity, 0);
        },

        totalPrice: (state): number => {
            return state.products.reduce((sum, product) => sum + product.price * product.quantity, 0);
        },

        totalProducts: (state): number => {
            return state.products.length;
        }
    },

    // Plugin: Persist the cart state in localStorage
    persist: {
        enabled: true,
        strategies: [
            {
                key: 'cart',
                storage: localStorage,
                paths: ['products'],
            },
        ],
    } as PersistenceOptions,
});
