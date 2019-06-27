<template>
   <div class=" bg-white rounded overflow-hidden shadow-lg">
      <div class="px-6 py-4">
         <div  class="flex items-center mb-3">
            <div class="flex items-center flex-col mr-4 flex-shrink-0 ">
                <img class="w-10 h-10 rounded-full border-4  border-gray-400 " :src="demand.owner.avatar" alt="Avatar of Jonathan Reinink">
                <div class="text-xs mt-1 text-gray-600">{{ demand.owner.username }}</div>
            </div>
            <div class="font-bold text mb-2 leading-tight text-gray-800 hover:text-indigo-800">
               <a :href="demand_path">
                  {{demand.title}}
               </a>
            </div>
         </div>
         <p class="text-gray-700 text-sm">
            {{ demand.description }}        
         </p>
      </div>
      <div class="px-6 py-4 flex">
          <div>
            <span class="inline-block bg-gray-200 rounded-full px-2 py-1 text-xs font-light text-gray-700 mr-1">{{validFor}} jours restants </span>
            <span class="inline-block bg-gray-200 rounded-full px-2 py-1 text-xs font-light text-gray-700 mr-1">{{ candidaturesCount }} candidatures</span>
         </div>
         <div v-if="hasApply" class='ml-auto'>
               <button href="#"
                  class="focus:outline-none outline-none cursor-not-allowed inline-block bg-gray-600  rounded-full px-2 py-1 text-xs font-light text-white mr-1">
                  Déja postulé
               </button>
         </div>
         <div v-else class='ml-auto'>
               <a href="#"
                  @click.prevent="openModal"
                  class="inline-block bg-orange-500 hover:bg-orange-600 rounded-full px-2 py-1 text-xs font-light text-white mr-1">
                  Postuler
               </a>
         </div>
      </div>
      <create-candidature-modal
         v-on:incrementCandidature="addCandidature"
         :name="'create-candidature-'+demand.id"
         :demand="demand"
         :action="action_candidature"
      ></create-candidature-modal>
   </div>
</template>

<script>
import moment from 'moment'
export default {
   props : ['auth_user', 'user_has_apply', 'demand', 'action_candidature', 'demand_path'],
   data() {
      return {
         candidaturesCount : 0,
         hasApply : false
      }
   },
   mounted() {
      this.candidaturesCount = this.demand.candidatures.length
      this.hasApply = this.user_has_apply
   },
   computed: {
      validFor: function() {
         
         moment.locale('fr');
         let validFor = moment(this.demand.valid_until);
         return validFor.diff(moment(), 'days');
      }
   },
   methods: {
      openModal(){
         this.$modal.show('create-candidature-'+this.demand.id)
      },
      addCandidature(value){
         this.hasApply = true
         this.candidaturesCount = this.candidaturesCount+value
      }
   }
}
</script>