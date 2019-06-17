@extends('layouts.app')

@section('content')
<div class="card">
   <div class=" py-8 text-2xl mt-4 text-center">
         <h2>Congratz, you subscribed to the plan {{ $subscription->plan->nickname }}</h2>      
   </div>
</div>
@endsection

