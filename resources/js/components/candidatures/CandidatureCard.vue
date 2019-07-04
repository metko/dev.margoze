<template>
   <div class=" bg-white rounded overflow-hidden shadow-lg">
      <div class="px-6 py-4">
         <div  class="flex items-center ">
            <div class="flex items-center flex-col mr-4 flex-shrink-0 ">
                  <img class="w-10 h-10 rounded-full border-4  border-gray-400" :src="demand.owner.avatar" alt="Avatar of Jonathan Reinink">
            </div>
            <div class="text-sm text  leading-tight text-gray-800">
               <a :href="demand.path">
                  <div v-if="isOwnerCandidature">
                     <strong>Vous</strong> avez laissé votre candidature  <strong>{{ candidature.created }}</strong>. 
                  </div>
                  <div v-else>
                     <strong>{{candidature.owner.username}}</strong> à laissé sa candidature  <strong>{{ candidature.created}}</strong>. 
                  </div>
                  <div v-if="isOwnerDemand">
                     <form :action="contractUrl" method='post'>
                           <input type="hidden" name='_token' :value="csrf">
                           <button>Selectionner</button>  
                     </form>
                   
                  </div>
               </a>
            </div>
         </div>
      </div>
   </div>
</template>

<script>
export default {
   props:['demand', 'candidature', 'auth_user', 'csrf'],
   mounted() {
   },
   computed: {
      isOwnerCandidature: function() {
         return this.auth_user.id == this.candidature.owner_id
      },

      isOwnerDemand: function() {
         return this.auth_user.id == this.demand.owner_id
      },
      
      contractUrl: function() {
         return "/demands/"+this.demand.id+"/contract/"+this.candidature.id
      }

      
   }
}
</script>