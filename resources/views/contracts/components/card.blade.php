<div class="flex flex-col rounded bg-gray-100 p-3" style="min-height:300px">
   <div>
      <h4 class="text-blue-600">Contract {{ $contract->contract_id}}</h4>
      <span class="text-xs text-gray-600"> Created le {{$contract->contract_created_at}}<br></span>
   </div>
   <div class="flex items-center justify-center items-center">
      <div class="p-8" >
         {{ $contract->demand_user_username }}
      </div>
      <div class="p-8 text-center">
         <h3 class="mb-4">{{ $contract->demand_title }}</h3>
         <p  class="text-sm">{{ $contract->demand_descr }}</p>
         <p  class="">Date souhaité {{ $contract->demand_be_done_at }}</p>
         <div class="mt-4">
            <a class="btn" href="">Modifier la date</a>
         
         </div>
      </div>
      <div class="p-8">
         {{ $contract->candidature_user_username }}
      </div>
   </div>
</div>