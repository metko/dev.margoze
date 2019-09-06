@extends('layouts.dashboard')

@section('main')

{{-- <div class="hero lg:flex items-center border-b border-gray-100 px-10 py-6 md:py-12">
   <div class=" ">
      <div class="title  l3 md:l2 gray ">Mes demandes</div>
      <div class="text-gray-800 mt-2">Vous avez créer 143 demandes depuis votre inscription.</div>
   </div>
   <a class="ml-auto btn small h-full" href="{{ route('demands.create')}}">
         Create demande
   </a>
</div> --}}


<div class="p-10 border-b border-gray-100">
   <h4 class="title l4 text-blue-primary ">0 demandes en cours</h4>
   <div class="flex flex-wrap -m-4 mt-2">
      @forelse ($demands->where('contracted', null)->where('valid_until' ,'>' , now()) as $demand)
         <div class=" w-1/3 p-4">
            @include('dashboard.demands.components.card')
         </div>
      @empty
         <div class="w-full p-4">
            <div class="flex relative ">
                  <svg class="w-24 fill-current text-gray-200" enable-background="new 0 0 512 512"  viewBox="0 0 512 512" width="100%" xmlns="http://www.w3.org/2000/svg"><path d="m512 256c0 68.38-26.629 132.667-74.98 181.02-48.353 48.351-112.64 74.98-181.02 74.98-47.869 0-93.723-13.066-133.518-37.482l29.35-29.35c30.91 17.088 66.42 26.832 104.168 26.832 119.103 0 216-96.897 216-216 0-37.748-9.744-73.258-26.833-104.167l29.351-29.35c24.416 39.794 37.482 85.648 37.482 133.517zm-482.858 255.142-28.284-28.284 60.528-60.528c-39.719-46.325-61.386-104.661-61.386-166.33 0-68.38 26.629-132.667 74.98-181.02 48.353-48.351 112.64-74.98 181.02-74.98 61.669 0 120.005 21.667 166.33 61.386l60.528-60.528 28.284 28.284zm60.711-117.28 304.009-304.009c-37.431-31.114-85.497-49.853-137.862-49.853-119.103 0-216 96.897-216 216 0 52.365 18.738 100.431 49.853 137.862z"/></svg>

                  <div class="z-10 flex justify-center ml-8 flex-col">
                     <div class="text-center text-gray-700">Vous n'avez aucune demandes actives actuellement</div> 
                     <div class="mt-2">
                        <a class=" btn  inline-block" href="#">
                              + Créer une demande
                        </a>
                     </div>
                  </div>
            </div>
         </div>
      @endforelse
   </div>
</div>

<div class="p-10">
   <div class="flex">
      <h4 class="title l4 text-blue-primary ">Mes demandes contractées</h4>
      <a href="#" class="btn small ml-auto">Tour voir (21)</a>
   </div>
   <div class="flex flex-wrap mt-2">
      @if ($demands)
         <table class="w-full">
      @endif

      @forelse ($demands->where('contracted', 1) as $demand)
        
            @include('dashboard.demands.components.card')
            @include('dashboard.demands.components.card')
            @include('dashboard.demands.components.card')
            @include('dashboard.demands.components.card')
            @include('dashboard.demands.components.card')
        
      @empty
         <div class=" w-1/3 p-4">
            Aucunes demandes 
         </div>
      @endforelse

      @if ($demands)
         </table>
      @endif
      </div>
</div>

@endsection