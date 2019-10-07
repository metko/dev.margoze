<template>
   <div class="bg-gray-100 flex items-center flex-col  ">
     
         <transition name="slide-top" mode="out-in" >
            <form action="#" method="POST" v-if="!isLoading" key="form" class="p-6"
             @submit.prevent="submitForm()">

               <div v-if="statut == 'error'" class="text-center">
                  <div class="text-center title l4 text-red-600 mb-5">{{errorGlobalMessage}}</div>
                  <div class="border border-red-600 text-red-600 p-4 rounded">{{statutMessage}}</div>
               </div>

               <div v-else-if="statut == 'success'" class="text-center">
                  <div class="text-center title l4 text-blue-primary mb-5">{{successGlobalMessage}}</div>
                  <div  class="border text-gray-700 p-4 rounded">{{statutMessage}}</div>
               </div>
               <div v-else>
                  <div  class="text-center title l4 text-blue-primary mb-5">Veuillez enregistrer une carte pour vous abonnez</div>
                  <input id="card-holder-name" 
                           v-model="cardName"
                           placeholder="Nom sur la carte" 
                           class="w-full rounded p-3 focus:outline-none mb-3" 
                           type="text">

                  <!-- Stripe Elements Placeholder -->
                  <div id="card-element" ></div>
                  <div v-if='cardError' class="text-red-600 text-sm">{{cardError}}</div>

                  <div class="w-full flex justify-center">
                        <button id="card-button" class="btn inline-block mt-5 text-center">
                              Enregistrer la carte
                        </button>
                  </div>
               </div>
               
            </form>

            <div v-else key="loader" class="h-32 w-full">
                  <LoaderAnim></LoaderAnim>
            </div>
         </transition>
            
      
   </div>
</template>

<script>
import LoaderAnim from "@/components/LoaderAnim.vue"
import style from "./cardStyle.js"
import cardFn from "./message.js"

export default {
   props: ['user', 'stripekey'],
   components: {LoaderAnim},
    data() {
        return {
            isLoading : false,
            statut : '',
            statutMessage : '',
            stripe : "",
            card : "",
            paymentMethod : "",
            cardError : "",
            cardName : this.user.first_name+" "+this.user.last_name
        }
    },
   mounted() {
      this.stripe = Stripe(this.stripekey);
      const elements =  this.stripe.elements();
      this.card = elements.create('card', style);
      this.card.mount('#card-element');

      const vm = this
      this.card.addEventListener('change', function(event) {  
         if (event.error ) {
               vm.cardError = event.error.message;
         }
         else{
               vm.cardError = ""
         }
      });
    
   },
   computed: {
      errorGlobalMessage(){
        return cardFn.message.global_error_message
      },
      sucessGlobalMessage(){
        return cardFn.message.global_success_message
      }
   },
    methods: {
        submitForm() {
            if( !cardFn.canSubmit(this)){
              return false
            } 
            this.isLoading = true
            cardFn.createPaymentMethod(this)
        },
    }
    
}
</script>

<style scoped>
   .StripeElement {
  box-sizing: border-box;

  height: 40px;

  padding: 10px 12px;

  border: 1px solid transparent;
  border-radius: 4px;
  background-color: white;

  box-shadow: 0 1px 3px 0 #e6ebf1;
  -webkit-transition: box-shadow 150ms ease;
  transition: box-shadow 150ms ease;
}

.StripeElement--focus {
  box-shadow: 0 1px 3px 0 #cfd7df;
}

.StripeElement--invalid {
  border-color: #fa755a;
}

.StripeElement--webkit-autofill {
  background-color: #fefde5 !important;
}
</style>