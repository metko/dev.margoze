require('./bootstrap');
import VModal from 'vue-js-modal'
import VeeValidate from 'vee-validate';
import FranceLocal from 'vee-validate/dist/locale/fr';


window.Vue = require('vue');

Vue.use(VeeValidate, {
    classes: true,
    classNames: {
      valid: 'is-valid',
      invalid: 'is-invalid'
    }
  });
Vue.use(VModal)

Vue.component('subscribe-form', require('./components/subscribeForm.vue').default);
Vue.component('create-candidature-modal', require('./components/CreateCandidatureModal.vue').default);
Vue.component('notifications-nav', require('./components/notificationsNav.vue').default);
Vue.component('notification', require('./components/notification.vue').default);

const app = new Vue({
    el: '#app',
    created() {
      this.$validator.localize('fr', {
        messages: FranceLocal.messages,
      });
      this.$validator.localize('fr');
    }
});
