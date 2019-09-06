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

<div class="panel flex flex-col md:flex-row md:flex-wrap border-t border-gray-200 ">

   <div class="menu-demands order-1 w-full md:w-5/5 lg:w-1/5 lg:h-screen-full pb-6">
      @include('demands.components.filters') 
   </div>

   <div class="demands  order-3  md:order-2 w-full  lg:w-3/5  lg:border-l lg:border-gray-200">
      @foreach ($demands as $demand)
         @include('demands.components.card')
      @endforeach
   </div>

   <div class="other-demand order-2 md:order-3 w-full md:w-2/5 lg:w-1/5 lg:border-l lg:border-gray-200 ">
      <div class="bg-blue-primary p-5 pb-8 hidden lg:block">
            <div class="title l5 white center">Besoin d'un service ?</div>
            <div class="w-full text-center mt-4">
               <a href='#' class="btn btn-inverse small inline-block ">Créer votre demande</a>
            </div>
      </div>
      <div class=" p-5 pb-8 hidden border-b border-gray-200 lg:block">
            <div class="title l5 blue separator s-sm">34 demandes en urgences</div>
            <div class="text-blue-primary">
                  Placez vous aussi votre demande à la une en souscrivant à une offre Margoze
            </div>
            <div class="w-full mt-4">
               <a href='#' class="btn  small inline-block ">Voir les offres</a>
            </div>
      </div>
   </div>

</div>


@endsection