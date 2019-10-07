@extends('layouts.app')

@section('content')
<div class="border-b border-gray-100 py-24 pt-32 px-4 md:px-0">
      <div class="container mx-auto">
            <div class="title l3  text-blue-primary ">
               Formules
           </div>
      </div>
</div>



<div class="container mx-auto mt-6 mb-10">
      <div class="-mx-4 flex flex-wrap">
         @foreach ($plans as $plan)
            <div class="w-1/3 px-4  ">
               <div class="border border-gray-100 rounded text-center px-4 py-5 hover:bg-gray-100">
                     <div class="title l5 px-4">{{$plan->name}}</div>
                     <div class="text-gray-700 px-4 mt-4"> Nos prestations sont vérifiés et encadrés</div>
                     <a href="{{ route('plans.show', $plan->slug)}}" class="btn inline-block mt-4">{{$plan->amount}},00€/m</a>
               </div>
            </div>
         @endforeach
   </div>
</div>
@endsection