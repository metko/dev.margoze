<div class=" bg-white rounded overflow-hidden shadow-lg">
      <div class="px-6 py-4">
         <div  class="flex items-center mb-3">
            <div class="flex items-center flex-col mr-4 flex-shrink-0 ">
                <img class="w-10 h-10 rounded-full border-4  border-gray-400 @if(Auth::user()->subscriber )border-indigo-800 @endif" src="{{ $demand->owner->avatar }}" alt="Avatar of Jonathan Reinink">
                <div class="text-xs mt-1 text-gray-600">{{ $demand->owner->username }}</div>
            </div>
            <div class="font-bold text mb-2 leading-tight text-gray-800 hover:text-indigo-800">
               <a href="{{$demand->path()}}">
                  {{str_limit( $demand->title , 50 )}}
               </a>
            </div>
         </div>
         <p class="text-gray-700 text-sm">
            {{str_limit( $demand->description , 100 )}}        
         </p>
      </div>
      <div class="px-6 py-4 flex">
         <div>
            <span class="inline-block bg-gray-200 rounded-full px-2 py-1 text-xs font-light text-gray-700 mr-1">{{$demand->valid_for}} jours restants </span>
            <span class="inline-block bg-gray-200 rounded-full px-2 py-1 text-xs font-light text-gray-700 mr-1">{{ $demand->candidatures->count() }} Candidatures</span>
         </div>
         <div class='ml-auto'>
            @if(auth()->user()->hasApply($demand) )
               <button href="#"
                  class="focus:outline-none outline-none cursor-not-allowed inline-block bg-gray-600  rounded-full px-2 py-1 text-xs font-light text-white mr-1">
                  Déja postulé
               </button>
            @else
               <a href="#"
                  @click.prevent="$modal.show('create-candidature-{{ $demand->id}}')"
                  class="inline-block bg-orange-500 hover:bg-orange-600 rounded-full px-2 py-1 text-xs font-light text-white mr-1">
                  Postuler
               </a>
            @endif
         </div>
      </div>
   </div>

   @if( ! auth()->user()->hasApply($demand) )
      
   @endif

   <create-candidature-modal
      name="create-candidature-{{$demand->id}}"
      :demand="{{$demand}}"
      action="{{route('demands.apply', $demand->id)}}"
   ></create-candidature-modal>
