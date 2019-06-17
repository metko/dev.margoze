@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 lg:px-0">
      <div class=" py-8 text-3xl mt-4">
         Profile 
      </div>
      <div class="lg:flex">
            <div class="md:w-full lg:w-1/4 rounded-lg bg-gray-100 px-6 py-8">
               <ul class="text-center">
                  <li class="flex justify-center mb-6">
                     <img class="rounded-full w-20 h-20 border-8 border-red-600 @if(Auth::user()->subscriber )border-indigo-600 @endif" src="{{ $user->avatar }}" alt="">
                  </li>
                  <li>{{ $user->username }}</li>
                  <li>{{ $user->email }}</li>
                  <li>{{ $user->first_name }}</li>
                  <li>{{ $user->last_name }}</li>
                  <li class="mt-8"><a href="#" class="btn">Update</a></li>
                  <li class="mt-8"><a href="{{ route('plans.index') }}" class="btn btn-info">Subscribe</a></li>
               </ul>
            </div>
            <div class="md:w-full lg:w-3/4 pl-6">
              @include('includes.notifications')

              <h3 class="text-2xl mt-6 mb-6">Mes demandes</h3>
              <a class="btn btn-alert" href="">Create demande</a>

            </div>
      </div>
      
  </div>
@endsection