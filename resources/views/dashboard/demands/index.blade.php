@extends('layouts.dashboard')

@section('main')

<div class="flex mb-4 items-center">
   <h3 class="text-2xl">Mes demandes</h3>
   <a class="bg-indigo-500 hover:bg-indigo-700 text-white text-sm font-semibold py-2 px-4 rounded-full ml-auto" href="">Create demande</a>
</div>

<div class="flex flex-wrap -m-4 mt-4">
   @forelse ($demands as $demand)
      <div class=" w-1/3 p-4">
         @include('dashboard.demands.components.card')
      </div>
   @empty
      Vous n'avez créer aucunes demandes encore
   @endforelse
</div>
    
@endsection