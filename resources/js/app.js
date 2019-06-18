require('./bootstrap');

window.Vue = require('vue');

Vue.component('subscribeform', require('./components/subscribeForm.vue').default);

const app = new Vue({
    el: '#app',
});
