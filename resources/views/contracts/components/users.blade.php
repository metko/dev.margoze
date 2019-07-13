<div class="flex items-center flex-col mr-4 flex-shrink-0 py-8 px-4 ">
   <img class="w-10 h-10 rounded-full border-4  border-gray-400 @if($user->subscriber )border-indigo-800 @endif" src="{{ $user->avatar }}" alt="Avatar of Jonathan Reinink">
   <div class="font-bold text-indigo-800 mt-2">
      @if ($user->id == auth()->user()->id)
          Vous
      @else
      {{ $user->username }}
      @endif
      
   </div>
   <div class=" text-gray-800 mt-2">{{$user->getAverageNote()}}/5</div>
</div>