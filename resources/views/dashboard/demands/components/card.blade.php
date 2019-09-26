<div class="w-1/2 flex hover:bg-gray-100 py-8 px-5 rounded"> 
   <div class="flex flex-col mr-5 justify-between">
         <div class="date flex flex-col flex-grow-0 items-center text-gray-600  flex-grow-0">
            <div class=" font-bold title l4 ">{{$demand->validFor}}</div>
            <div class="text-xs uppercase">jours</div>
            <div class="text-xs uppercase">restants</div>
         </div>
         <div class="avatar-user flex flex-col items-center ">
            <img class=" h-10 w-auto"
            src="{{ asset('/img/reunion-nord.svg') }}" alt="">
            <div class="uppercase font-bold text-gray-700 text-xs mt-1">
               <a href="#" class="hover:text-blue-primary">{{ $demand->sector->name }}</a>
            </div>
         </div>
   </div>
   <div>
        
         <div class="flex flex-col justify-around">
            <div class="flex">
               <div class="uppercase font-bold text-gray-700 text-xs  ">
                     {{$demand->category->name}}
                  </div>
               <div class="uppercase font-bold text-gray-700 text-xs ml-4 ">
                  {{$demand->commune->name}}, {{$demand->district->name}}
               </div>
            </div>
            
            <div class="title l4 text-gray-800 mb-2"><a href="{{ $demand->path() }}">{{ $demand->title }}</a></div>
            <div class="description text-sm text-gray-600 mb-2">
               {{ $demand->description}}
            </div>
            <div class="uppercase  text-gray-700 text-xs mt-3">
               <span class="mr-2 border rounded border-blue-primary text-blue-primary px-2 py-1">{{$demand->candidatures->count()}} candidatures</span>
               <a href="#" class="mr-2 border rounded border-blue-primary bg-blue-primary text-white px-2 py-1 hover:bg-blue-darken">Editer</a>
            </div>
         </div>
   </div>
     
</div>


 
      {{-- <span class="btn btn-inverse small auto">En attente de confirmation</span>   
 

      <div class="flex items-center">
         <div class="w-12 pr-3 ">
            <img class="rounded-full border-yellow-primary border-2   h-auto w-full "
            src="{{ asset('/img/avatar.jpg') }}" alt="">
         </div>
         <a href="#" class="uppercase text-xs font-bold lg:l4 hover:text-blue-primary">Robert</a>
      </div>   
 
      <a class="btn small" href="#">Voir le contrat</a>    --}}




