@extends('layouts.app')

@section('content')
<div class="card">
      <div class=" py-8 text-2xl mt-4 text-center">
         Subscribe for {{ $plan->slug }} for {{ $plan->amount }}€/mois 
      </div>

      <subscribe-form 
         stripe-key="{{ config("services.stripe.key") }}"
         url="{{ route('plans.subscribe') }}"
         plan-name="{{ $plan->stripe_id }}"
         >
      </subscribe-form>

</div>
@endsection


@push('scripts')
   <script src="https://js.stripe.com/v3/"></script>
   <script>
   // var stripe = Stripe('{{ config("services.stripe.key") }}');

   // var elements = stripe.elements();
   // var cardElement = elements.create('card');
   // cardElement.mount('#card-element');
   // var cardholderName = document.getElementById('cardholder-name');
   // var planName = document.getElementById('plan_name');
   // var form = document.getElementById('payment-form');

   // cardElement.addEventListener('change', function(event) {
   //       var displayError = document.getElementById('card-errors');
   //       if (event.error) {
   //          displayError.textContent = event.error.message;
   //       } else {
   //          displayError.textContent = '';
   //       }
   //    });


   // form.addEventListener('submit', function(ev) {
   //    ev.preventDefault();
     
   //    stripe.createToken(cardElement).then(function(result) {
   //       if (result.error) {
   //          // Inform the customer that there was an error.
   //          var errorElement = document.getElementById('card-errors');
   //          errorElement.textContent = result.error.message;
   //       } else {
   //          // Send the token to your server.
   //          stripeTokenHandler(result.token);
   //       }
        
   //    });
      

   //    function stripeTokenHandler(token) {
   //       // Insert the token ID into the form so it gets submitted to the server
   //       const axios = require('axios');

   //       var form = document.getElementById('payment-form');
   //       var hiddenInput = document.createElement('input');
   //       var url = form.action;
   //       hiddenInput.setAttribute('type', 'hidden');
   //       hiddenInput.setAttribute('name', 'stripeToken');
   //       hiddenInput.setAttribute('value', token.id);
   //       form.appendChild(hiddenInput);
        
   //       axios.post(url, {
   //          stripeToken : token.id,
   //          holderName : cardholderName,
   //          plan_name : planName,
   //       })
   //       .then(function (response) {
   //          console.log(response);
   //       })
   //       .catch(function (error) {
   //          console.log(error);
   //       });
   //       //axios.post(form.)
         
   //       //form.submit();
   //       }
   // });

</script>

@endpush