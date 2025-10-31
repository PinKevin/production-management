import { createApp } from 'vue';
import './style.css';
import App from './App.vue';
import router from './router';
import axios from 'axios';
import { clearAuthData, getToken } from './helper/authHelper';

axios.interceptors.request.use(
  (config) => {
    const token = getToken();
    if (!token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  },
);

axios.interceptors.response.use(
  (response) => {
    return response;
  },
  (error) => {
    if (error.response && error.response.status == 401) {
      clearAuthData();
      window.location.href = '/login';
    }

    return Promise.reject(error);
  },
);

const app = createApp(App);
app.use(router);
app.mount('#app');
