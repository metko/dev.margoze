<div class=" bg-white  overflow-hidden border-r border-gray-100 p-8">
      <div  class="flex items-center">
            
            <div class="title l4 leading-tight text-gray-800 text-center w-full">
               <a href="{{ route('contracts.show', $contract->id)}}">
                  {{$contract->title}}
               </a>
            </div>
       
      </div>
      <div class="text-center leading-relaxed  mt-4 leading-tight text-gray-700 ">
         {{$contract->content}}
      </div>
      <div class="text-sm flex flex-col text mt-4 leading-tight text-gray-700 ">
         @if ($contract->isCancelled())
         <div class="w-full mb-2 text-center">
               <span class="rounded inline-block bg-red-700 rounded leading-normal p-1 px-2 text-xs text-white  ">Contract annulé</span>
         </div>
         @elseif($contract->isEvaluable())
         <div class="mb-2 text-center">
               <div class="w-full mb-2 text-gray-700 font-bold">
                     Contrat réalisé le 
               </div>
               <span class="inline-block bg-teal-600 text-white  rounded p-1 px-2">{{ \Illuminate\Support\Carbon::parse($contract->be_done_at)->format('d M Y')}}</span>
               <div class="w-full mb-2 text-center">
                  <a href="#" class="inline-block text-orange-600 border border-orange-600   rounded p-1 px-2 mt-2">Laisser une évaluation</a>
               </div>
            </div>
         @elseif($contract->isValidated())
            <div class="mb-2 text-center">
               <div class="w-full mb-2 text-gray-700 font-bold">
                     Contrat validé pour le 
               </div>
               <span class="inline-block bg-teal-600 text-white  rounded p-1 px-2">{{ \Illuminate\Support\Carbon::parse($contract->be_done_at)->format('d M Y')}}</span>
            </div>
            
         @elseif($contract->isConfirmable())
            <div class="mb-2 text-center">
                  <div class="w-full mb-2 text-gray-700 font-bold">
                     Réalisation du contrat prévue le 
               </div>
               <span class="inline-block bg-teal-600 text-white  rounded p-1 px-2">{{ \Illuminate\Support\Carbon::parse($contract->be_done_at)->format('d M Y')}}</span>
            </div>
            
            
         @else
         <div class="w-full mb-2 text-center">
            <a href="{{ route('contracts.show', $contract->id)}}" class="inline-block bg-orange-600 text-white  rounded p-1 px-2">Planifier une date</a>
         </div>
         @endif
         <div class="flex mt-2">
            <div class="mb-2 text-center">
               @if ($contract->isConfirmable())
                  @if($contract->user1->id == $contract->last_propose_by)
                     <span class=" bg-orange-600 text-white  rounded p-1 px-2">En attente de réponse</span>
                  @else
                     <span class=" bg-orange-600 text-white text rounded p-1 px-2">Donner une réponse</span>
                  @endif
               @endif
            </div>
            <span class="rounded  bg-gray-400  p-1 px-2 text-xs text-gray-800 font-bold uppercase ml-auto">{{ $contract->conversation->status->where('read_at', "!=", null)->count()}} messages non lus</span>
         </div>

      </div>
</div>