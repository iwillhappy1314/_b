import Vue from 'vue';
import ElementUI from 'element-ui';
import App from './App.vue';
import router from './router';
import 'element-ui/lib/theme-chalk/index.css';
import {Drag, Drop} from 'vue-drag-drop';

Vue.component('drag', Drag);
Vue.component('drop', Drop);
Vue.use(ElementUI);

Vue.config.productionTip = false;

/* eslint-disable no-new */
new Vue({
  el    : '#_b-app',
  router,
  render: h => h(App),
});