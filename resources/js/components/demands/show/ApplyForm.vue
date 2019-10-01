<template>
<div>
  <transition name="slide-top-bottom" mode="out-in">
   <div v-if="candidature || hasApply()" key="candidature" class="bg-blue-darken p-6 leading-none rounded relative">
      <div class="text-white font-bold text-xl text-center mb-10">
       {{alreadySendMessage}}
      </div>
      <div class="flex">
            <div class="avatar-user flex flex-col  items-center mr-4  flex-shrink-0">
              <img :src="candidature.owner.avatar" alt="" 
              class="rounded-full border-yellow-primary border-2   h-8 w-8 "> 
              <div class="uppercase font-bold text-white tracking-wide text-xs mt-2">Vous</div>
            </div>
            <div>
                <div class="text-gray-300">
                    {{candidature.content}}
                </div>
                <div class="mt-2 text-gray-500 text-sm italic">
                    Posté le <span class="text-blue-400  font-bold " > {{candidatureCreatedAt}}</span>
                </div>
                <div>
                    <a href="" class="btn small btn-white inline-block mt-4">Retirer ma candidature</a>
                </div>
            </div>
            
      </div>
   </div>
   <div v-else key="form" class="relative bg-white mb-10 lg:m-0  rounded p-6">
      <div class="text-blue-primary font-bold text-xl text-center mb-10">Envoyez votre candidature à {{demand.owner.username}}</div>
        <form action="" >
            <div class="mb-10 relative">
              <textarea name="content" 
              :placeholder='placeholder' 
              v-model="content" 
                v-bind:class="{'opacity-25': isLoading}"
              :disabled="isLoading"
              class="rounded  border border-blue-primary p-4 w-full h-32 focus:outline-none " ></textarea>
                <div v-if="!valid" class="absolute text-blue-400 text-sm"> Minimum 200 caractéres</div>
            </div>
          
            <div class="text-center ">
              <button
              v-if="!isLoading" 
              v-bind:class="{'opacity-25': !valid}"
              :disabled="!valid"
              @click.prevent="sendCandidature()"
              class="btn rounded-full">Envoyer ma candidature</button>
            
              <div v-else class="m-4">
                  <div class="sk-folding-cube mb-2">
                    <div class="sk-cube1 sk-cube"></div>
                    <div class="sk-cube2 sk-cube"></div>
                    <div class="sk-cube4 sk-cube"></div>
                    <div class="sk-cube3 sk-cube"></div>
                  </div>
                
              </div>
            
            </div>
        </form>
      </div>
  </transition>
  </div>
</template>

<script>
import moment from 'moment'
   export default {
      props: ['demand'],
      created() {
        moment.locale('fr')
        console.log(this.$parent.$refs.contentDemand.candidatureCount)
      },
      data() {
         return {
            valid : false,
            content: '',
            isLoading: false,
            candidature: '',
            temp: false,
            test: false
         }
      },
      methods: {
          hasApply() {
            this.candidature = this.demand.candidatures.find(candidature => candidature.owner_id === this.$parent.auth.id)
            return this.candidature
          },
          sendCandidature() {
            let vm = this
            this.isLoading = true
            axios.post('/demands/'+this.demand.id+'/apply',
            {
              content: this.content
            })
            .then(function(response){
              console.log(response)
              vm.isLoading= false
              vm.candidature = response.data
              vm.temp = true
              vm.$parent.$refs.contentDemand.candidatureCount++
            })
            .catch(function(error) {
              console.log(error)
            })
          }
      },
      computed: {
        alreadySendMessage() {
          if(this.temp) {
            return "Merci d'avoir soumis votre candidature à "+this.demand.owner.username
          }
          else{  
            return "Vous avez déjà postuler pour cette demande"  
          }    
        },
        candidatureCreatedAt(){
            return moment(this.candidature).format("dddd Do MMMM Y")
        },
        placeholder() {
          return "Bonjour, je suis intéressé par votre demande, je pense pouvoir vous aider..."
        }
      },
      watch: {
         content(newValue){
               if(newValue.trim().length > 10 ){
                    this.valid = true
               }else{
                  this.valid = false
               }
         }
      }
      
   }
</script>


<style scoped>
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