<template>
   <div class="z-50 relative">
      <transition name="fromTop">
         <div v-if="loginModalIsOpen" 
            class="modal full min-h-screen min-w-screen  md:flex md:flex-wrap ">
            <div class="bg-blue-primary w-full md:w-3/5 md:flex md:items-center relative">
                  <div class="w-full px-6 py-16 md:px-0 md:w-2/3 max-w-sm mx-auto text-center relative">

                     <transition name='slide-fade'>
                        <div class="md:absolute w-full top-0  mt-5 mb-5 md:mt-0 md:mb-0 w-full  left-0" v-if="errors">
                           <div class=" top-0 w-full  text-red-400">
                              <ul>
                                 <li>{{errorMessage[0]}}</li>
                              </ul>
                           </div>   
                        </div>
                      </transition>

                     <h3 class="title l3 white center">Heureux de vous revoir</h3>
                     
                     <form :action="loginUrl" method='post' v-on:submit.prevent="onSubmit"
                     class="flex flex-col py-5 pt-12 pb-3">
                        <!-- @csrf -->
                        <input type="hidden" :value="csrf" name="csrf_token">
                        <input type="email" 
                           placeholder="Email"
                           v-model='email'
                           class="input inverse mb-6">
                        <input type="password" 
                           placeholder="Mot de passe"
                           v-model='password'
                           class="input inverse mb-6">
                        <div class="mt-6">
                           <div class="h-16 ">
                              <button v-if="!isLoading()" class="btn btn-inverse" 
                                 v-bind:class="{ 'opacity-50': !isValidated()  }"
                                 :disabled="!isValidated()">
                                    {{buttonMesssage}}
                              </button>

                              <div v-if="isLoading()" class="sk-folding-cube">
                                 <div class="sk-cube1 sk-cube"></div>
                                 <div class="sk-cube2 sk-cube"></div>
                                 <div class="sk-cube4 sk-cube"></div>
                                 <div class="sk-cube3 sk-cube"></div>
                              </div>
                           </div>

                        </div>
                     </form>
                     
                  </div>
                  <div class="md:absolute pb-10 md:pb-0 w-full md:mb-6 md:bottom-0  md:left-0 md:right-0 text-center md:text-lg text-gray-500 hover:text-white">
                     <a href="">J'ai oublier mon mot de passe</a>
                  </div>
                  
            </div>
            <div class="bg-blue-darken w-full md:w-2/5 md:flex md:items-center">
               <div class="w-full px-6 py-16 md:px-0 md:w-3/4 mx-auto">
                  <h4 class="title l3 white center">Connecter vous avec vos autres compte</h4>
                  <div class="mx-auto text-center">
                     <div action="" class="flex flex-col w-48 mx-auto py-5 pt-12">
                        <a href="" class="btn btn-white mb-6"><span class="btn-icon icon-facebook"></span>Facebook</a>
                        <a href="" class="btn btn-white mb-6"><span class="btn-icon icon-twitter"></span>Twitter</a>
                        <a href="" class="btn btn-white"><span class="btn-icon icon-google"></span>Google</a>
                     </div>
                  </div>
               </div>
            </div>
            
            <div @click.prevent="closeLoginModal()" class="absolute  top-0 right-0  text-2xl p-3 mr-4 text-gray-500 hover:text-white">
               <a href="">X</a>
            </div>
         </div>
         </transition>
   </div>
</template>


<script>
   export default {
      props: ['login-url', 'csrf'],
      data(){
         return {
            loginModalIsOpen : false,
            canSubmit: false,
            email: '',
            password: '',
            errors: false,
            errorMessage: "",
            loading: false
         }
      },
      computed: {
         buttonMesssage: function() {
            if(this.loading){
               return '...'
            }
            return 'Se connecter'
         }
      },
      methods: {
         openLoginModal: function() {
             this.loginModalIsOpen = !this.loginModalIsOpen
         },
         closeLoginModal: function() {
            this.loginModalIsOpen = false
         },
         isValidated: function() {
            if( !this.email =="" && !this.password == ""){
               this.canSubmit = true
               return true
            }
            this.canSubmit = false
            return false
         },
         isLoading: function() {
            if(this.loading == true){
               return true
            }
            return false
         },
         onSubmit: function() {
            let vm = this
            vm.loading = true
            axios.post(this.loginUrl, {
               email: vm.email,
               password: vm.password
            })
            .then(function (response) {
               if(response.status == 200) {
                  location.reload()
               }
            })
            .catch(function (error) {
               vm.errors = true
               vm.errorMessage = error.response.data.errors.email
               //console.log(error.response.data.errors.email)
               vm.loading = false
               vm.password = ""
            });
         }
      }
   }
