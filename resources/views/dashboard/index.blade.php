@extends('layouts.dashboard')


@section('main')
<div class="hero lg:flex items-center border-b border-gray-100 px-10 py-6 md:py-12">
    <div class=" ">
       <div class="title  l3 md:l2 gray ">Bonjour {{ $user->first_name}}</div>
       <div class="text-gray-800 mt-2">Membre depuis {{ $user->memberSince}}</div>
    </div>
    <div class="ml-auto btn small h-full">
        Modifier mon profil
    </div>
 </div>

<div class="flex flex-wrap border-b border-gray-100">
   
    <div class="w-1/3 flex items-center p-10 border-r border-gray-100">
        <div class=" flex items-center align-center">
            <div class="title l3  font-bold text-blue-primary  mr-3">
                {{$demandsCount}} 
            </div>
            <div class="title l4 text-gray-800">demandes actives</div>
        </div>
        <div class="ml-auto mt-1">
            <a class="ml-auto btn small" 
                href="{{ route('demands.create') }}">+ Créer</a>
        </div>
    </div>

    <div class="w-1/3 flex items-center p-10 border-r border-gray-100">
        <div class=" flex items-center align-center">
            <div class="title l3  font-bold text-blue-primary  mr-3">
                {{$candidaturesCount}} 
            </div>
            <div class="title l4 text-gray-800">candidatures en attente</div>
        </div>
        <div class="ml-auto mt-1">
            <a class="ml-auto btn small" 
                href="{{ route('demands.create') }}">Touf voir</a>
        </div>
    </div>
    
    <div class="w-1/3 flex items-center p-10">
        <div class=" flex items-center align-center">
            <div class="title l3  font-bold text-blue-primary  mr-3">
                {{$contractsCount}}  
            </div>
            <div class="title l4 text-gray-800">contrats en cours</div>
        </div>
        <div class="ml-auto mt-1">
            <a class="ml-auto btn small" 
                href="{{ route('demands.create') }}">Touf voir</a>
        </div>
    </div>
</div>
@endsection