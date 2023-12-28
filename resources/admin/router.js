import Vue from 'vue';

import Router from 'vue-router';
import PackageAdmin from '../pages/Package.vue';

Vue.use(Router);

export default new Router({
    routes: [
        {
            path     : '/',
            name     : 'PackageAdmin',
            component: PackageAdmin,
        }
    ],
});