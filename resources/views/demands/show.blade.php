@extends('layouts.app')

@section('content')
   <div class="bg-blue-primary  lg:min-h-screen pt-10">
      <div class="container mx-auto lg:flex ">
         <div class="content-left w-full lg:w-2/3  py-16 px-6 lg:px-6 xl:p-16">
            @include('demands.partials.show_content')
         </div>
         <div class="content-right w-full  pb-10 lg:w-1/3  lg:px-6 lg:py-16 xl:p-16">
            @include('demands.partials.show_form')
         </div>
      </div>
   </div>

   <div class="my-10 lg:mt-16">
      <div class="container mx-auto">
         <div class="avatar-user flex flex-col items-center lg:mr-10">
               <img class="rounded-full border-yellow-primary border-2 md:border-4  h-20 w-20 "
               src="{{ asset('/img/avatar.jpg') }}" alt="">
               <div class="flex mt-4">
                  <div class="h-3 w-3 bg-yellow-primary mx-3"></div>
                  <div class="h-3 w-3 bg-yellow-primary mx-3"></div>
                  <div class="h-3 w-3 bg-yellow-primary mx-3"></div>
                  <div class="h-3 w-3 bg-yellow-primary mx-3"></div>
                  <div class="h-3 w-3 bg-yellow-primary mx-3"></div>
               </div>
               <div class="font-bold text-blue-primary title l5 tracking-wide mt-1">203 personnes ont évalué Patricia</div>
               <div class="separator w-16 bg-blue-primary h-1 rounded-full my-6 lg:my-16"></div>
         </div>
         <div class="comments   md:mx-auto">
            @include('demands.partials.comment')
            @include('demands.partials.comment')
            @include('demands.partials.comment')
            @include('demands.partials.comment')
         </div>
      </div>
      <div class="mt-16 flex">
         <div class="flex container mx-auto p-4">
               <div class="title l5 lg:l4 text-blue-primary ">Autres demandes de la même categorie</div>
               <a class="ml-auto btn small" href="">Tout voir (145)</a>
         </div>

         
      </div>
      <div class="overflow-x-scroll">
         <div class="flex">
            @include('demands.components.other')
            @include('demands.components.other')
            @include('demands.components.other')
            @include('demands.components.other')
            @include('demands.components.other')
            @include('demands.components.other')
         </div>

      </div>
   </div>

@endsection