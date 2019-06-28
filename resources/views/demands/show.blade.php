@extends('layouts.app')

@section('content')
{{-- 
<div class="container mx-auto px-4 md:px-0 lg:px-0">
   <div class="lg:flex -mx-4">  
      <div class="w-full lg:w-3/4 px-4">
         @include('demands.components.card_full') 
      </div>
      <div class="md:w-full lg:w-1/4 px-4">
         <div class=" bg-white rounded  rounded-b-none overflow-hidden shadow-lg">
            <div class="px-6 py-4 text-gray-700">
               <strong>{{ $demand->candidatures->count()}}</strong> candidatures
            </div>
         </div>
         @forelse ($demand->candidatures as $candidature)
               @include('candidatures.components.card')
         @empty
         <div class="px-6 py-4 text-gray-700 text-center">
            Soyez le premier a poster votre candidature pour ce job!
         </div>
         @endforelse
      </div>
   </div>   
</div> --}}

<demand-show
   :demand="{{$demand}}"
   is_owner="{{Auth()->user()->isOwnerDemand($demand)}}"
   user_has_apply="{{Auth()->user()->hasApply($demand)}}"
   :auth_user="{{Auth()->user()}}"
   apply_action_url="{{ route('demands.apply', $demand->id) }}"
></demand-show>


@endsection
      