@extends('layouts.dashboard')

@section('main')

<div class="flex mb-4 items-center">
   <h3 class="text-2xl">Mes demandes</h3>
   <a class="bg-indigo-500 hover:bg-indigo-700 text-white text-sm font-semibold py-2 px-4 rounded-full ml-auto" 
   href="{{ route('demands.create')}}">Create demande</a>
</div>

<h4 class="text-indigo-800 font-bold pt-8">Demande en cours</h4>
<div class="flex flex-wrap -m-4 mt-2">
   @forelse ($demands->where('contracted', null)->where('valid_until' ,'>' , now()) as $demand)
      <div class=" w-1/3 p-4">
         @include('dashboard.demands.components.card')
      </div>
   @empty
      <div class=" w-1/3 p-4">
         Aucunes demandes 
      </div>
   @endforelse
</div>

<h4 class="text-indigo-800 font-bold pt-8">Demande contracté</h4>
<div class="flex flex-wrap -m-4 mt-2">
   @forelse ($demands->where('contracted', 1) as $demand)
      <div class=" w-1/3 p-4">
         @include('dashboard.demands.components.card')
      </div>
   @empty
      <div class=" w-1/3 p-4">
         Aucunes demandes 
      </div>
   @endforelse
</div>

<h4 class="text-indigo-800 font-bold pt-8">Demande pas contracté</h4>
<div class="flex flex-wrap -m-4 mt-2">
   @forelse ($demands->where('contracted', null)->where('valid_until', '<', now()) as $demand)
      <div class=" w-1/3 p-4">
         @include('dashboard.demands.components.card')
      </div>
   @empty
   <div class=" w-1/3 p-4">
      Aucunes demandes 
   </div>
      
   @endforelse
</div>
    
@endsection