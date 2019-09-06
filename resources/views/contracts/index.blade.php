@extends('layouts.dashboard')

@section('main')
<div class="hero lg:flex items-center border-b border-gray-100 px-10 py-6 md:py-12">
   <div class=" ">
      <div class="title  l3 md:l2 gray ">Mes contrats</div>
      <div class="text-gray-800 mt-2">Ici, retrouvez les demandes que vous aveez créer ou candidaté t qui se sont concretiser.</div>
   </div>
   
</div>



<div class="p-10">
   <div class="flex">
      <h4 class="title l4 text-blue-primary ">Contrats en cours</h4>
      <a href="#" class="btn small ml-auto">Tour voir (21)</a>
   </div>
</div>
<div class="flex flex-row  w-full mt-4 overflow-x-scroll">
   @forelse ($contracts as $contract)
      <div class="w-1/2 flex-shrink-0">
         @include('contracts.components.card')
      </div>
      <div class="w-1/2 flex-shrink-0">
         @include('contracts.components.card')
      </div>
      <div class="w-1/2 flex-shrink-0">
         @include('contracts.components.card')
      </div>
   @empty
      Vous n'avez créer aucunes demandes encore
   @endforelse
</div>

@endsection
{{-- <div class="flex mb-4 items-center">
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
     --}}
