<tr class="border-b border-gray-100"> 
   <td class="py-3">
         <div class="date flex flex-col  items-center text-gray-600 mr-5 flex-grow-0">
            <div class=" font-bold title l4 ">17</div>
            <div class="text-xs uppercase">juin</div>
            <div class="font-bold title l6 ">2019</div>
      </div>
   </td>
   <td class="py-3">
      <a href="{{$demand->path()}}" class="title l5 text-gray-800 hover:text-blue-primary">
         {{ $demand->title }}
      </a>
   </td> 
   <td class="py-3">
      <span class="btn btn-inverse small auto">En attente de confirmation</span>   
   </td> 
   <td class="py-3">
      <div class="flex items-center">
         <div class="w-12 pr-3 ">
            <img class="rounded-full border-yellow-primary border-2   h-auto w-full "
            src="{{ asset('/img/avatar.jpg') }}" alt="">
         </div>
         <a href="#" class="uppercase text-xs font-bold lg:l4 hover:text-blue-primary">Robert</a>
      </div>   
   </td> 
   <td class="py-3">
      <a class="btn small" href="#">Voir le contrat</a>   
   </td> 
</tr>



