<div class=" bg-white rounded overflow-hidden shadow-lg">
      <div class="px-6 py-4">
         <div  class="flex items-center ">
            <div class="flex items-center flex-col mr-4 flex-shrink-0 ">
                <img class="w-10 h-10 rounded-full border-4  border-gray-400 @if(Auth::user()->subscriber )border-indigo-800 @endif" src="{{ $demand->owner->avatar }}" alt="Avatar of Jonathan Reinink">
            </div>
            <div class="text-sm text  leading-tight text-gray-800 ">
               <a href="{{$demand->path()}}">
                  @if (Auth()->user()->id == $candidature->owner_id)
                     <strong>Vous</strong> avez laissé votre candidature  <strong>{{ $candidature->created}}</strong>. 
                  @else
                     <strong>{{$candidature->owner->username}}</strong> à laissé sa candidature  <strong>{{ $candidature->created}}</strong>. 
                  @endif
               </a>
            </div>
         </div>
      </div>
   </div>

  