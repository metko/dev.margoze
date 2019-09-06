<div class="flex p-5 lg:p-8 hover:bg-gray-100 border-b border-gray-100">
   <div class="flex-shrink-0">
      <div class="avatar-user flex flex-col items-center mb-4 ">
         <img class="rounded-full border-blue-primary border-2 md:border-4  h-12 w-12 lg:h-16 lg:w-16"
         src="{{ $demand->owner->avatar }}" alt="">
         <div class="uppercase font-bold text-gray-700 text-xs mt-1">{{$demand->owner->username}}</div>
      </div>
      <div class="sector">
         <div class="avatar-user flex flex-col items-center">
            <img class=" h-10 w-auto"
            src="{{ asset('/img/reunion-nord.svg') }}" alt="">
            <div class="uppercase font-bold text-gray-700 text-xs mt-1">
               <a href="#" class="hover:text-blue-primary">{{ $demand->sector->name }}</a>
            </div>
         </div>
      </div>
   </div>
   <div class=" ml-6 flex flex-col justify-around">
      <div class="uppercase font-bold text-gray-700 text-xs  ">
         <a href="#" class='hover:text-blue-primary'>{{$demand->category->name}}</a> - sub categ</div>
      <div class="title l4 text-gray-800 mb-2"><a href="{{ $demand->path() }}">{{ $demand->title }}</a></div>
   <div class="description text-sm text-gray-600 mb-2">{{ $demand->description}}</div>
      <div class="uppercase  text-gray-700 text-xs ">
         <span class="mr-2 border rounded border-blue-primary bg-blue-primary text-white px-2 py-1">{{$demand->location}}</span>
         <span class="mr-2 border rounded border-blue-primary text-blue-primary px-2 py-1">{{$demand->candidatures->count()}} candidatures</span>
         <span class="mr-0 border rounded border-gray-600 px-2 py-1">{{$demand->validFor}} jours restants</span></div>
   </div>
</div>