import Vue from 'vue';
import ElementUI from 'element-ui';
import App from './App.vue';
import router from './router';
import 'element-ui/lib/theme-chalk/index.css';

Vue.use(ElementUI);

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