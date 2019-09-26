require('./bootstrap');
import VModal from 'vue-js-modal'
//import VeeValidate from 'vee-validate';
//import FranceLocal from 'vee-validate/dist/locale/fr';



window.Vue = require('vue');

// Vue.use(VeeValidate, {
//     classes: true,
//     classNames: {
//       valid: 'is-valid',
//       invalid: 'is-invalid'
//     }
//   });
Vue.use(VModal)
// new
Vue.component('create-demand', require('./components/demands/CreateDemand.vue').default);
Vue.component('list-demands', require('./components/demands/ListDemands.vue').default);
Vue.component('form-select', require('./components/FormSelect.vue').default);
Vue.component('login-modal', require('./components/LoginModal.vue').default);

//old
Vue.component('subscribe-form', require('./components/subscribeForm.vue').default);



Vue.component('create-candidature-modal', require('./components/CreateCandidatureModal.vue').default);
Vue.component('demand-card-full', require('./components/demands/DemandCardFull.vue').default);
Vue.component('demand-show', require('./components/demands/DemandShow.vue').default);




Vue.component('candidature-card', require('./components/candidatures/CandidatureCard.vue').default);

Vue.component('propose-settings', require('./components/contracts/ProposeSettings.vue').default);


Vue.component('notifications-nav', require('./components/notificationsNav.vue').default);
Vue.component('notification', require('./components/notification.vue').default);

Vue.component('star-rating', require('./components/starRating.vue').default);


const app = new Vue({
    el: '#app',
    data(){
      return {
        middleMenuIsOpen : false,
        userMenuIsOpen: false,
        messagesTabIsOpen: false,
        searchInputIsExpanded: false,
      }
    },
    created() {
      // this.$validator.localize('fr', {
      //   messages: FranceLocal.messages,
      // });
      // this.$validator.localize('fr');
    },
    mounted(){
      var msgDiv = document.getElementsByClassName("messagebox");
     
      if(msgDiv.length){
        msgDiv[0].scrollTop = msgDiv[0].scrollHeight;
      }
    },

    methods: {
      openMiddleMenu: function(){
        if(this.hasOpenMenu()){
          this.closeMenus()
        }
        this.middleMenuIsOpen = !this.middleMenuIsOpen
      },
      openUserMenu: function(){
        if(this.hasOpenMenu()){
          this.closeMenus()
        }
        this.userMenuIsOpen = !this.userMenuIsOpen
      },
      openTabMessages: function(){
        if(this.hasOpenMenu()){
          this.closeMenus()
        }
        this.messagesTabIsOpen = !this.messagesTabIsOpen
      },
      openLoginModal: function() {
        this.$refs.loginModal.openLoginModal()
      },
      hasOpenMenu: function(){
          if(this.middleMenuIsOpen || this.userMenuIsOpen || this.messagesTabIsOpen  ){
            return true
          }
          return false
      },
      closeMenus: function(){
          if(this.hasOpenMenu()){
            this.middleMenuIsOpen = false
            this.userMenuIsOpen = false
            this.messagesTabIsOpen = false
          }
      },
      expandSearchInput: function(){
        this.searchInputIsExpanded = !this.searchInputIsExpanded
      },

      updateDataSelectDistrict: function(item) {
        this.$refs.districtSelectComponent.updateDataList(item)
      }
 
    }
});
