<template >

      <div class="order-1 md:order-2 bg-white hidden  w-full md:w-2/5 md:flex md:max-h-screen  py-12 md:py-32  relative items-center">
      <div class="w-full px-6 py-16 md:px-0 md:w-3/4 mx-auto text-gray-600 ">
        <div class="my-4 md:my-8"
            v-bind:class="{ current: isCurrent(1) }">
           <span>1 - </span>Titre de la demande
        </div>
        <div class="my-4 md:my-8"
            v-bind:class="{ current: isCurrent(2) }">
           <span>2 - </span>De quoi s'agit-il ?
        </div>
        <div class="my-4 md:my-8"
            v-bind:class="{ current: isCurrent(3) }">
           <span>3 - </span>Ou et quand ?
        </div>
        <div class="my-4 md:my-8"
            v-bind:class="{ current: isCurrent(4) }">
           <span>4 - </span>Photos 
        </div>
        <!-- <div class="my-4 md:my-8"
            v-bind:class="{ current: isCurrent(2) }">
           <span>2 - </span>Informations supplémentaire 
        </div> -->
       
         
      </div>
       <div class="md:absolute w-full md:bottom-0 text-red-600 ">
         <div class="w-3/4 mx-auto py-12">
            <a href="#">Annuler</a>
         </div>
      </div> 
   </div>
</template>



<script>  

   export default {
    props: ['currentStep'],
      methods: {
         isCurrent: function(step) {
            if(this.currentStep === step) {
               return true
            }
            return false
         },
         goTo: function(step){
            if(step < this.currentStep){
               this.$emit('update-animation-name', 'slide-prev-fade');
            }else{
               this.$emit('update-animation-name', 'slide-next-fade');
            }
            this.$emit('update-current-step', step);
         },
         
      },
   }
</script>

<style>
   .current{
      @apply font-bold text-blue-primary  pl-4 ;
   }
</style>
