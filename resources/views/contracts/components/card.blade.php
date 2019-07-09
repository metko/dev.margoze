<div class=" bg-white rounded overflow-hidden shadow-lg">
   <div class="px-6 py-4">
      <div  class="flex items-center ">
            <div class="flex items-center flex-col mr-4 flex-shrink-0 ">
               <img class="w-10 h-10 rounded-full border-4  border-gray-400 
                  @if($contract->user2->subscriber )border-indigo-800 @endif" 
                  src="{{ $contract->user2->avatar }}" alt="Avatar of Jonathan Reinink">
               <div class="text-xs text-gray-700">{{$contract->user2->username}}</div>
            </div>
            <div class="font-bold leading-tight text-gray-800 ">
               <a href="{{ route('contracts.show', $contract->id)}}">
                  {{$contract->title}}
               </a>
            </div>
       
      </div>
      <div class="text-sm text mt-4 leading-tight text-gray-700 ">
         {{$contract->content}}
      </div>
      <div class="text-sm flex flex-col text mt-4 leading-tight text-gray-700 ">
         @if ($contract->isCancelled())
         <div class="w-full mb-2 text-center">
               <span class="rounded inline-block bg-red-700 rounded-full leading-normal p-1 px-2 text-xs text-white  ">Contract annulé</span>
         </div>
         @elseif($contract->isValidated())
            <div class="mb-2 text-center">
               <div class="w-full mb-2 text-gray-700 font-bold">
                     Contrat validé pour le 
               </div>
               <span class="inline-block bg-teal-600 text-white text-xs rounded-full p-1 px-2">{{ \Illuminate\Support\Carbon::parse($contract->be_done_at)->format('d M Y')}}</span>
            </div>
            
         @elseif($contract->isConfirmable())
            <div class="mb-2 text-center">
                  <div class="w-full mb-2 text-gray-700 font-bold">
                     Réalisation du contrat prévue le 
               </div>
               <span class="inline-block bg-teal-600 text-white text-xs rounded-full p-1 px-2">{{ \Illuminate\Support\Carbon::parse($contract->be_done_at)->format('d M Y')}}</span>
            </div>
            
            
         @else
         <div class="w-full mb-2 text-center">
            <a href="{{ route('contracts.show', $contract->id)}}" class="inline-block bg-orange-600 text-white text-xs rounded-full p-1 px-2">Planifier une date</a>
         </div>
         @endif
         <div class="flex mt-2">
            <div class="mb-2 text-center">
               @if ($contract->isConfirmable())
                  @if($contract->user1->id == $contract->last_propose_by)
                     <span class=" bg-orange-600 text-white text-xs rounded-full p-1 px-2">En attente de réponse</span>
                  @else
                     <span class=" bg-orange-600 text-white text-xs rounded-full p-1 px-2">Donner une réponse</span>
                  @endif
               @endif
            </div>
            <span class="rounded inline-block bg-gray-400 rounded-full leading-normal p-1 px-2 text-xs text-gray-700  ml-auto">{{ $contract->conversation->status->where('read_at', "!=", null)->count()}} messages non lus</span>
         </div>

      </div>
   </div>
</div>