# **Vue.js Frontend for Laravel Authentication Boilerplate**

This repository contains the **Vue.js 3 frontend** for a fully functional authentication system that integrates seamlessly with a **Laravel backend**. It is designed to provide a modern, scalable, and developer-friendly Single Page Application (SPA) setup.

---

## **Tech Stack**

- **Vue.js 3** with TypeScript for a modern and robust frontend experience.
- **Pinia** for state management, ensuring a reactive and centralized store.
- **Vue Router** for client-side navigation and route guards.
- **Axios** for efficient and flexible HTTP requests.
- **Vite** for blazing-fast development and build process.

---

## **Features**

- **Authentication**:

  - Login, Register, and Logout functionality.
  - Persistent authentication state using `pinia-plugin-persistedstate`.

- **State Management**:

  - User and token management with Pinia.
  - LocalStorage-based persistence for seamless state restoration.

- **Navigation**:

  - Route guards for authenticated and unauthenticated routes.
  - Redirects to prevent logged-in users from accessing login/register pages.

- **Developer Friendly**:
  - Modular and reusable components.
  - Centralized API configuration with Axios.
  - Fully responsive design for all devices.
