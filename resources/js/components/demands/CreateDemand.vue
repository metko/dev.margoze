<template>
   <div class="flex flex-col md:flex md:flex-wrap md:flex-row  md:h-screen ">
         <FormCreate
            :currentStep="currentStep"
            :totalStep="totalStep"
            :animationName='animationName'
            v-on:update-current-step="UpdateCurrentStep"
            v-on:update-animation-name="UpdateAnimationName"/>
         <FormNav
            :currentStep="currentStep"
            v-on:update-current-step="UpdateCurrentStep"
            v-on:update-animation-name="UpdateAnimationName"/>
    
   </div>
</template>

<script>
import FormCreate from './FormCreate.vue'
import FormNav from './FormNav.vue'

export default {
      components: {FormCreate, FormNav},

      data(){
            return {
                  currentStep: 1,
                  totalStep: 5,
                  animationName: ''
            }
      },

      methods: {
            UpdateCurrentStep: function (step) {
                  this.currentStep = step
            },
            UpdateAnimationName: function (name) {
                  this.animationName = name
            },
      }
}
</script>

<style>
      

.slide-next-fade-enter-active, .slide-next-fade-leave-active,
.slide-prev-fade-enter-active, .slide-prev-fade-leave-active  {
  transition: all .3s ease;
}

.slide-next-fade-leave-to, .slide-next-fade-enter,
.slide-prev-fade-leave-to, .slide-prev-fade-enter{
  opacity: 0;
}

.slide-next-fade-leave-to{
  transform: translateX(-10px);
}
.slide-next-fade-enter{
  transform: translateX(10px);
}

.slide-prev-fade-leave-to{
  transform: translateX(10px);
}
.slide-prev-fade-enter{
  transform: translateX(-10px);
}
</style>