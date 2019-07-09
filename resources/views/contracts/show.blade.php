@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 md:px-0 lg:px-0">
      <div class=" py-8 text-3xl mt-4">
         Contrats {{ $contract->id }}
      </div> 
      <div class="md:flex flex-wrap -mx-2 mt-4">
            <div class="p-2 md:w-3/4">
                  @include('contracts.components.card_full')
            </div> 
            <div class="p-2 md:w-1/4">
                  <div class="bg-white rounded overflow-hidden shadow-lg">
                         @include('contracts.components.conversations.card')
                  </div>
            </div> 
            
      </div>
</div>
      
@endsection