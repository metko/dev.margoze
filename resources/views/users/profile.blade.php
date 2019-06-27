@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 lg:px-0">
      <div class=" py-8 text-3xl mt-4">
         Profile 
      </div>
      <div class="lg:flex">
            @include('includes.notifications')
            <div class="md:w-full lg:w-1/4 rounded-lg bg-gray-100 px-6 py-8">
               @include('dashboard.components.nav')
            </div>
            <div class="md:w-full lg:w-3/4 pl-6">
              @include('includes.notifications')
              <div>
               <h3 class="text-2xl mt-6 mb-6">Mes demandes</h3>
               <a class="btn btn-alert" href="">Create demande</a>
               <div class="flex  -m-4 mt-4">
                  @forelse ($demand as $d)
                     <div class="rounded bg-gray-100 w-1/3 m-4 p-4">
                        <h4 class="text-blue-600">{{ $d->title}}</h4>
                        <span class="text-xs text-gray-600"> Valid until {{$d->valid_until}}<br></span>
                        <span class="text-xs text-gray-600"> Staut: {{$d->status->name}}<br></span>
                        <span class="text-xs text-gray-600"> Nb candidatures: {{ $d->candidatures->count() }}<br></span>
                        <p class="text-sm text-gray-800 mt-4">{{ $d->description}}</p>
                        <div class="flex">
                           <button class="btn btn-small text-sm">Update</button>
                           <a class="text-xs text-red-600" href="">Delete</a>
                           
                        </div>
                        
                     </div>
                  @empty
                     Vous n'avez créer aucunes demandes encore
                  @endforelse
               </div>
              </div>
            </div>
      </div>
  </div>
@endsection