<template>
   <div >
      <div v-if="! demand.contracted" class="flex items-center justify-center flex-col leading-none ">     
         <div class="font-bold title l2 leading-none text-yellow-primary">{{demand.candidatures.length}}</div>
         <div class="uppercase text-white">Candidatures</div>
         <div class="mt-6 bg-blue-darken w-full px-6 rounded">
            <CandidatureCard v-for="candidature in demandCandidature" v-bind:key="candidature.id" 
            class="cardCandidature" :candidature="candidature">
            </CandidatureCard>
            <div v-if="countCandidatures() > 3" class="flex justify-center">
               <a href="#" @click.prevent="openAllCandidatures()" class="btn small btn-white inline-block my-6">Voir toutes les candidatures</a>
            </div>  
         </div>
      </div>
      <div v-else class="flex items-center justify-center flex-col leading-none ">
            <div class="font-bold title l4 leading-none text-white text-center">Cette demande est déja contracté</div>
            <a class="btn btn-white inline-block mt-6" :href="'/dashboard/contracts/'+demand.contract.id">Voir le contrat</a>
      </div>
      
         <ContractCandidatureModal
            name="contract-candidature"
            :demand="demand"
      ></ContractCandidatureModal>

      <AllCandidatureModal
            v-if="countCandidatures() > 3"
            :demand="demand"
            ref="allCandidaturesModal"
             name="all-candidatures"
      ></AllCandidatureModal>

     
   </div>
</template>

<script>

import CandidatureCard from "./CandidatureCard.vue"
import ContractCandidatureModal from "./ContractCandidatureModal.vue"
import AllCandidatureModal from "./AllCandidatureModal.vue"

export default {
   props: ['demand'],
   components: {CandidatureCard, ContractCandidatureModal, AllCandidatureModal},
   data() {
      return {
        
      }
   },
   methods: {
      countCandidatures(){
         return this.demand.candidatures.length
      },
      openAllCandidatures() {
         this.$modal.show('all-candidatures')
      },
      
   },
   computed: {
      demandCandidature(){
         if(this.countCandidatures() > 3){
            return this.demand.candidatures.slice(0,3)
         }
         return this.demand.candidatures
      }
   }
}
</script>


<style scoped>
   .cardCandidature{
      @apply flex border-b py-8 border-blue-primary  w-full
   }
   .cardCandidature:last-child{
      @apply border-b-0      
   }
   
</style>