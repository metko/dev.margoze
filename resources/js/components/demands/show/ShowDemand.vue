<template >
   <div class="bg-blue-primary  lg:min-h-screen pt-10">

         <div class="container mx-auto ">
            <div class="md:-mx-6 lg:flex ">
               <div class="content-left w-full lg:w-2/3 px-10 py-16 md:px-6  ">
                  <ContentDemand ref="contentDemand" :demand="demand"></ContentDemand>
               </div>
               <div class="content-right w-full  pb-10 lg:w-1/3  md:px-6 ">

                  <LoginAccess v-if="isGuest()"
                     class='lg:sticky' style="top:100px"
                  ></LoginAccess>

                  <OwnerActions v-else-if="isOwnerDemand()"
                     :demand="demand"
                     class=" lg:sticky " style="top:100px"
                  ></OwnerActions>

                  <div v-else-if="isContracted()" 
                  :demand="demand"
                  class=" lg:sticky"  style="top:100px"
                  >
                     <div class="flex items-center justify-center flex-col leading-none ">
                        <div class="font-bold title l4 leading-none text-white text-center">Cette demande est déja contracté</div>
                        <a class="btn btn-white inline-block mt-6" :href="'/dashboard/contracts/'+demand.contract.id">Voir le contrat</a>
                  </div>
                  </div>

                  <ApplyForm v-else="" 
                  :demand="demand"
                  class=" lg:sticky"  style="top:100px"
                  ></ApplyForm>

               </div>

            </div>
         </div>
  


   </div>
</template>

<script>
import ContentDemand from './ContentDemand.vue'
import ApplyForm from './ApplyForm.vue'
import OwnerActions from './OwnerActions.vue'
import LoginAccess from './LoginAccess.vue'

export default {
   components: {ContentDemand, ApplyForm, OwnerActions, LoginAccess}, 
   props: ['demand', 'auth'],
   methods: {
      isGuest() {
         return ! this.auth
      },
      isOwnerDemand() {
        return this.auth.id === this.demand.owner_id
      },
      isContracted() {
        return this.demand.contracted
      }
   }
}
</script>