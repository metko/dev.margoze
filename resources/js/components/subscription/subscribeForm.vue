<template> 
<div>
<transition name="slide-top" mode="out-in">
    <div v-if="!isLoading" key="form">
          <div v-if="statut == 'error'" class="text-center">
              <div class="text-center title l4 text-red-600 mb-5">{{errorGlobalMessage}}</div>
              <div class="border border-red-600 text-red-600 p-4 rounded">{{statutMessage}}</div>
          </div>

          <div v-else-if="statut == 'success'" class="text-center">
              <div class="text-center title l4 text-blue-primary mb-5">{{successGlobalMessage}}</div>
              <div  class="border border-gray-700 text-gray-700 p-4 rounded">{{statutMessage}}</div>
          </div>

          <div v-else>
            <form action="#" class="bg-gray-100 rounded p-6" method="POST" 
                  @submit.prevent="submitForm()">
              <div class="text-center title l4 text-blue-primary mb-5">Veuillez enregistrer une carte pour vous abonnez</div>
              <input id="card-holder-name" 
                      v-model="cardName"
                      placeholder="Nom sur la carte" 
                      class="w-full rounded p-3 focus:outline-none mb-3" 
                      type="text">

              <!-- Stripe Elements Placeholder -->
              <div id="card-element"></div>
              <div v-if='cardError' class="text-red-600 text-sm">{{cardError}}</div>
              <div class="w-full flex justify-center">
                <button type="submit" id="card-button" class="btn inline-block mt-5 text-center">
                    Souscrire
                </button>
              </div>
            </form>
          </div>
          
    </div>
    <div v-else key="loader" class="h-16">
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
    components: {LoaderAnim},
    props: ['stripekey', 'plan', 'user'],
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
        const elements =  this.stripe.elements();
        this.card = elements.create('card', style );
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
    methods: {
        canSubmit() {
           let card = document.getElementById('card-element')
           if(card.classList.contains('StripeElement--empty')){
              this.cardError =  msg.card_empty
           }
            if(card.classList.contains('StripeElement--complete')){
                return true
            }
        },

        submitForm() {
            if( ! this.canSubmit()){
              return false
            } 
            this.isLoading = true
            if(this.user.card_last_four){
                cardFn.subscribeToPlan(this)
            }else{
               const vm = this
               return cardFn.createPaymentMethodAndSubscribeToPlan(this)
               
            }  
        },

        
      
        createSubscription() {
          const vm = this
          axios.post('/plans/subscribe/'+this.plan.slug, {
              plan: vm.plan,
              paymentMethod: vm.paymentMethod
             
          }).then(function(response){
              console.log(response)
              if(response.data.payment_intent && response.data.require_actions){
                  cardFn.handleCardAuth(response.data)
              }else if(response.data.payment_intent && response.data.error_message && ! response.data.require_actions){
                  vm.statut = "error"
                  vm.statutMessage = response.data.error_message
                  vm.isLoading = false
              }else if(response.data.error) {
                  vm.statut = "error"
                  vm.statutMessage = response.data.error.message
                  vm.isLoading = false
              }else{
                    vm.statut = "success"
                    vm.statutMessage = msg.subscription_success
                    vm.isLoading = false
                    // document.location.href = '/subscriptions';
              }    
          })
        },
        handleCardAuth(paymentIntent){
          const vm = this
         this.stripe.handleCardPayment(paymentIntent.client_secret)
            .then(function(response) {
               if(response.error) {
                  console.log(response.error.message);
                   vm.statut = "error"
                  vm.statutMessage = response.error.message
               } else {
                  console.log('Collected payment!');
                  console.log(response.paymentIntent.id);
                  vm.statut = "success"
                  vm.statutMessage = msg.subscription_success
                  vm.isLoading = false
                  document.location.href = '/subscriptions';
               }
         })
      }
    },
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
