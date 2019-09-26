<template>
  <div class="flex p-5 lg:p-8 hover:bg-gray-100 border-b border-gray-100">
     <div class="flex-shrink-0">
      <div class="avatar-user flex flex-col items-center mb-4 ">
         <img class="rounded-full border-blue-primary border-2 md:border-4  h-12 w-12 lg:h-16 lg:w-16"
         :src="ownerAvatar" alt="">
         <div class="uppercase font-bold text-gray-700 text-xs mt-1">{{ownerUsername}}</div>
      </div>   
      <div class="sector">
         <div class="avatar-user flex flex-col items-center">
            <img class=" h-10 w-auto"
            :src="sectorLogo" alt="">
            <div class="uppercase font-bold text-gray-700 text-xs mt-1">
               <a href="#" class="hover:text-blue-primary">{{sectorName}}</a>
            </div>
         </div>
      </div>
   </div>
    <div class=" ml-6 flex flex-col justify-around">
         <div class="uppercase font-bold text-gray-700 text-xs  ">
            <a href="#" class='hover:text-blue-primary'>{{demandCategory}}</a> </div>
         <div class="title l4 text-gray-800 mb-2"><a :href="demandPath">{{ demand.title }}</a></div>
      <div class="description text-sm text-gray-600 mb-2">{{ demand.description }}</div>
         <div class="uppercase  text-gray-700 text-xs ">
            <span class="mr-2 border rounded border-blue-primary bg-blue-primary text-white px-2 py-1">{{ demandAddress }}</span>
            <span class="mr-2 border rounded border-blue-primary text-blue-primary px-2 py-1">{{ candidaturesCount }} candidatures</span>
            <span class="mr-0 border rounded border-gray-600 px-2 py-1">{{ validFor }}</span></div>
      </div>

   </div>
</template>

<script>
import moment from 'moment'

export default {
   props : ['demand'],
   data() {
      return {
         
      }
   },
   mounted() {

  },
   computed: {
        ownerUsername: function() {
           return this.demand.owner.username
        },
        ownerAvatar: function() {
           return this.demand.owner.avatar
        },
        sectorName: function() {
           return this.demand.sector.name 
        },
        sectorLogo: function() {
           return "/img/reunion-nord.svg" 
        },
        demandCategory: function() {
            return this.demand.category.name 
        },
        demandAddress: function() {
            return this.demand.commune.name+', '+this.demand.district.name
        },
        candidaturesCount: function() {
            return this.demand.candidatures_count
        },
        validFor: function() {
            var end = moment(this.demand.valid_until);
            
            if(end.diff(moment(), 'days') >= 1) {
               let diff = end.diff(moment(), 'days');
               if(diff == 1){
                  return diff + " jour restant" 
               }
               return diff + " jours restants"
            }
            else{
               let diff = end.diff(moment(), 'hours');
               if(diff == 1){
                  return diff + " heure restante" 
               }
               return diff + " heures restantes"
            }
            
        },
        demandPath: function() {
           return "/demands/"+this.demand.id
        }
   },
   methods: {
    
   }
}
</script>