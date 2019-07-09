<div class=" bg-white rounded overflow-hidden shadow-lg">
   <div class="max-w-sm w-full lg:max-w-full lg:flex">
         <div class="w-full md:w-1/5">
            <div class="flex items-center flex-col mr-4 flex-shrink-0 py-8 px-4 ">
               <img class="w-10 h-10 rounded-full border-4  border-gray-400 @if($user1->subscriber )border-indigo-800 @endif" src="{{ $user1->avatar }}" alt="Avatar of Jonathan Reinink">
               <div class="font-bold text-indigo-800 mt-2">{{ $user1->username }}</div>
               <div class=" text-gray-800 mt-2">4,5/5</div>
            </div>
         </div>
         <div class="w-full md:w-3/5 py-8 px-4">
            <div class="text-center font-bold text-indigo-800 mb-8 text-xl ">
                  <div class="text-center">
                     <span class="inline-block  px-2 py-1 text-xs font-light italic text-gray-700 mr-1">Crée le {{$contract->created_at}}</span>
                  </div>
               {{ $contract->title}}
               <div class="text-center">
                  <span class="inline-block bg-gray-200 rounded-full px-2 py-1 text-xs font-light text-gray-700 mr-1">{{$contract->category->name}}</span>
                  <span class="inline-block bg-gray-200 rounded-full px-2 py-1 text-xs font-light text-gray-700 mr-1">{{$contract->sector->name}}</span>
               </div>
            </div>
            <div class="text-center text-gray-600 border-t border-b py-6 border-gray-200">{{ $contract->content}}</div>
            <div>
               <div class="text-gray-600 text-center mt-4">
                  @if($contract->isCancelled())
                     Contract annulé
                  @elseif($contract->isConfirmable())
                     <div class="mb-2">Réalisation du contrat prévue le</div>
                     <span class="inline-block bg-orange-600 text-white rounded-full px-3 py-2">{{ \Illuminate\Support\Carbon::parse($contract->be_done_at)->format('d M Y')}}</span>
                     <div class="mt-4">
                        @if($user1->id == $contract->last_propose_by)
                     <a class="ml-auto bg-teal-500 hover:bg-teal-700 text-white text-sm font-semibold py-2 px-4 rounded-full ml-auto" href="#">En attente de réponse de {{ $user2->username }}</a>
                        @else 
                           <a class="ml-auto bg-red-500 hover:bg-red-700 text-white text-sm font-semibold py-2 px-4 rounded-full ml-auto" href="#">Refuser</a>
                           <form action="{{ route('contracts.validate', $contract->id)}}" method="post">
                              @csrf
                              <button class="ml-auto bg-indigo-500 hover:bg-indigo-700 text-white text-sm font-semibold py-2 px-4 rounded-full ml-auto" type="submit">Confirmer</button>
                           </form>
                        @endif 
                     </div>
                  @elseif($contract->isValidated())
                        <span class="ml-auto bg-teal-500  text-white text-sm font-semibold py-2 px-4 rounded-full ml-auto"  >
                        Contrat validé pour le {{ \Illuminate\Support\Carbon::parse($contract->be_done_at)->format('d M Y')}}</span>
                  @else
                     <a class="ml-auto bg-teal-500 hover:bg-teal-700 text-white text-sm font-semibold py-2 px-4 rounded-full ml-auto" href="#" 
                     v-on:click.prevent="$modal.show('propose-settings')">
                     Confirmer la date</a>
                  @endif
                  <div class="text-center">
                    <a href="#" v-on:click.prevent="$modal.show('cancel-contract')">Annuler le contrat</a> 
                  </div>
               </div>
            </div>
         </div>
         <div class="w-full md:w-1/5">
            <div class="flex items-center flex-col mr-4 flex-shrink-0 py-8 px-4 ">
               <img class="w-10 h-10 rounded-full border-4  border-gray-400 @if($user2->subscriber )border-indigo-800 @endif" src="{{ $user2->avatar }}" alt="Avatar of Jonathan Reinink">
               <div class="font-bold text-indigo-800 mt-2">{{ $user2->username }}</div>
               <div class=" text-gray-800 mt-2">4,5/5</div>
            </div>
         </div>
    </div>
    <div class="max-w-sm w-full lg:max-w-full  mt-4">       
        
    </div>
</div>
<propose-settings 
   name="propose-settings"
   :user2="{{$user2}}"
   action="{{route('contracts.propose-settings', $contract->id)}}"
   be_done_at="{{Illuminate\Support\Carbon::parse($contract->be_done_at)->format('Y-m-d')}}"
   csrf="{{ csrf_token()}}"
></propose-settings>

<modal name="cancel-contract"  classes="bg-white h-auto rounded shadow-lg" style="overflow: visible" height="auto">
   <div class="flex justify-center items-center flex-col py-8 px-6">
            <form id="cancelContract"  class="w-full" action='{{route('contracts.cancel', $contract->id)}}' method="POST">
               @csrf
               @method('DELETE')
               <div class="flex flex-wrap justify-center -mx-3 mb-6">
                        <div class="w-full px-3 my-4 mb-4 text-gray-700 text-center text-xs ">
                           <div class="text-xl text-gray-700 font-bold mb-2"> Are you sure ?</div>
                           <p>Lorem Ipsum é simplesmente uma simulação de texto da indústria tipográfica e de impressos, e vem sendo utilizado desde o século XVI, quando um impressor desconhecido pegou uma bandeja de tipos e os embaralhou para fazer um livro de modelos de tipos</p>
                        </div>    
                        <button
                           type="submit"
                           class="bg-red-600 hover:bg-red-800 focus:outline-none text-white text-sm font-semibold py-2 px-4 rounded-full">
                           Cancel 
                        </button>        
                  </div>
            </form>
         </div>
   </modal>