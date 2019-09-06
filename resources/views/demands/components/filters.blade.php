<div class="selected w-full p-5 bg-gray-300">
   <div class="flex mb-3">
         <div class="title text-gray-800 l5 ">Filtres</div>
         <a href="#" class="btn small btn-danger ml-auto">Reset</a>
   </div>
   <ul class="flex flex-wrap -mx-1 ">
      <li class="my-1 inline-block mx-1"><a href="#" 
      class="rounded-full  text-white bg-blue-darken  text-sm  py-1 px-2 ">
      Saint-Denis</a></li>
      <li class="my-1 inline-block mx-1"><a href="#" 
      class="rounded-full text-white bg-blue-darken  text-sm  py-1 px-2 ">
      La providence</a></li>
   </ul>
      
</div>
<div class="overflow-hidden">
   <div class="flex p-5 pb-0 -mx-2 lg:hidden">
      <div class="text-blue-primary px-2 font-title text-xl"><a href="#">Communes</a></div>
      <div class="text-gray-600 px-2 font-title text-xl"><a href="#">Quartier</a></div>
   </div>
   <div class=" overflow-x-scroll py-3  ">
         <div class="title blue l5 separator s-sm hidden lg:block lg:px-5 lg:pb-0">Communes</div>
         <ul class="flex  lg:flex lg:flex-wrap  lg:px-5">
            @php
               $i = 0;
               for($i; $i < 10; $i++){
                  echo '<li class="mx-1 my-1 flex-none lg:m-0 "><a href="#" 
                     class="rounded-full  text-gray-600 bg-gray-100 hover:text-blue-primary lg:bg-transparent lg:border-0 border border-gray-300 py-2 px-3 lg:pr-2 lg:pl-0 lg:py-1 lg:inline-block  ">
                     Saint-Denis</a></li>';
               }   
            @endphp
         </ul>
      </div>
   <div class="overflow-x-scroll lg:py-3 ">
         <div class="title blue l5 separator s-sm hidden lg:block lg:px-5 lg:pb-0">Quartier</div>
         <ul class="flex hidden lg:flex lg:flex-wrap  lg:px-5 ">
            @php
               $i = 0;
               for($i; $i < 10; $i++){
                  echo '<li class="mx-1 my-1 flex-none lg:flex lg:m-0 "><a href="#" 
                     class="rounded-full  text-gray-600 bg-gray-100 hover:text-blue-primary lg:bg-transparent lg:border-0 border border-gray-300 py-2 px-3 lg:pr-2 lg:pl-0 lg:py-1 lg:inline-block  ">
                     Saint-Denis</a></li>';
               }   
            @endphp
         </ul>
      </div>

   <div class="overflow-x-scroll lg:py-3 ">
         <div class="title blue l5 separator s-sm hidden lg:block lg:px-5 lg:pb-0">Secteurs</div>
         <ul class="flex hidden lg:flex lg:flex-wrap  lg:px-5 ">

            @foreach ($sectors as $sector)
            <li class="mx-1 my-1 flex-none lg:flex lg:m-0 ">
                  <a href="#" class="rounded-full lg:rounded text-gray-600 bg-gray-100 hover:text-blue-primary lg:bg-transparent lg:border-gray-600
                   border border-gray-300 py-2 px-3 lg:mr-2 lg:mb-2 lg:px-1 lg:py-0 lg:inline-block  ">
                  {{$sector->name}}</a>
               </li>
            @endforeach
           

         </ul>
      </div>
</div>