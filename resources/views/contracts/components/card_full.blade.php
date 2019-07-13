<div class=" bg-white rounded overflow-hidden shadow-lg">
   <div class="max-w-sm w-full lg:max-w-full lg:flex">
         <div class="w-full md:w-1/5">
            @include('contracts.components.users', ['user' => $user1])
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
               @include('contracts.components.state')
         </div>
         <div class="w-full md:w-1/5">
            @include('contracts.components.users', ['user' => $user2])
         </div>
    </div>
   @include('contracts.components.evaluations')
</div>

<propose-settings 
   name="propose-settings"
   :user2="{{$user2}}"
   action="{{route('contracts.propose-settings', $contract->id)}}"
   be_done_at="{{Illuminate\Support\Carbon::parse($contract->be_done_at)->format('Y-m-d')}}"
   csrf="{{ csrf_token()}}"
></propose-settings>
