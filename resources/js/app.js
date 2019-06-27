require('./bootstrap');
import VModal from 'vue-js-modal'

window.Vue = require('vue');


Vue.use(VModal)


Vue.component('subscribe-form', require('./components/subscribeForm.vue').default);
Vue.component('create-candidature-modal', require('./components/CreateCandidatureModal.vue').default);
Vue.component('notifications-nav', require('./components/notificationsNav.vue').default);
Vue.component('notification', require('./components/notification.vue').default);

const app = new Vue({
    el: '#app'
});
