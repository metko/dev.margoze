<template>
<div>
   <transition name="slide-top" mode="out-in">

      <div v-if="!isLoading" key="button" class="bg-gray-100 rounded p-6">

         <div v-if="statut == 'error'" class="text-center">
              <div class="text-center title l4 text-red-600 mb-5">{{errorGlobalMessage}}</div>
              <div class="border border-red-600 text-red-600 p-4 rounded">{{statutMessage}}</div>
          </div>

          <div v-else-if="statut == 'success'" class="text-center">
              <div class="text-center title l4 text-blue-primary mb-5">{{successGlobalMessage}}</div>
              <div  class="border border-gray-700 text-gray-700 p-4 rounded">{{statutMessage}}</div>
          </div>

         <div v-else>
            <div>
            <div class="text-center title l4 text-blue-primary mb-5">Paiment</div>
         </div>
         <div class=" text-center">
            <div class="text-gray-700">Vous allez utilisé la carte se terminant par 
            <span class="border rounded border-gray-500 px-1">{{ user.card_last_four }}</span>
            </div> 
            <div><a href="#" class="underline text-blue-primary">Mettre à jour ma carte</a></div>
         </div>
         <div class="flex justify-center">
            <button @click="$modal.show('confirm-subscription-button')" id="card-button" class="btn inline-block mt-5 text-center">
               Souscrire
            </button>
         </div>
      </div>
         </div>
      

      <div v-else key="loader" class="h-16">
         <LoaderAnim></LoaderAnim>
      </div>


   </transition>
      <modal name="confirm-subscription-button"  style="z-index:1000"  
         :maxWidth="800" width="90%" :adaptive="true" height="auto" >
         
         <div  class="flex justify-center items-center flex-col mt-6 ">
             <div class="title l4 text-blue-primary text-center px-4">
                Etes vous sur de vouloir souscrire à l'abonnement {{ plan.name }} ?
            </div>
         </div>
         <div class="mt-6 text-gray-500 flex w-full items-center ">
            <a href="#" 
            @click.prevent="$modal.hide('confirm-subscription-button')"
            class="w-1/2 text-center text-red-600 bg-gray-100 hover:bg-gray-200 py-4 ">Annuler</a>
            <a href="#" 
            @click.prevent="subscribeToPlan()"
            class="w-1/2 text-center bg-blue-primary text-white hover:bg-blue-darken py-4">Oui, je souscris</a>
         </div>
           

      </modal>
</div>
</template>

<script>

import LoaderAnim from '@/components/LoaderAnim.vue'
import cardFn from "./message.js"

export default {
   components: {LoaderAnim},
   props: ['stripekey', 'user', 'plan'],
   data() {
      return {
         isLoading : false,
         stripe : "",
         statut : "",
         statutMessage : ""
      }
   },
   computed: {
      errorGlobalMessage(){
        return cardFn.message.global_error_message
      },
      successGlobalMessage(){
        return cardFn.message.global_success_message
      }
    },
   mounted() {
       this.stripe = Stripe(this.stripekey);
   },
   methods: {
      subscribeToPlan() {
         return cardFn.subscribeToPlan(this)
      },
   }
}
</script>

<style>
   

</style>