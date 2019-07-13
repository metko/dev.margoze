
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