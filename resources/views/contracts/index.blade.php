@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 md:px-0 lg:px-0">
      <div class=" py-8 text-3xl mt-4">
         Mes contracts 
      </div>
      @include('includes.notifications')
         <div class="md:flex flex-wrap -mx-4 mt-4">
            @forelse ($contracts as $contract)
            <div class="p-3 md:w-1/2">
               @include('contracts.components.card')
            </div>
            @empty
               Vous n'avez aucun contrat encore
            @endforelse
         </div>
</div>
      
@endsection