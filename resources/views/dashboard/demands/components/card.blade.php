<div class="bg-white rounded overflow-hidden shadow-lg">
   <div class="px-6 py-4">
      <div  class="flex">
         <div class="font-bold text mb-2 text-gray-800 hover:text-indigo-800">
            <a href="{{$demand->path()}}">
               {{str_limit( $demand->title , 50 )}}
            </a>
         </div>
         <div class="ml-2 w-6 h-6  pt-2 -mr-2 cursor-pointer relative">
               <svg xmlns="http://www.w3.org/2000/svg" class="fill-current text-gray-400 hover:text-indigo-800" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 58 58" style="enable-background:new 0 0 58 58;" xml:space="preserve">
                  <circle  cx="29" cy="7" r="7"/>
                  <circle  cx="29" cy="51" r="7"/>
                  <circle  cx="29" cy="29" r="7"/>
               </svg>
               <div class="absolute hidden bg-white rounded overflow-hidden shadow-lg w-36  right-0 top-0 text-right">
                     <a href="#" class="block text-sm text-gray-600 px-3 py-2 pl-10 hover:bg-gray-100">Update</a>
                     <form class="" action="{{ $demand->path('delete')}}" method="POST">
                        @csrf
                        <button type="submit" class="block w-full text-sm text-red-600 px-3 py-2 pl-10 hover:bg-gray-100">Delete</button>   
                     </form>
               </div>
         </div>
      </div>
      <p class="text-gray-700 text-sm">
         {{str_limit( $demand->description , 100 )}}        
      </p>
   </div>
   <div class="px-6 py-4">
      <span class="inline-block bg-gray-200 rounded-full px-2 py-1 text-xs font-light text-gray-700 mr-1 mb-2">
         {{$demand->valid_for}} 
      </span>
      <span class="inline-block bg-gray-200 rounded-full px-2 py-1 text-xs font-light text-gray-700 mr-1 mb-2">{{ $demand->candidatures->count() }} Candidatures</span>
      @if ($demand->contracted)
         <span class="inline-block bg-orange-500 rounded-full px-2 py-1 text-xs font-light text-white mr-1 mb-2">Contracté</span>
      @else
       <span class="inline-block bg-teal-500 rounded-full px-2 py-1 text-xs font-light text-white mr-1 mb-2">En attente de séléction</span>
      @endif
   </div>
</div>