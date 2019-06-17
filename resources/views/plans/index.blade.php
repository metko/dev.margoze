@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 lg:px-0">
      <div class=" py-8 text-3xl mt-4">
         Plans 
      </div>

      <div class="px-2">
         <div class="flex -mx-2">
            @foreach ($plans as $plan)
            <div class="w-1/3 px-2">
               <div class="bg-gray-200 px-6 py-8 text-center rounded-lg">
                     <h3 class="text-xl">{{$plan->name}}</h3>
                     <p class="text-gray-700 mb-8">Description</p>
                     @if (! Auth::user()->subscribed('main', $plan->stripe_id))
                        <a href=" {{ route('plans.show', $plan->slug) }}" class="btn btn-info">Subscribe</a>

                     @endif
               </div>
            </div>
            @endforeach
            
            
         </div>
      </div>
     

</div>
@endsection