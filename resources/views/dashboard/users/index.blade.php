@extends('layouts.dashboard')


@section('main')
<div class="flex mb-4 items-center">
    <h3 class="text-2xl">Mon profile</h3>
    <a class="ml-auto bg-indigo-500 hover:bg-indigo-700 text-white text-sm font-semibold py-2 px-4 rounded-full ml-auto" href="">Modifier</a>

</div>

<div class="flex flex-wrap -m-4 mt-4">
   <div class="p-4 w-2/3">
      <div class=" bg-white rounded  rounded-b-none overflow-hidden shadow-lg">
         <div class="px-6 py-4 text-gray-700">
            <div class="text-xl text-indigo-800">
               <strong>Informations</strong>
               <div class="border-t border-gray-200 my-4"></div>
            </div>
            <div class="">
                  <div class="flex flex-col items-center p-4 ">
                     <img class="flex-shrink-0 w-20 h-20 rounded-full  border-4  border-gray-400 @if(Auth::user()->subscriber )border-indigo-800 @endif" src="{{ $user->avatar }}" alt="Avatar of Jonathan Reinink">
                     <a class="bg-indigo-500 hover:bg-indigo-700 text-white text-sm font-semibold py-2 px-4 rounded-full mt-4" href="">Update avatar</a>
                  </div>
                  <div class="border-t border-gray-200 my-4"></div>
                  <div class="-mx-2 flex flex-wrap">
                     <div class="px-2 w-1/2">
                        <div class="my-1"><strong>Username:</strong> {{ $user->username}}</div>
                        <div class="my-1"><strong>Email:</strong> {{ $user->email}}</div>
                     </div>
                     <div class="px-2 w-1/2">
                        <div class="my-1"><strong>Nom:</strong> {{ $user->first_name}}</div>
                        <div class="my-1"><strong>Prénom:</strong> {{ $user->last_name}}</div>
                     </div>
                  </div>
                  <div class="-mx-2 flex flex-wrap">
                     <div class="px-2 w-1/2">
                        <div class="my-1"><strong>Télephone:</strong> {{ $user->phone_1}}</div>
                        <div class="my-1"><strong>Adresse:</strong> {{ $user->adress_1}}</div>
                        <div class="my-1"><strong>Code postal:</strong> {{ $user->postal}}</div>
                     </div>
                     <div class="px-2 w-1/2">
                        <div class="my-1"><strong>Teléphone 2:</strong> {{ $user->phone_2}}</div>
                        <div class="my-1"><strong>Adresse 2:</strong> {{ $user->adress_2}}</div>
                        <div class="my-1"><strong>Ville:</strong> {{ $user->city}}</div>
                        <div class="my-1"><strong>Secteur:</strong> {{ $user->sector}}</div>
                     </div>
                  </div>
                  <div class="border-t border-gray-200 my-4"></div>
                  <div class="text-sm">{{$user->biography}}</div>
                  <div class="border-t border-gray-200 my-4"></div>
                  <div class="-mx-2 flex flex-wrap">
                     <div class="px-2 w-1/2">
                        <div class="my-1"><strong>Vehiculable:</strong> Oui</div>
                        
                     </div>
                     <div class="px-2 w-1/2">
                        <div class="my-1"><strong>Spécialité:</strong> Informatique, jardinnage</div>
                     </div>
                  </div>
                  <div class="border-t border-gray-200 my-4"></div>
                  <div class="-mx-2 flex flex-wrap">
                     <div class="px-2 w-1/2">
                        <div class="my-1"><strong>Nombre de contrat réalisé:</strong> {{$contractsCount}}</div>
                        <div class="my-1"><strong>Nombre de demande posté:</strong> {{$demandsCount}}</div>
                        <div class="my-1"><strong>Nombre de candidature posté:</strong> {{$candidaturesCount}}</div>    
                     </div>
                     <div class="px-2 w-1/2">
                        <div class="my-1"><strong>Note global:</strong> {{$contractsCount}}</div>
                        <div class="my-1"><strong>Nombre de commentairte recu:</strong> {{$contractsCount}}</div>
                        <div class="my-1"><strong>Nombre de commentairte laissé:</strong> {{$contractsCount}}</div>
                       
                     </div>
                  </div>
            </div>
         </div>
      </div>
   </div>

   <div class="p-4 w-1/3">
      <div class=" bg-white rounded  rounded-b-none overflow-hidden shadow-lg">
         <div class="px-6 py-4 text-indigo-800 text-xl">
            <strong>Activity</strong>
            <div class="border-t border-gray-200 my-4"></div>
         </div>
      </div>
   </div>
      
</div>
@endsection