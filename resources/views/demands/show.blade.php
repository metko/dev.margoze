@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 md:px-0 lg:px-0">
      <div class=" py-8 text-3xl mt-4">
         Demands {{ $demand->title }}
         @if (Auth::user()->hasApply($demand))
                  <div class="text-sm">
                     Vous avez postuler pour cette demande. {{$demand->owner->username}} a {{ $demand->valid_for}} jours pour vous répondre
                  </div>
         @endif
               
      @can('apply', $demand)
         <a class="btn btn-info" href="{{ route('demands.apply', $demand->id)}}">Apply</a>
      @endcan
      </div>
      <div class="md:flex flex-wrap -mx-4 mt-4">
         <div class="p-3 w-full">
            @include('demands.components.card')
         </div>
      </div>
      <div class="md:flex flex-wrap -mx-4 mt-4">
         @can('manage', $demand)
            @forelse ($demand->candidatures as $candidature)
               <div class="p-3 md:w-1/3">
                  @include('candidatures.components.card')
               </div> 
            @empty
                  Aucune candidature recu pour le moment
            @endforelse
         @else
   </div>
   <div>
         <div>
             Actuellement {{ $demand->candidatures->count() }} candidatures pour cette demande
         </div> 
          @if (Auth::user()->hasApply($demand))
               <div class='rounded bg-blue-200 p-4 w-full'>   
                  <h3 class="text-gray-600">Votre candidature :</h3>      
                  <div>
                     {{ $demand->candidatures->where('owner_id', Auth::user()->id)->first()->content }}
                  </div>
               </div>
         @endif
      @endcan 
   </div>
</div>
      
@endsection