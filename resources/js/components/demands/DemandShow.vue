<template>
   <div class="container mx-auto px-4 md:px-0 lg:px-0">
      
         <div class="px-4 py-8 leading-none text-3xl mt-4 flex text-indigo-800">
               <h3> {{ demand.title }} </h3>
               <a v-if="is_owner"
               class="ml-auto bg-indigo-500 hover:bg-indigo-700 text-white text-sm font-semibold py-2 px-4 rounded-full ml-auto" 
               href="">Gérer</a>
            <a v-else-if="hasApply"
               class="ml-auto cursor-not-allowed focus:outline-none bg-gray-500 hover:bg-gray-600 text-white text-sm font-semibold py-2 px-4 rounded-full ml-auto" 
               >En attente de réponse</a>
            <a v-else
               v-on:click.prevent="openModal"
               class="ml-auto bg-orange-600 hover:bg-orange-600 text-white text-sm font-semibold py-2 px-4 rounded-full ml-auto" 
               href="" >Postuler</a>
         </div>

         <div class="flex flex-wrap -mx-4">
            <div class="md:w-full lg:w-3/4 px-4">
               <demand-card-full
                  :demand="demand"
                  :candidaturesCount="demand.candidatures.length"
               ></demand-card-full>
            </div>
            <div class="md:w-full lg:w-1/4 px-4">
               <div class=" bg-white rounded  rounded-b-none overflow-hidden shadow-lg">
                  <div class="px-6 py-4 text-gray-700">
                     <strong>{{ demand.candidatures.length }}</strong> candidatures
                  </div>
               </div>
               
               <candidature-card v-for="(candidature, index) in candidatures" v-bind:key="candidature.id"
                  :index="index"
                  :auth_user="auth_user"
                  :demand="demand"
                  :candidature="candidature"
                  :csrf="csrf"
               ></candidature-card>

               <div v-if="demand.candidatures.length <= 0" class="px-6 py-4 text-gray-700 text-center">
                  Soyez le premier a poster votre candidature pour ce job!
               </div>

            </div>
         </div>

         <create-candidature-modal
            :name="'create-candidature-'+demand.id"
            :demand="demand"
            :action="apply_action_url"
         ></create-candidature-modal>

   </div>
</template>

<script>
import moment from 'moment'

export default {
   props:['demand', 'is_owner', 'user_has_apply', 'auth_user', 'apply_action_url', 'csrf'],
   data () {
      return {
         hasApply : false,
         candidatures : []    
      }
   },
   mounted () {
      this.hasApply = this.user_has_apply
      this.candidatures = this.demand.candidatures
      let vm = this;
      Echo.channel('demands.'+this.demand.id)
         .listen('.App\\Candidature\\Events\\CandidatureCreated', (event) => {
            console.log(event.candidature);
            //alert('ddd');
            vm.candidatures.push(event.candidature)
            vm.hasApply = true
            //Array.prototype.unshift.call(vm.candidatures, event.candidature);
         });
   },
   methods: {
       openModal(){
         this.$modal.show('create-candidature-'+this.demand.id)
      },
   },
   computed: {
      validFor: function() { 
         moment.locale('fr');
         let validFor = moment(this.demand.valid_until);
         return validFor.diff(moment(), 'days');
      },
   },
   
}
</script>
