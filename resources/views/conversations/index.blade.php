@extends('layouts.dashboard')

@section('main')

<div class="flex mb-4 items-center">
   <h3 class="text-2xl">Mes Messages</h3>
</div>

<div class="lg:flex -mx-4">  
   <div class="w-full  px-4">
         @forelse ($conversations as $conversation)
         <div class="bg-white rounded overflow-hidden shadow-lg">
            <div class="px-6 py-4">
               <div  class="flex">
                  <div class="font-bold text mb-2 text-gray-800 hover:text-indigo-800">
                     <a href="{{ route('dashboard.conversations.show', $conversation->id) }}">
                        {{str_limit( $conversation->subject , 50 )}}
                     </a>
                     <span class="font-mono font-normal text-sm text-gray-600">Avec
                         @foreach ($conversation->participants as $user)
                              @if ($user->id != Auth::user()->id)
                                  {{ $user->username }}
                                  @if ( ! $loop->last)
                                    -
                                 @endif
                              @endif
                         @endforeach
                     </span>
                  </div>
                  <div class="font-bold ml-auto text mb-2 text-gray-800 hover:text-indigo-800">
                     <a href="#">
                        <a class="ml-auto bg-indigo-500 hover:bg-indigo-700 text-white text-sm font-semibold py-2 px-4 rounded-full ml-auto" href="">Ecrire un message</a>

                     </a>
                  </div>
               </div>
               <p class="text-gray-700 text-sm">
                  {{str_limit( $conversation->description , 100 )}}        
               </p>
               <span class="text-gray-700 text-sm">Crée le {{ $conversation->created_at }}</span>
            </div>
            <div class="px-6 py-4">
               <span class="inline-block bg-gray-200 rounded-full px-2 py-1 text-xs font-light text-gray-700 mr-1">{{$conversation->messages_count}} messsages </span>
               <span class="inline-block bg-gray-200 rounded-full px-2 py-1 text-xs font-light text-gray-700 mr-1">{{ $conversation->unread_messages_count }} messages non lus</span>
            </div>
         </div>
         @empty
            Désolé, aucune conversation pour le moment
         @endforelse
   </div>
</div>
    
@endsection