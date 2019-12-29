@extends('layouts.dashboard')


@section('main')
<div class="px-10 pt-10 pb-5 ">
      <div class="hero lg:flex  border-b pb-4 border-gray-100 items-center">
         <div class="flex justify-center items-center">
            <div class="flex items-center">
               <img class="w-10 h-10 rounded-full mr-4" src="{{$user->getAvatar()}}" alt="Avatar of Jonathan Reinink">
            </div>
            <div>
               <div class="title  l3 md:l2 gray ">{{ $user->username}}</div>
               <div class="text-gray-700 ">Membre depuis {{ $user->memberSince}}</div>
            </div>
         
         </div>
         <a class="ml-auto btn small h-full" href="{{route('dashboard.profile.edit') }}">
            + Editer mon profil
         </a>
      </div>     
</div>
<div class="px-10 pb-10  border-b border-gray-100 flex -mx-6">
   
   <div class="w-1/3 px-6">
      <div class="text-blue-primary font-bold title l4 mb-4">Infos perso</div>
      <div class="py-1 font-bold">{{$user->first_name}} {{$user->last_name}}</div>
      <div class="py-1 ">{{$user->commune->name}}, {{$user->district->name}}</div>
      <div class="py-1 text-gray-700 italic">{{$user->biography}}</div>
      <div class="py-1 text-gray-700 "><a href="" class="btn small btn-blue">Modifier le mot de passe</a></div>
   </div>
   <div class="w-1/3 px-6">
      <div class="text-blue-primary font-bold title l4 mb-4">Contact</div>
      <div class="py-1 font-bold">{{$user->email}}</div>
      <div class="py-1">{{$user->phone_1}}</div>
      <div class="py-1 text-gray-700 italic">{{$user->adress_1}}</div>
      
   </div>  
   <div class="w-1/3 px-6">
      <div class="text-blue-primary font-bold title l4 mb-4">Infos perso</div>
      <div class="py-1">{{$user->first_name}} {{$user->last_name}}</div>
      <div class="py-1">{{$user->email}}</div>
      <div class="py-1">{{$user->phone_1}}</div>
   </div>  
     
</div>

@endsection