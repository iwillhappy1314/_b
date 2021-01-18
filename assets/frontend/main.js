import Vue from 'vue';
import App from './App.vue';
import router from './router';

import axios from 'axios';
import VueAxios from 'vue-axios';
import axiosConfig from './utils/axios-config';

import './index.css';

let axios_instance = axios.create(axiosConfig);

Vue.use(VueAxios, axios_instance);

Vue.config.productionTip = false;

/* eslint-disable no-new */
new Vue({
    el    : '#_b-app',
    router,
    render: h => h(App),
});