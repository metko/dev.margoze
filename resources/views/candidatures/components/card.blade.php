<div class="flex flex-col rounded bg-gray-100 p-3" style="min-height:300px">
      <div>
         <h4 class="text-blue-600">Candidature de {{ $candidature->owner->username}}</h4>
         <span class="text-xs text-gray-600"> Recu le {{$candidature->created_at}}<br></span>
      </div>
      <p class="text-sm text-gray-800 mt-4 mt-auto mb-auto">{{ str_limit($candidature->content ) }}</p>
      <div class="flex items-center justify-center">
         <div class="flex">
            @if ( ! $demand->isContracted())
                  @can('manage', $demand)
                  <form method="POST" action="{{route('demands.contract.candidature', ['demand' => $demand->id, 'candidature' => $candidature->id])}}">
                     @csrf
                     <button type="submit" class="btn btn-info text-sm ml-auto">Select</button>
                  </form>
                  @endcan  
            @else
               Demande already contracted
            @endif
            
            </div>
      </div>
   </div>