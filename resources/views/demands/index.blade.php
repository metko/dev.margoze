@extends('layouts.app')

@section('content')
<div class="hero hidden lg:block bg-blue-primary pt-10">
   <div class="w-full max-w-5xl mx-auto px-4 py-8 md:py-16">
      <div class="title center separator l3 md:l2 white ">Toutes les demandes à la Réunion</div>
      <div class="text-white text-center">Trouvez un job en postulant directement aux annonces posté par les membres</div>
      <div class="text-white text-center font-bold mt-2">{{$totalDemands}} demandes actives</div>
   </div>
</div>

<div class="breadcrumb  border-t p-3 lg:p-5 border-gray-200 ">
   <div class="container mx-auto text-gray-700">
      <a href="/" class="text-blue-primary">Acceuil</a> > 
      demandes
   </div>
</div>

<list-demands
   :sectors="{{$sectors}}"
   :communes="{{$communes}}"
></list-demands>

@endsection