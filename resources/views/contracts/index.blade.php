@extends('layouts.dashboard')

@section('main')

<div class="flex mb-4 items-center">
   <h3 class="text-2xl">Mes contrats</h3>
</div>

<div class="flex flex-wrap -m-4 mt-4">
   @forelse ($contracts as $contract)
      <div class=" w-1/2 p-4">
         @include('contracts.components.card')
      </div>
   @empty
      Vous n'avez créer aucunes demandes encore
   @endforelse
</div>
    
@endsection