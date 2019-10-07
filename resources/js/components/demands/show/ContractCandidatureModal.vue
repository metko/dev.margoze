<template>
    <modal :name="name"  style="z-index:1000"  
         :maxWidth="800" width="90%" :adaptive="true" height="auto" @before-open="beforeOpen">
         
       <div id="modal" v-bind:class="{'flex flex-col justify-between': height}" 
       class="" 
       :style="'height : '+ heightModal">

         <transition name="slide-top-bottom" mode="out-in">
   
            <div v-if="!isLoading" key="candidature" class="flex justify-center items-center flex-col mt-6 ">
               <div class="title l4 text-blue-primary text-center px-4">Etes vous sur de vouloir conracté la candidature de {{ username }} ?</div>
               <div class="flex mt-6 py-6 px-8 ">
                  <a href="#" class="avatar-user flex flex-col  items-center mr-4  flex-shrink-0">
                     <img :src="avatar" alt="" 
                        class="rounded-full border-yellow-primary border-2 h-12 w-12 "> 
                     <div class="uppercase font-bold text-gray-500 tracking-wide text-xs mt-2">{{username}}</div>
                  </a>
                  <div>
                     <div class="text-gray-800 leading-snug">
                        {{content}}
                     </div>
                     <div class="mt-2 text-gray-500 text-sm italic">
                        Posté le <span class="text-blue-400  font-bold" > {{ created }}</span>
                     </div>
                  </div>
               </div>
            </div>

            <div v-else key="loader" class="mt-4 mb-12 flex h-full items-center">
               <div class="sk-folding-cube mb-2">
                  <div class="sk-cube1 sk-cube"></div>
                  <div class="sk-cube2 sk-cube"></div>
                  <div class="sk-cube4 sk-cube"></div>
                  <div class="sk-cube3 sk-cube"></div>
               </div>
            </div>

         </transition>

         <div v-if="auth.credits.contracts_count" class="mt-6 text-gray-500 flex w-full items-center ">
            <a href="#" 
            :disabled="isLoading"
            v-bind:class="{'opacity-25': isLoading}"
            @click.prevent="$modal.hide('contract-candidature')"
            class="w-1/2 text-center text-red-600 bg-gray-100 hover:bg-gray-200 py-4 ">Annuler</a>
            <a href="#" 
            :disabled="isLoading"   
            v-bind:class="{'opacity-25': isLoading}"   
            @click.prevent="contractCandidature"
            class="w-1/2 text-center bg-blue-primary text-white hover:bg-blue-darken py-4">Oui, je contracte ma demande</a>
         </div>
         <div v-else class="mt-6 flex w-full items-center flex-col bg-gray-100 py-4">
               <div class="text-red-600 title l5">Vous n'avez pas assez de credits pour contracter d'avantage de candidatures.</div>
               <a href="/plan" class="btn inline-block my-3 ">Changer d'abonnement</a>
         </div>
      </div>
   </modal>
</template>

<script>
import moment from 'moment'
export default {
   props: ['name', 'demand', 'auth'],
   data() {
      return {
         candidature : '',
         isLoading : false,
         height : "",
      }
   },
   created() {
       moment.locale('fr')
   }, 
   computed: {
      heightModal(){
         if(this.height){
            return this.height
         }
         return "auto"
      },
      avatar(){
         if(this.candidature.owner){
            return this.candidature.owner.avatar
         }
      },
      username(){
         if(this.candidature.owner){
            return this.candidature.owner.username
         }
      },
      content(){
         return this.candidature.content
      },
      created(){
         return moment(this.candidature).format("dddd Do MMMM Y")
      },
   },
   methods: {
      contractCandidature() {
       let heightModal = document.getElementById('modal').offsetHeight;
       this.height = heightModal+"px"
       this.isLoading = true
       let vm = this
       axios.post("/demands/"+this.candidature.demand_id+"/contract/"+this.candidature.id,
            {
               demand : vm.demand,
               candidature : vm.canditdature
            })
            .then(function(response) {
                  console.log(response.data)
                  window.location = 'https://dev.margoze.app/dashboard/contracts/'+response.data.contract.id
                 
            })
            .catch(function(error){

            })
      },
      beforeOpen(event) {
         console.log(event.params.candidature);
         this.candidature = event.params.candidature;
      }
   }
}
</script>


<style scoped>
.v--modal-box.v--modal{
   width: 100% !important;
} 
.slide-top-bottom-enter-active {
  transition: all .5s ease;
}
.slide-top-bottom-leave-active {
  transition: all .3s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}
.slide-top-bottom-enter, .slide-top-bottom-leave-to
/* .slide-fade-leave-active below version 2.1.8 */ {
  transform: translateY(20px);
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
  @apply bg-blue-primary;
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