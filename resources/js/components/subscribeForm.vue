<template> 
<div>
    <div v-if="responseMessage" class="alert success" v-bind:class="{ danger: errors }" >
        {{ responseMessage }}
    </div>
   <form @submit.prevent="submit" id='subscribe-form' v-bind:class="{ hide: hideForm }" >
            <div v-bind:class="{ hide: loading }">
                <input  type="text" name="cardholder" placeholder="Nom sur la carte" style="border: 1px solid #ced4da; padding: .375rem .75rem; line-height: 1.3; border-radius: .25rem; width:100%; margin-bottom: 10px; background: transparent" v-model="fields.cardholder">
            
                <div class="form-group" style="border: 1px solid #ced4da; padding: .375rem .75rem; line-height: 1.5; border-radius: .25rem;">
                <div id="card-element"></div>
                </div>
                <div v-if="cardError" id="card-errors">{{ cardError }}</div>
            </div>
            <div class="text-center">
               <button 
                    :disabled="loading" 
                    id="card-button" 
                    type="submit" 
                    class="btn btn-info" 
                    v-bind:class="{ disabled: loading }">
                    {{ textButton }}
               </button>
            </div>
    </form> 
    <div>4000000000003220</div>
    <div>4242424242424242</div>
   </div>
</template>

<script>


export default {
    props: ['stripeKey', 'url', 'planName'],
    data() {
        return {
            fields: {},
            cardError: "",
            errors: false,
            cardElement : "",
            stripeElement : "",
            loading : false,
            textButton : "Subscribe",
            hideForm : false,
            responseMessage : ""
        }
    },
    mounted() {

        this.stripe = Stripe(this.stripeKey);
        let elements = this.stripe.elements();
        this.cardElement = elements.create('card', {
            iconStyle: 'solid',
            style: {
            base: {
                iconColor: '#000',
                color: '#000',
                fontWeight: 500,
                fontFamily: 'Roboto, Open Sans, Segoe UI, sans-serif',
                fontSize: '16px',
                fontSmoothing: 'antialiased',

                ':-webkit-autofill': {
                color: '#000',
                },
                '::placeholder': {
                color: '#000',
                },
            },
            invalid: {
                iconColor: '#000',
                color: '#099',
            },
            },
        });
        this.cardElement.mount('#card-element');    
        var vm = this;
        this.cardElement.addEventListener('change', function(event) {
            if (event.error) {
               vm.cardError = event.error.message;
            }else{
                vm.cardError = ""
            }
        });
    },

    methods: {
        verifyPayment(data) {
            let vm = this;
            let paymentIntentSecret = data.data.latest_invoice.payment_intent.client_secret;
            vm.stripe.handleCardPayment(
                paymentIntentSecret
            ).then(function (response) {
                console.log(response)
                if (response.error) {
                        vm.responseMessage = "Une erreur interne est survenue, merci de ressayer plus tard";
                        vm.errors = true;
                        vm.loading = false;
                        vm.textButton = "Subscribe";
                } else {
                    vm.responseMessage = "yey paiement ok";
                }
            })
        },
        submit() {
            let vm = this;
            this.stripe.createToken(this.cardElement).then(function(result) {
                
              if (result.error) {
                 // Inform the customer that there was an error.
                 var errorElement = document.getElementById('card-errors');
                 vm.cardError = result.error.message;
              } else {
                 vm.loading = true;
                 vm.textButton = '...';
                 axios.post(vm.url, {
                     stripe_token : result.token.id,
                     holder_name : vm.fields.cardholder,
                     plan_name : vm.planName,
                     card_element : vm.cardElement
                  })
                  .then(function (response) {
                        vm.responseMessage = response.data.message;
                        if(response.data.status == "success"){
                            vm.hideForm = true;
                        }
                        if(response.data.status == "incomplete"){
                            vm.hideForm = true;
                            vm.verifyPayment(response.data);
                        }
                  })
                  .catch(function (error) {
                      console.log(error);
                      if (error.response.status === 405) {
                         vm.responseMessage = "Une erreur interne est survenue, merci de ressayer plus tard";
                      }
                      if(error.response.data.exception = "Stripe\Error\Card"){
                         vm.responseMessage = error.response.data.message;
                      }

                      vm.errors = true;
                      vm.loading = false;
                      vm.textButton = "Subscribe"; 
                  });
              } 
           });
        },  
    },
}
</script>
