<template>
   <div>
      <div class="flex w-full ">
         <div class="avatar-user flex flex-col items-center mr-4 lg:mr-10">
               <img class="rounded-full border-yellow-primary border-2 md:border-4  h-8 w-8 lg:h-12 lg:w-12 lg:h-16 lg:w-16"
               :src="demand.owner.avatar" alt="">
               <div class="uppercase font-bold text-white tracking-wide text-xxs mt-2">{{ demand.owner.username }}</div>
         </div>
         <div>
               <div class="text-gray-400 text-sm italic">Mis en ligne  {{ createdSince }}</div>
               <div class="title l4 lg:l2 text-white leading-none -mt-2">{{ demand.title }}</div>
               <div class="flex mt-2">
                  <div class="bg-blue-darken text-sm text-white py-1 px-2 inline-block rounded-sm mr-4">{{ demand.category.name }}</div>
                  <div class="btn  btn-inverse small">{{ demand.commune.name }}, {{ demand.district.name }}</div>
               </div>
         </div>
      </div>

      <div class="mt-10 text-white text-center lg:text-xl font-bold bg-blue-darken p-4 lg:p-10 border-2 border-blue-600 rounded flex justify-content-around items-center justify-around">
            <div> <span class="text-yellow-primary">{{candidatureCount}}</span><br> candidatures reçu </div>
          <div>
           A realiser le <br><span class="text-yellow-primary">{{beDoneAt}}</span>  
         </div>
 
      </div>

      <div class="separator w-16 bg-blue-darken h-1 rounded-full my-8 lg:my-12"></div>
      
      <div>
         <div class="text-yellow-primary text-xl mb-6">Description</div>
         <div class="text-white leading-relaxed">
            <p class="my-8">{{ demand.description }}. 
            </p>
            <p class="my-8"> {{ demand.content }} 
            </p>
         </div>
      </div>

       <div class="separator w-16 bg-blue-darken h-1 rounded-full my-8 lg:my-12"></div>

       <div>
         <div class="text-yellow-primary text-xl mb-6">Photos</div>
         <div class="flex flex-wrap -mx-3">
            <div v-for='image in demand.images' v-bind:key="image.id " class="px-3 w-1/4 ">
                  <div class=" h-32 overflow-hidden rounded overflow-hidden flex items-center ">
                        <img :src="image.path" alt="" class="h-auto w-full">
                  </div>
            </div>            
         </div>

      </div>

   </div>
</template>

<script>
import moment from 'moment'
   export default {
      props: ['demand'],
      data() {
         return {
            candidatureCount: this.demand.candidatures.length
         }
      },
      created() {
         moment.locale('fr')
      },
      computed: {
         
         createdSince: function() {
            return moment(this.demand.created_at).fromNow()
         },
         beDoneAt: function() {
            return  moment(this.demand.be_done_at).format("dddd Do MMMM à  H:mm");
         }   
      }
   }
</script>