</script>



<style scoped>
   /* Enter and leave animations can use different */
/* durations and timing functions.  
            */

.fromTop-enter-active, .fromTop-leave-active {
   transition: opacity .3s, transform .5s;
}
.fromTop-enter, .fromTop-leave-to /* .fade-leave-active below version 2.1.8 */ {
   opacity: 0;
   transform: translateY(100%)
}

.slide-fade-enter-active {
  transition: all .3s ease;
}
.slide-fade-leave-active {
  transition: all .3s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}
.slide-fade-enter, .slide-fade-leave-to{
  margin-top: 10px;
  opacity: 0;
}





.sk-folding-cube {
  margin: 0 auto;
  width: 30px;
  height: 30px;
  position: relative;
  top:15px;
  -webkit-transform: rotateZ(45deg);
          transform: rotateZ(45deg);
}

.sk-folding-cube .sk-cube {
  float: left;
  width: 50%;
  height: 50%;
  position: relative;
  -webkit-transform: scale(1.1);
      -ms-transform: scale(1.1);
          transform: scale(1.1); 
}
.sk-folding-cube .sk-cube:before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: #fff;
  -webkit-animation: sk-foldCubeAngle 2.4s infinite linear both;
          animation: sk-foldCubeAngle 2.4s infinite linear both;
  -webkit-transform-origin: 100% 100%;
      -ms-transform-origin: 100% 100%;
          transform-origin: 100% 100%;
}
.sk-folding-cube .sk-cube2 {
  -webkit-transform: scale(1.1) rotateZ(90deg);
          transform: scale(1.1) rotateZ(90deg);
}
.sk-folding-cube .sk-cube3 {
  -webkit-transform: scale(1.1) rotateZ(180deg);
          transform: scale(1.1) rotateZ(180deg);
}
.sk-folding-cube .sk-cube4 {
  -webkit-transform: scale(1.1) rotateZ(270deg);
          transform: scale(1.1) rotateZ(270deg);
}
.sk-folding-cube .sk-cube2:before {
  -webkit-animation-delay: 0.3s;
          animation-delay: 0.3s;
}
.sk-folding-cube .sk-cube3:before {
  -webkit-animation-delay: 0.6s;
          animation-delay: 0.6s; 
}
.sk-folding-cube .sk-cube4:before {
  -webkit-animation-delay: 0.9s;
          animation-delay: 0.9s;
}
@-webkit-keyframes sk-foldCubeAngle {
  0%, 10% {
    -webkit-transform: perspective(140px) rotateX(-180deg);
            transform: perspective(140px) rotateX(-180deg);
    opacity: 0; 
  } 25%, 75% {
    -webkit-transform: perspective(140px) rotateX(0deg);
            transform: perspective(140px) rotateX(0deg);
    opacity: 1; 
  } 90%, 100% {
    -webkit-transform: perspective(140px) rotateY(180deg);
            transform: perspective(140px) rotateY(180deg);
    opacity: 0; 
  } 
}

@keyframes sk-foldCubeAngle {
  0%, 10% {
    -webkit-transform: perspective(140px) rotateX(-180deg);
            transform: perspective(140px) rotateX(-180deg);
    opacity: 0; 
  } 25%, 75% {
    -webkit-transform: perspective(140px) rotateX(0deg);
            transform: perspective(140px) rotateX(0deg);
    opacity: 1; 
  } 90%, 100% {
    -webkit-transform: perspective(140px) rotateY(180deg);
            transform: perspective(140px) rotateY(180deg);
    opacity: 0; 
  }
}

</style>