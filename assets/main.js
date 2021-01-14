import Vue from 'vue';
import App from './App.vue';
import router from './router';
import './index.css';

let axios_instance = axios.create({
    transformRequest: [
        function(data) {
            return Qs.stringify(data);
        }],
    headers         : {
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-WP-Nonce'  : _bApiSettings.nonce,
    },
});

Vue.use(VueAxios, axios_instance);

Vue.config.productionTip = false;

/* eslint-disable no-new */
new Vue({
    el    : '#_b-app',
    router,
    render: h => h(App),
});