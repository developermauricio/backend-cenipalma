import Vue from 'vue';

Vue.prototype.$axios = require('axios');
Vue.prototype.$axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

Vue.component('certificado-component', require('./components/CertificadoComponent.vue').default);

const app = new Vue({
    el: '#app',
});
