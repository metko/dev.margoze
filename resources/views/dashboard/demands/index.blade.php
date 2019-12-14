@extends('layouts.dashboard')

@section('main')

<div class="p-10 border-b border-gray-100">
   <div class="flex flex-col lg:flex-row lg:items-center">
      <div>
            <div class="title  l3 md:l2 gray ">Mes demandes</div>
            <div class="text-gray-800 mt-2">Ici, retrouvez les demandes que vous aveez créer ou candidaté t qui se sont concretiser.</div>
      </div>      
      <div class="mt-4 lg:mt-0 lg:ml-auto">
            <a class="btn small h-full" href="{{route('demands.create') }}">
                  + Créer une demande
            </a>
      </div>
   
   </div>

   

   <div class="mt-12">
         <div class="-mx-4  flex flex-wrap">
            @forelse ($demands as $demand)
                  @include('dashboard.demands.components.card')
                  @include('dashboard.demands.components.card')
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
   </div>
</div>

@endsection