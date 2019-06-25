@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 md:px-0 lg:px-0">
      <div class=" py-8 text-3xl mt-4">
         Demands 
      </div>
      @include('includes.notifications')
         <a class="btn btn-alert" href="">Create demande</a>
         <div class="md:flex flex-wrap -mx-4 mt-4">
            @forelse ($demands as $demand)
            <div class="p-3 md:w-1/3">
               @include('demands.components.card')
            </div>
            @empty
               Vous n'avez créer aucunes demandes encore
            @endforelse
         </div>
</div>
      
@endsection