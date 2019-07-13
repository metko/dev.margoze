<div>
   <div class="text-gray-600 text-center mt-4">
      @if($contract->isCancelled())
         Contract annulé
      @elseif($contract->isEvaluable())
         <div class="mb-2">Contrat réaliser le</div>
         <span class="inline-block bg-orange-600 text-white rounded-full px-3 py-2">{{ \Illuminate\Support\Carbon::parse($contract->be_done_at)->format('d M Y')}}</span>
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

      @if (!$contract->isCancelled() && !$contract->isEvaluable())
         <div class="text-center">
            <a href="#" v-on:click.prevent="$modal.show('cancel-contract')">Annuler le contrat</a> 
            </div>
      @endif
      
   </div>
</div>
