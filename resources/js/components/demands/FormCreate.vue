<template >
  <div
    class="order-2 md:order-1 bg-blue-primary min-h-screen md:h-screen w-full flex-col md:w-3/5 flex md:max-h-screen py-24 pb-12  relative">
   
    <transition name="slide-top-bottom" mode="out-in">
        <div v-if="isLoading" key="loader" class="flex w-full h-full items-center text-center text-white ">
              <div class='w-full'>
                <div  class="sk-folding-cube">
                    <div class="sk-cube1 sk-cube"></div>
                    <div class="sk-cube2 sk-cube"></div>
                    <div class="sk-cube4 sk-cube"></div>
                    <div class="sk-cube3 sk-cube"></div>
                </div>
                <div class="title text-center w-full text-2xl mt-10">Envoi en cours...</div>
              </div>
        </div>
        <div v-else-if="hasError" key="error" class="flex w-full h-full items-center text-center text-white ">
              <div class='w-full'>
                  <div class="title text-center w-full text-2xl mt-10">{{ messageError}}</div>
              </div>
        </div>

        <div v-else class=" w-full h-full"  key="form">
          <form action="#" method="POST" enctype="multipart/form-data" v-on:submit.prevent v-on:keyup.enter.prevent="nextStep()" 
              class="h-full px-6 md:px-12 pb-16 overflow-x-hidden">
              <transition v-bind:name="animationName" mode="out-in">
                <div v-if="currentStep === 1" key="step1" class="h-full">
                    <Step1 :data="getStep(1)" />
                </div>
                <div v-else-if="currentStep === 2" key="step2" class="h-full">
                    <Step2 :data="getStep(2)" />
                </div>
                <div v-else-if="currentStep === 3" key="step3" class="h-full">
                    <Step3 :data="getStep(3)" />
                </div>
                <div v-else-if="currentStep === 4" key="step4" class="h-full">
                    <Step4 :data="getStep(4)" />
                </div>
                <div v-else-if="currentStep === 5" key="step5" class="h-full">
                    <Step5 :data="getStep(5)" />
                </div>
              </transition>
          </form>
        </div> 
    </transition>

    <div  class="w-full md:absolute md:py-10 px-6 md:px-16 mt-auto md:mt-0 md:bottom-0">
          <transition name="slide-top-bottom">
        <div v-show="!isLoading && !hasError" class="flex">
          <button
          class="text-white uppercase rounded text-sm tracking-wider font-bold px-2 py-3"
          @click="previousStep()"
          v-bind:class="{'opacity-25': isFirstStep() || isLoading}"
          :disabled="isFirstStep() || isLoading"
          >Précedent</button> 
          <button
          class="text-blue-primary rounded text-sm tracking-wider font-bold bg-white px-3 py-1 uppercase ml-auto"
          @click="nextStep()"
          v-bind:class="{'opacity-25': !currentStepValidated() || isLoading}"
          :disabled="!currentStepValidated() || isLoading"
          >{{textNextButton}}</button>
        </div>
          </transition>
    </div>  
       
    
  </div>
</template>


<script>
import Step1 from "./steps/Step1.vue";
import Step2 from "./steps/Step2.vue";
import Step3 from "./steps/Step3.vue";
import Step4 from "./steps/Step4.vue";
import Step5 from "./steps/Step5.vue";

import Steps from "./steps/steps.js";
import fecha from "fecha";

export default {
  components: { Step1, Step2, Step3, Step4, Step5 },
  props: ["currentStep", "totalStep", "animationName"],

  data() {
    return {
      isLoading: false,
      steps: Steps,
      hasError: false,
      messageError: "Une erreur est survenue...Merci de réessayer ultérieurement",
    };
  },
  computed: {
    textNextButton: function() {
      if (this.isLastStep()) {
        return "Publier la demande";
      }
      return "Suivant";
    }
  },

  methods: {
    nextStep: function() {
      if (this.getStep().validated) {
        if (this.isLastStep() && this.allStepValidated()) {
          this.sendForm();
        } else {
          this.$emit("update-animation-name", "slide-next-fade");
          this.$emit("update-current-step", this.currentStep + 1);
        }
      }
    },

    previousStep: function() {
      this.$emit("update-animation-name", "slide-prev-fade");
      this.$emit("update-current-step", this.currentStep - 1);
    },

    isLastStep: function() {
      if (this.currentStep === this.totalStep) {
        return true;
      }
      return false;
    },

    isFirstStep: function() {
      if (this.currentStep === 1) {
        return true;
      }
      return false;
    },


    currentStepValidated: function() {
      return this.getStep().validated;
    },

    

    getStep: function(step) {
      if (step === undefined) {
        step = this.currentStep;
      }
      return this.steps.find(item => item.id === step);
    },

    getField: function(fieldName, step) {
      if (step === undefined) {
        step = this.currentStep;
      }
      return this.steps
        .find(item => item.id === step)
        .fields.find(field => field.name === fieldName);
    },

    errorMessage(fieldName) {
      return this.getField(fieldName).errorMessage;
    },

    isValidated: function(data) {
      let statut = true;
      data.fields.forEach(function(field) {
        if (field.validated !== true) {
          statut = false;
        }
      });
      this.getStep(data.id).validated = statut;
    },

    allStepValidated: function() {
      let validate = true;
      this.steps.forEach(function(step) {
        if (step.validated == false) {
          validate = false;
        }
      });
      return validate;
    },

    sendForm: function() {
      //1 show a loader and disabled button
      this.isLoading = true;
      let vm = this
      console.log( fecha.format(this.getField('be_done_at', 3).value, "YYYY-MM-DD hh:mm A"))

      var formData = new FormData();

      formData.append('title', this.getField('title', 1).value);
      formData.append('description', this.getField('description', 2).value);
      formData.append('content', this.getField('content', 2).value);
      formData.append('category_id', this.getField('category_id', 2).data.id);
      formData.append('sector_id', this.getField('commune_id', 3).data.sector_id);
      formData.append('commune_id', this.getField('commune_id', 3).data.id);
      formData.append('district_id', this.getField('district_id', 3).data.id);
      formData.append('address_1', this.getField('address_1', 3).value);
      formData.append('address_2', this.getField('address_1', 3).value);
      formData.append('postal', this.getField('postal', 3).value);
      formData.append('be_done_at', fecha.format(this.getField('be_done_at', 3).value, "YYYY-MM-DD hh:mm A"));
      this.getStep(4).fields.forEach(function(field){
        formData.append('images[]', field.uploadedFile, field.name);
      })


         axios.post('/demands', formData, {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
        })
         .then(function(response) {
            if(response.data.statut === "success"){
              setTimeout(function(){ 
                  window.location = 'https://dev.margoze.app/demands/'+response.data.demand.id
              }, 1000);
            }else{
              vm.hasError = true
              vm.isLoading = false
            }
         })
         .catch(function(error) {
               vm.hasError = true
               vm.messageError = "Une erreur interne est survenu, merci de réesayer ulteriement"
               vm.isLoading = false
         })
    }
  }
};
</script>



<style scoped >

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