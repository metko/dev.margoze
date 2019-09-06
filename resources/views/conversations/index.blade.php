@extends('layouts.dashboard')

@section('main')
{{-- <div class="hero lg:flex items-center border-b border-gray-100  px-10 py-6 md:py-12">
   <div class=" ">
      <div class="title  l3 md:l2 gray ">Messagerie</div>
      <div class="text-gray-800 mt-2">Ici, retrouvez les demandes que vous aveez créer ou candidaté t qui se sont concretiser.</div>
   </div>
</div> --}}


<div class="flex"> 
   <div class="w-auto lg:w-1/4">
         <div class="w-full mt-6 px-3 lg:px-10 mb-4 hidden lg:block">
            <input type="text" placeholder="Rechercher une conversation..."
             class="  rounded-full text-gray-600 py-2 focus:outline-none w-full">
         </div>
         <div class=" px-3 lg:px-10 border-b border-t border-gray-100 py-4 flex items-center hover:bg-gray-100">
               <div class="flex items-center flex-col lg:mr-4 flex-shrink-0 ">
                     <img class="w-10 h-10 rounded-full border-4  border-gray-400 border-indigo-800 " src="{{ $user->avatar }}" alt="Avatar of Jonathan Reinink">
               </div>  
               <div class="hidden lg:block" >
                  <div class="title l5 text-gray-800">
                        Jean
                  </div>
                  <div class="text-xs text-gray-600">
                     En ligne il y a 3 heures
                  </div>
                  
               </div>  
         </div>
         <div class="px-3 lg:px-10 border-b border-t border-gray-100 py-4 flex items-center hover:bg-gray-100">
               <div class="flex items-center flex-col lg:mr-4 flex-shrink-0 ">
                     <img class="w-10 h-10 rounded-full border-4  border-gray-400 border-indigo-800 " src="{{ $user->avatar }}" alt="Avatar of Jonathan Reinink">
               </div>  
               <div class="hidden lg:block" >
                  <div class="title l5 text-gray-800">
                        Jean
                  </div>
                  <div class="text-xs text-gray-600">
                     <span class="font-bold text-gray-700">Jean:</span> Pas de soucis, on se retro...
                  </div>
                  
               </div>  
         </div>
         <div class="px-3 lg:px-10 border-b border-t border-gray-100 py-4 flex items-center hover:bg-gray-100">
               <div class="flex items-center flex-col lg:mr-4 flex-shrink-0 ">
                     <img class="w-10 h-10 rounded-full border-4  border-gray-400 border-indigo-800 " src="{{ $user->avatar }}" alt="Avatar of Jonathan Reinink">
               </div>  
               <div class="hidden lg:block" >
                  <div class="title l5 text-gray-800">
                        Jean
                  </div>
                  <div class="text-xs text-gray-600">
                     <span class="font-bold text-gray-700">Vous:</span> Bonne soirée
                  </div>
                  
               </div>  
         </div>
   </div>
   <div class="w-auto; lg:w-3/4 border-l border-gray-100 flex flex-col">
      <div class="px-10 border-b border-t border-gray-100 py-4 flex items-center ">
            <div class="flex items-center flex-col mr-4 flex-shrink-0 ">
                  <img class="w-10 h-10 rounded-full border-4  border-gray-400 border-indigo-800 " src="{{ $user->avatar }}" alt="Avatar of Jonathan Reinink">
            </div>  
            <div>
               <div class="title l5 text-gray-800">
                  Jean
               </div>
               <div class="text-xs text-gray-600">
                  En ligne il y a 3 heures
               </div>
               
            </div> 
            <div class="ml-auto">
               Paramêtres
            </div> 
      </div>
         <div>
            message
         </div>
         <div class="mt-auto border-t border-gray-100 w-full">
            <form action="" class="w-full flex">
               <textarea class="w-full resize-none h-auto focus:outline-none p-6"
               placeholder="Ecrire un message..."
               name="">

               </textarea>
               <button class="border border-blue-primary h-full text-blue-primary rounded px-4 py-2 mx-6  mt-6 hover:bg-blue-primary hover:text-white">
                  Envoyer
               </button>
            </form>
         </div>
   </div>
</div>

{{-- <div class="lg:flex -mx-4">  
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
     --}}
@endsection