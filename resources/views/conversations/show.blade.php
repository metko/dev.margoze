@extends('layouts.dashboard')

@section('main')

<div class="flex mb-4 items-center">
   <h3 class="text-2xl">{{$conversation->subject }}</h3>
</div>

<div class="lg:flex -mx-4">  
   <div class="w-full  px-4">
      <div class="bg-white shadow w-full ">
         <div class="flex p-4">
            @foreach ($conversation->participants as $user)
               <img class="w-10 h-10 rounded-full border-4  border-gray-400 @if($user->subscriber )border-indigo-800 @endif" src="{{ $user->avatar }}" alt="Avatar of Jonathan Reinink">
            @endforeach
         </div>
     
         <div  class="messagebox border-t border-gray-200 p-4 py-8 h-64 overflow-scroll">
               @forelse ($messages as $message)

                     <div class="mt-4 w-auto @if($message->owner_id == Auth::user()->id) text-right @else text-left @endif ">
                        <span class="rounded inline-block rounded-full leading-normal p-2 text-xs text-white  @if($message->owner_id == Auth::user()->id) bg-indigo-500 @else bg-orange-500 @endif ">{{ $message->message}}</span>
                        <span class="text-xs block text-gray-600 @if($message->owner_id == Auth::user()->id) mr-2  @else ml-2  @endif pt-1">Vu il y 38 minutes</span>
                     </div>
               @empty
                   Aucun messages pour le moments
               @endforelse   
         </div>

         <div class=" border-t border-gray-200  ">
               <form action="{{ route('dashboard.messages.store', $conversation->id)  }}"  method="post" class=''>
                  @csrf
                  <textarea name="message" 
                     class="w-full bg-gray-100 p-6   text-gray-800 text-normal h-20 w-full resizable focus:outline-none" 
                     id="message" cols="30" rows="10" >  
                  </textarea>  
                  <div class="bg-white p-4 flex">
                        <button class="ml-auto bg-indigo-500 hover:bg-indigo-700 text-white text-sm font-semibold py-2 px-4 rounded-full ml-auto" type="submit">
                           Envoyer</button>
                  </div> 
               </form>   
         </div>
      </div>
     
   </div>
</div>
    
@endsection