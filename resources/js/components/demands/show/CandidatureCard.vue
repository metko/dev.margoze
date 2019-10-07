<template>
<div>
      <div class="avatar-user flex flex-col  items-center mr-4  flex-shrink-0">
         <img :src="candidature.owner.avatar" alt="" 
            class="rounded-full border-yellow-primary border-2   h-8 w-8 "> 
         <div class="candidature_candidature">{{candidature.owner.username}}</div>
      </div>
      <div>
         <div class=" candidature_content">
            {{candidatureContent(candidature.content)}}
            <a href="#" @click.prevent="expandContent" class="candidature_more">{{seeMore}}</a>
         </div>
         <div class="mt-2 candidature_created">
            Posté le <span class="candidature_created_date" > {{candidatureCreatedAt}}</span>
         </div>
      </div>
   
      <div @click.prevent="contractCandidature()" 
      class="ml-auto h-8 w-8 bg-red-600 flex-shrink-0 hover:pointer"
         v-bind:class="{'bg-green-600' : auth.credits.contracts_count}">

      </div> 
   
   </div>
</template>

<script>
import moment from "moment"


export default {
   props: ['candidature', 'auth'],
   data() {
      return {
         textExpanded : false,
         
      }
   },
   created() {
         moment.locale('fr')
   },
   methods: {
      contractCandidature() {
         this.$modal.show('contract-candidature', { candidature : this.candidature })
      },
      expandContent(){
         this.textExpanded = !this.textExpanded
      },
      candidatureContent(content) {
         if(! this.textExpanded){
            return content.length < 70 ?
               content :
               content.substring(0, 70)+'...';
         }
          return content
      },
      
   },
   computed: {
      seeMore(){
         if(! this.textExpanded){
            return "Voir plus";
         }
         return 'Voir moins'
      },
      candidatureCreatedAt(){
         return moment(this.candidature).format("dddd Do MMMM Y")
      },
   }
}
</script>

<style scoped>
   .candidature_candidature{
      @apply uppercase font-bold text-white tracking-wide text-xs mt-2
   }
   .candidature_content{
      @apply text-gray-300 leading-snug
   }
   .candidature_more{
      @apply underline text-blue-400
   }
   .candidature_created{
      @apply text-gray-500 text-sm italic
   }
   .candidature_created_date{
      @apply text-blue-400  font-bold 
   }

   .cardCandidature_modal{
      @apply bg-gray-100 rounded px-4 mb-3 
   }
   .cardCandidature_modal .candidature_candidature{
      @apply uppercase font-bold text-gray-800 tracking-wide text-xs mt-2
   }
   .cardCandidature_modal .candidature_content{
      @apply text-gray-600 leading-snug
   }
   .cardCandidature_modal .candidature_more{
      @apply underline text-blue-700
   }
   .cardCandidature_modal .candidature_created{
      @apply text-gray-500 text-sm italic
   }
   .cardCandidature_modal .candidature_created_date{
      @apply text-blue-primary  font-bold 
   }
</style>

