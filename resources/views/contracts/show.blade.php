@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 md:px-0 lg:px-0">
      <div class=" py-8 text-3xl mt-4">
         Contrats {{ $contract->id }}
      </div> 
      <div class="md:flex flex-wrap -mx-4 mt-4">
            <div class="p-3 w-full">
                  @include('contracts.components.card')
            </div> 
      </div>

</div>
      
@endsection