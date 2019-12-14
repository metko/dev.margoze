
@extends('layouts.dashboard')


@section('main')
<div class="hero lg:flex items-center border-b border-gray-100 px-10 py-6 md:py-12">
    <div class=" ">
       <div class="title  l3 md:l2 gray ">Bonjour {{ $user->first_name}}</div>
       <div class="text-gray-800 mt-2">Membre depuis {{ $user->memberSince}}</div>
    </div>
    <div class="ml-auto h-full mt-4 lg:mt-0">
            <a href="#" class="btn small">Modifier mon profil</a> 
    </div>
 </div>

<div class="flex flex-col lg:flex-row flex-wrap  border-b border-gray-100">
   
    <div class="w-full lg:w-1/3 flex flex-col lg:flex-row items-center p-10 border-b lg:border-b-0 lg:border-r border-gray-100">
        <div class="flex lg:items-center align-center">
            <div class="title l3 font-bold text-blue-primary mr-3 -mt-1 lg:mt-0">
                {{$demandsCount}} 
            </div>
            <div class="title l4 text-gray-800">demandes actives</div>
        </div>
        <div class="lg:ml-auto mt-5 lg:mt-1 flex-shrink-0">
            <a class="ml-auto btn small" 
                href="{{ route('demands.create') }}">+ Créer</a>
        </div>
    </div>

    <div class="w-full lg:w-1/3 flex flex-col lg:flex-row items-center p-10 border-b lg:border-b-0 lg:border-r border-gray-100">
        <div class="flex lg:items-center align-center">
            <div class="title l3 font-bold text-blue-primary mr-3 -mt-1 lg:mt-0">
                {{ $candidaturesCount }} 
            </div>
            <div class="title l4 text-gray-800">candidatures en attente</div>
        </div>
        <div class="lg:ml-auto mt-5 lg:mt-1 flex-shrink-0">
            <a class="ml-auto btn small" 
                href="{{ route('demands.create') }}">Touf voir</a>
        </div>
    </div>
    
    <div class="w-full lg:w-1/3 flex flex-col lg:flex-row items-center p-10 border-b lg:border-b-0 lg:border-r border-gray-100">
        <div class="flex lg:items-center align-center">
            <div class="title l3 font-bold text-blue-primary mr-3 -mt-1 lg:mt-0">
                {{$contractsCount}}  
            </div>
            <div class="title l4 text-gray-800">contrats en cours</div>
        </div>
        <div class="lg:ml-auto mt-5 lg:mt-1 flex-shrink-0">
            <a class="ml-auto btn small" 
                href="{{ route('demands.create') }}">Touf voir</a>
        </div>
    </div>
</div>
@endsection