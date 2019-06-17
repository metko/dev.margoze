@extends('layouts.app')

@section('content')
<div class="card">
   <div class=" py-8 text-2xl mt-4 text-center">
      Confirm to validate your subscription   
   </div>
</div>
@endsection

@push('scripts')
   <script src="https://js.stripe.com/v3/"></script>
   <script>
   var stripe = Stripe('{{ config("services.stripe.key") }}');
   var paymentIntentSecret = "{{ $subscription->latest_invoice->payment_intent->client_secret }}";

   stripe.handleCardPayment(
      paymentIntentSecret
   ).then(function (result) {
      if (result.error) {
         // Display error.message in your UI.
      } else {
         console.log('paiment ok');
         // The payment has succeeded. Display a success message.
      }
   })
</script>

@endpush