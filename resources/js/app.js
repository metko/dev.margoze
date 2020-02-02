require('./bootstrap');
import VModal from 'vue-js-modal'
import vSelect from 'vue-select'

window.Vue = require('vue');


Vue.use(VModal)



// new
Vue.component('register-form', require('./components/auth/Register.vue').default);
Vue.component('update-modal-avatar', require('./components/updateAvatarModal.vue').default);

Vue.component('create-demand', require('./components/demands/CreateDemand.vue').default);
Vue.component('list-demands', require('./components/demands/ListDemands.vue').default);
Vue.component('show-demand', require('./components/demands/show/ShowDemand.vue').default);

Vue.component('form-select', require('./components/FormSelect.vue').default);

Vue.component('login-modal', require('./components/LoginModal.vue').default);
Vue.component('search-modal', require('./components/SearchModal.vue').default);

Vue.component('search-input-home', require('./components/SearchInputHome.vue').default);


Vue.component('subscribe-form', require('./components/subscription/subscribeForm.vue').default);
Vue.component('subscribe-button', require('./components/subscription/subscribeButton.vue').default);
Vue.component('add-card-form', require('./components/subscription/addCardForm.vue').default);



//old



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
        loginModalOpen : false
      }
    },
    created() {

    },
    mounted(){
      var msgDiv = document.getElementsByClassName("messagebox");
     
      if(msgDiv.length){
        msgDiv[0].scrollTop = msgDiv[0].scrollHeight;
      }

      // FILTER district select on edit dashboard profile 
      let selectCommune = document.getElementById('dashboard-edit-profile-commune')
      let selectDistrict = document.getElementById('dashboard-edit-profile-district')
      
      if(selectCommune) {
        let selectedCommune = selectCommune.options[selectCommune.selectedIndex]

        let filterDistrict = function() {
          for (let district of selectDistrict.options) {
            district.disabled = false
            if(district.getAttribute('data-commune-id') != selectedCommune.value) {
              district.disabled = true
            }
          }  
       }

        filterDistrict()

        selectCommune.addEventListener('change', (event) => {
            selectDistrict.options.selectedIndex = null   
            selectedCommune = selectCommune.options[selectCommune.selectedIndex]
            filterDistrict()
        })
      }
      // END FILTER DISTRICT
      



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
      
      openLoginModal: function() {
        console.log('open login')
        console.log(this.$refs)
        this.$refs.loginModal.openLoginModal()
      },

      openSearchModal: function() {
        console.log('open search')
        console.log(this.$refs)
        console.log(this)
        this.$refs.searchModal.openSearchModal()
      },

      hasOpenMenu: function(){
          if(this.middleMenuIsOpen || this.userMenuIsOpen  ){
            return true
          }
          return false
      },
      closeMenus: function(){
        
          if(this.hasOpenMenu()){
            this.middleMenuIsOpen = false
            this.userMenuIsOpen = false
          }
      },

 
    }
});


 