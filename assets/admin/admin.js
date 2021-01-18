import Vue from 'vue';
import AdminApp from './AdminApp.vue';
import ElDataTable from '@femessage/el-data-table';
import ElFormRenderer from '@femessage/el-form-renderer';

import menuFix from './utils/admin-menu-fix.js';

import axios from 'axios';
import VueAxios from 'vue-axios';
import axiosConfig from './utils/axios-config';

import router from './router';
import 'element-ui/lib/theme-chalk/index.css';

import {Button, Dialog, Form, FormItem, Loading, Message, MessageBox, Pagination, Table, TableColumn, Input} from 'element-ui';

Vue.use(Button);
Vue.use(Dialog);
Vue.use(Form);
Vue.use(FormItem);
Vue.use(Input);
Vue.use(Loading.directive);
Vue.use(Pagination);
Vue.use(Table);
Vue.use(TableColumn);
Vue.component('el-form-renderer', ElFormRenderer);
Vue.component('el-data-table', ElDataTable);

// to show confirm before delete
Vue.prototype.$confirm = MessageBox.confirm;

// show tips
Vue.prototype.$message = Message;

Vue.use(VueAxios, axios.create(axiosConfig));

Vue.config.productionTip = false;

Vue.prototype.$axios = axios;

/* eslint-disable no-new */
new Vue({
    el    : '#_b-admin',
    router,
    render: h => h(AdminApp),
});

// fix the admin menu for the slug "wp-transship-admin"
menuFix('_b-admin');