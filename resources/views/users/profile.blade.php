   @extends('layouts.app')

   @section('content')
   <div class="hero hidden lg:block bg-blue-primary pt-10">
      <div class="w-full max-w-5xl mx-auto px-4 py-8 md:py-16">
         <div class="flex justify-center">
            <img class="w-16 h-16 rounded-full border-4  border-gray-400 @if(Auth::user()->subscriber )border-indigo-800 @endif" src="{{ $user->getAvatar() }}" alt="Avatar of Jonathan Reinink">
         </div>
         <div class="title center separator   s-sm l3 md:l2 white">{{ $user->username}}</div>
         <div class="text-white text-center">Hello, i'm machin machin</div>
      <div class="text-white text-center font-bold mt-2">  {{$user->getAverageNote()}}/5</div>
      </div>
   </div>
   <div class="container mx-auto px-10 pt-10 pb-5 ">
      {{-- CARDS RECAP --}}
      <div class=" lg:flex  border-b pb-4 border-gray-100 items-center">
         <div class="flex justify-center items-center">
            <div>
               <div class="title  l3 md:l2 gray ">{{ $user->username}}</div>
               <div class="text-gray-700 ">Membre depuis {{ $user->memberSince}}</div>
            </div>

         </div>
         <div class="ml-auto">

            <a class=" btn small h-full" href="{{route('demands.create') }}">
               Contacter
            </a>
            @if($user->id == auth()->user()->id)
               <a class="btn small btn-blue h-full ml-2" href="{{route('demands.create') }}">
                  + Editer mon profil
               </a>
            @endauth
         </div>
      </div> 
      <div class="py-4 flex flex-wrap border-b border-gray-100 ">
         <div class="w-full lg:w-1/3 flex flex-col lg:flex-row items-center p-10 border-b lg:border-b-0 lg:border-r border-gray-100">
            <div class="flex lg:items-center align-center">
                <div class="title l3 font-bold text-blue-primary mr-3 -mt-1 lg:mt-0">
                    {{$demandsCount}} 
                </div>
                <div class="title l4 text-gray-800">demandes actives</div>
            </div>
            @if($user->id == auth()->user()->id)
               <div class="lg:ml-auto mt-5 lg:mt-1 flex-shrink-0">
                  <a class="ml-auto btn small" 
                     href="{{ route('demands.create') }}">+ Créer
                  </a>
               </div>
            @endif
        </div>
    
        <div class="w-full lg:w-1/3 flex flex-col lg:flex-row items-center p-10 border-b lg:border-b-0 lg:border-r border-gray-100">
            <div class="flex lg:items-center align-center">
                <div class="title l3 font-bold text-blue-primary mr-3 -mt-1 lg:mt-0">
                    {{ $candidaturesCount }} 
                </div>
                <div class="title l4 text-gray-800">candidatures en attente</div>
            </div>
            @if($user->id == auth()->user()->id)
               <div class="lg:ml-auto mt-5 lg:mt-1 flex-shrink-0">
                  <a class="ml-auto btn small" 
                     href="{{ route('demands.create') }}">Touf voir</a>
               </div>
            @endif
        </div>
        
        <div class="w-full lg:w-1/3 flex flex-col lg:flex-row items-center p-10 border-b lg:border-b-0  border-gray-100">
            <div class="flex lg:items-center align-center">
                <div class="title l3 font-bold text-blue-primary mr-3 -mt-1 lg:mt-0">
                    {{$contractsCount}}  
                </div>
                <div class="title l4 text-gray-800">contrats en cours</div>
            </div>
            @if($user->id == auth()->user()->id)
                  <div class="lg:ml-auto mt-5 lg:mt-1 flex-shrink-0">
                     <a class="ml-auto btn small" 
                        href="{{ route('demands.create') }}">Touf voir</a>
                  </div>
            @endif
        </div>
      </div>

      {{-- LAST DEMANDS --}}
      <div class="py-6">
         <h3 class="title l3 text-blue-primary">Ses dérniéres demandes</h3>
         <div class="flex -mx-4 flex-wrap mt-6">
               @forelse ($demands as $demand)
                     @include('dashboard.demands.components.card')
               @empty
                  <div class="flex relative flex-col items-center">
                     <svg class="w-24 fill-current text-gray-200  mb-4" enable-background="new 0 0 512 512"  viewBox="0 0 512 512" width="100%" xmlns="http://www.w3.org/2000/svg"><path d="m512 256c0 68.38-26.629 132.667-74.98 181.02-48.353 48.351-112.64 74.98-181.02 74.98-47.869 0-93.723-13.066-133.518-37.482l29.35-29.35c30.91 17.088 66.42 26.832 104.168 26.832 119.103 0 216-96.897 216-216 0-37.748-9.744-73.258-26.833-104.167l29.351-29.35c24.416 39.794 37.482 85.648 37.482 133.517zm-482.858 255.142-28.284-28.284 60.528-60.528c-39.719-46.325-61.386-104.661-61.386-166.33 0-68.38 26.629-132.667 74.98-181.02 48.353-48.351 112.64-74.98 181.02-74.98 61.669 0 120.005 21.667 166.33 61.386l60.528-60.528 28.284 28.284zm60.711-117.28 304.009-304.009c-37.431-31.114-85.497-49.853-137.862-49.853-119.103 0-216 96.897-216 216 0 52.365 18.738 100.431 49.853 137.862z"/></svg>
                     <div class="z-10 flex items-center ml-8 flex-col">
                        <div class="title l4 text-center text-gray-700">Vous n'avez aucune demandes actives actuellement</div>  
                     </div>
                  </div>
            @endforelse

         </div>
         <div class="flex ">
            <a class=" btn small h-full" href="#">
               Voir toutes ses demandes
            </a>
         </div>
      </div>

     
   </div>

   <div>
       {{-- COMMENTS --}}
       <div class="py-6 container mx-auto px-10">
         <h3 class="title l3 text-blue-primary">Ses dérniers commentaires</h3>
         <div class="flex flex-wrap mt-6">
            @include('demands.partials.comment')
            @include('demands.partials.comment')
            @include('demands.partials.comment')
         </div>
      </div>
   </div>

         
   @endsection