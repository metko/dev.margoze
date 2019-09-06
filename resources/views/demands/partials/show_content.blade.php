<div class="flex w-full ">
      <div class="avatar-user flex flex-col items-center mr-4 lg:mr-10">
            <img class="rounded-full border-yellow-primary border-2 md:border-4  h-8 w-8 lg:h-12 lg:w-12 lg:h-16 lg:w-16"
            src="{{ $demand->owner->avatar }}" alt="">
            <div class="uppercase font-bold text-white tracking-wide text-xxs mt-2">{{ $demand->owner->username }}</div>
      </div>
      <div>
            <div class="text-gray-400 text-sm italic">Mis en ligne il y a {{$demand->created}}</div>
            <div class="title l4 lg:l2 text-white leading-none -mt-2">{{ $demand->title }}</div>
            <div class="flex mt-2">
               <div class="bg-blue-darken text-sm text-white py-1 px-2 inline-block rounded-sm mr-4">{{ $demand->category->name }}</div>
               <div class="btn  btn-inverse small">Fleurimont</div>
            </div>
      </div>
   </div>
   <div class="mt-10 text-white text-center lg:text-xl font-bold bg-blue-darken p-4 lg:p-10 border-2 border-blue-600 rounded ">
      A realiser le <span class="text-yellow-primary">{{$demand->toBeDoneThe}}</span>
   </div>

   <div class="separator w-16 bg-blue-darken h-1 rounded-full my-8 lg:my-12"></div>

   <div>
      <div class="text-yellow-primary text-xl mb-6">Description</div>
      <div class="text-white leading-relaxed">
         <p class="my-8">{{ $demand->description }}. 
         </p>
         <p class="my-8"> {{ $demand->content }} 
         </p>
      </div>
   </div>

   <div class="separator w-16 bg-blue-darken h-1 rounded-full my-8 lg:my-12"></div>

   <div>
         <div class="text-yellow-primary text-xl mb-6">A prévoir</div>
   </div>

   <div class="separator w-16 bg-blue-darken h-1 rounded-full my-8 lg:my-12"></div>

   <div>
         <div class="text-yellow-primary text-xl mb-6">Photos</div>
   </div>
   
   