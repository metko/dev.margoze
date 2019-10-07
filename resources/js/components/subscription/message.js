let message = {
   subscription_success : "Vous êtes maintenant abonné",
   card_success : 'La carte a bien été ajouté',
   global_error_message : "Oups!",
   global_success_message : "Génial!",
   card_empty : "Veuillez renseigner vos donnée de carte bancaire"
}

// CREATE METHOD PAYMENT IN STRIPE
let createPaymentMethod = function(vm, nextSubscribe = false) {
   
   return vm.stripe.createPaymentMethod( 'card', vm.card, {
         billing_details: { 
            name: vm.cardName,
            email: vm.user.email,
         },      
   }).then(function(response) {
      if(response.error) {
         // alert(response.error.message);
         console.log(response.error)
         vm.statut = 'error'
         vm.statutMessage = response.error.message
         vm.isLoading = false
      } else {
         vm.paymentMethod = response.paymentMethod
         savePaymentMethod(vm, response.paymentMethod, nextSubscribe)
         return response.paymentMethod;
      }
   })
}

// SAVE METHOD PAYMENT IN APP
let savePaymentMethod = function(vm, paymentMethod, nextSubscribe) {
   return axios.post('/users/card',{
      paymentMethod : paymentMethod,
   }).then(function(response){
      if(response.data.status == "success"){
         if(! nextSubscribe){
            vm.statut= "success"
            vm.statutMessage = message.card_success
            vm.isLoading = false
            location.reload();
         }else{
            subscribeToPlan(vm)
         }
         // console.log(response.data)
           
      }else{
            // console.log(response.data)
            vm.statut= "error"
            vm.statutMessage = response.data.error.message
            vm.isLoading = false
      }     
   })
}

// HANDLE SCA AUTH
let handleCardAuth = function(vm, paymentIntent){
  vm.stripe.handleCardPayment(paymentIntent.client_secret)
     .then(function(response) {
        if(response.error) {
            console.log(response.error.message);
            vm.statut = "error"
            vm.statutMessage = response.error.message
            vm.isLoading = false
        } else {
           console.log('Collected payment!');
           console.log(response.paymentIntent.id);
           vm.statut = "success"
           vm.statutMessage = message.subscription_success
           vm.isLoading = false
           document.location.href = '/subscriptions';
        }
  })
}

// SUBSCRIBE TO SPECIFIC PLAN
let subscribeToPlan = function(vm) {
   if(vm.plan){
      vm.$modal.hide('confirm-subscription-button')
   }
   console.log('SUBSCRIBE STOP')
   //return
   vm.isLoading = true
   axios.post('/plans/subscribe/'+vm.plan.slug, {
      plan: vm.plan,
   })
   .then(function(response){
      
         if(response.data.payment_intent && response.data.require_actions){
            handleCardAuth(vm, response.data)
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
            vm.statutMessage =  message.subscription_success
            vm.isLoading = false
               // document.location.href = '/subscriptions';
         }    
   })
}

let createPaymentMethodAndSubscribeToPlan = function(vm) {
   createPaymentMethod(vm, true)
}

// CHECK IF CARD INPUT IS FILL
let canSubmit = function(vm) {
   let card = document.getElementById('card-element')
   if(card.classList.contains('StripeElement--empty')){
      vm.cardError =  message.card_empty
   }
    if(card.classList.contains('StripeElement--complete')){
        return true
    }
}

export default {message,  savePaymentMethod, createPaymentMethodAndSubscribeToPlan, createPaymentMethod, handleCardAuth, subscribeToPlan,   canSubmit}