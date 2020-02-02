<template>
    <modal :name="name"  classes="bg-white h-auto rounded shadow-lg flex flex-col" 
    height="80%" width="90%" :maxWidth="600" :adaptive="true" >

      <div class="flex items-center flex-col   py-6 pb-0bg-white ">
         <div class="font-bold title l2 leading-none text-blue-primary">{{demand.candidatures.length}}</div>
         <div class="uppercase text-gray-800">Candidatures</div>
         <div class=" mt-6  px-4 md:px-8 w-full overflow-y-auto max-h-full">
              <CandidatureCard v-for="candidature in demand.candidatures" v-bind:key="candidature.id" 
               class="cardCandidature cardCandidature_modal" :candidature="candidature" :auth="auth">
               </CandidatureCard>
         </div>
      </div>
       <div class="text-gray-500 flex w-full items-center mt-auto">
         <a href="#"
         @click.prevent="$modal.hide('all-candidatures')" 
         class="w-full text-center bg-gray-200 text-red-600 hover:bg-gray-400 py-4">Retour</a>
      </div>

   </modal>
   
</template>

<script>
import CandidatureCard from './CandidatureCard.vue'

export default {
   props: ['name', 'demand', 'auth'],
   components: {CandidatureCard},
   data() {
      return {
         isModalVisible: false,
         windowWidth : '',
         modalMaxWidth: 800,
         modal : ""
      }
   },
   created() {
       window.addEventListener('resize', () => {
      //   this.windwWidth = window.innerWidth
      });
   }, 
   watch: {
    
   },
   computed: {   
      
   },
   methods: {
      showModal() {
        this.isModalVisible = true;
      },
      closeModal() {
        this.isModalVisible = false;
      },
    
   
   }
}
</script>


<style scoped>

.cardCandidature{
   @apply flex border-b py-8 border-gray-200  w-full
}
.cardCandidature:last-child{
   @apply border-b-0      
}

.cardCandidature.cardCandidature_modal{
   @apply flex  py-4  w-full
}


</style>