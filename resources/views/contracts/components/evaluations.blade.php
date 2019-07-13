
@if (!$contract->isCancelled() && $contract->isEvaluable())
   <div class="max-w-sm w-full lg:max-w-full  mt-4">       
      <div class="p-4">
            <div class="border-t border-gray-200"></div>
            <div class="mt-4 text-center text-indigo-800 font-bold text-xl">Evaluation</div>
            
            @if ($contract->isEvaluable() && !$user1->hasEvaluated($contract))
            <div class="flex items-center flex-col justify-center mt-2">
                  <div class="text-center text-gray-600 italic p-4 px-40">
                        Laissez une évaluation pour définitivement terminé le contrat.
                  </div>
                  <a class="bg-green-500 hover:bg-green-700 text-white text-sm font-semibold py-2 px-4 rounded-full" 
                  href="#" v-on:click.prevent="$modal.show('evaluate-contract')">
                        Laissez une evaluation à {{ $user2->username}}</a>
            </div>
            
            @elseif($user1->hasEvaluated($contract))
                  <div class="text-center text-gray-600 italic p-4 px-40">
                        Vous avez déja evalué ce contrat. Attendez l'evaluation de {{ $user2->username}}
                  </div> 
            @else
                  
            @endif
            <div>
                  <div class=" text-gray-600 italic p-4 px-8">
                        @foreach ($evaluations as $evaluation)
                              <div class="flex items-center">
                                    <div class="w-1/5">
                                          @if ($evaluation->causer_id == $user1->id)
                                                @include('contracts.components.users_evaluation', ['user' => $user1])
                                          @else
                                                @include('contracts.components.users_evaluation', ['user' => $user2])
                                          @endif

                                    </div>
                                    <div class="w-3/5">
                                          {{$evaluation->comment}}
                                    </div>
                                    <div class="w-1/5 font-bold text-indigo-800 text-center text-xl">
                                          {{$evaluation->note}}
                                    </div>
                              </div>      
                        @endforeach
                  </div> 
            </div>
      </div>
   </div>
@endif


<modal name="evaluate-contract"  classes="bg-white h-auto rounded shadow-lg" style="overflow: visible" height="auto">
      <div class="flex justify-center items-center flex-col py-8 px-6">
         <div class="text-xl font-bold text-indigo-800">Laisser une évauation</div>
         <form id="evaluateContract"  class="w-full" action='{{route('contracts.evaluate', $contract->id)}}' method="POST">
            @csrf
            <div class="w-full px-3 mb-6 md:mb-0">
                  <label class="block tracking-wide text-gray-700 text-xs font-bold mb-2" for="content">
                        Laissez une note
                  </label>
                  <div class="flex items-center justify-center flex-col">
                        <star-rating name="note"></star-rating>
                  </div>      
                  <p class="text-red-500 text-xs italic"></p>
               </div>
            <div class="w-full px-3 mb-6 md:mb-0">
                  <label class="block tracking-wide text-gray-700 text-xs font-bold mb-2" for="content">
                  Ecrivez un message à {{ $user2->username }}
                  </label>
                  <textarea 
                        class="appearance-none block w-full bg-gray-100 text-gray-700 border border-indigo-600 rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-indigo-800" 
                        id="comment" 
                        name="comment" 
                        v-validate="'required|min:20'" 
                        type="text"
                        placeholder="placeholder"></textarea>                  
                  <p class="text-red-500 text-xs italic"></p>
               </div>
               <div class="flex justify-center">
                  <button
                     type="submit"
                     class="bg-indigo-600 hover:bg-indigo-800  focus:outline-none text-white text-sm font-semibold py-2 px-4 rounded-full">
                     Save
                  </button>
               </div>
         </form>
      </div>
</modal>