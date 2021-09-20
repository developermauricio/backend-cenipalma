import Vue from 'vue';
import NProgress from 'vue-nprogress';

Vue.use(NProgress)

Vue.prototype.$axios = require('axios');
Vue.prototype.$axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

Vue.component('certificado-component', require('./components/CertificadoComponent.vue').default);

const nprogress = new NProgress()
const app = new Vue({
    nprogress,
    el: '#app',
});
