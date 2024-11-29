// Import Bootstrap and other required styles
import './bootstrap';

import { createApp } from 'vue';
import axios from 'axios';
import App from './components/app.vue';
import router from './router';

import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap-icons/font/bootstrap-icons.css';
import '@fortawesome/fontawesome-free/css/all.min.css';


const app = createApp(App);

// Make axios globally accessible
app.config.globalProperties.$axios = axios;

// Use the router and mount the app
app.use(router).mount('#app');
