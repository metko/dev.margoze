<div class="flex flex-col rounded bg-gray-100 p-3" style="min-height:300px">
   <div>
      <div class="flex">
         <h4 class="text-blue-600">{{ $demand->title}}</h4>
         <div class="text-xs ml-auto pl-2 text-green-700">
            By{{$demand->owner->username}}
            Is :{{$demand->owner->roles->first()->name}}
         </div>
      </div>
      <span class="text-xs text-gray-600"> Valid until {{$demand->valid_until}}<br></span>
      <span class="text-xs text-gray-600"> Staut: {{$demand->status}}<br></span>
      <span class="text-xs text-gray-600"> Nb candidatures: {{ $demand->candidatures->count() }}<br></span>
   </div>
   <p class="text-sm text-gray-800 mt-4 mt-auto mb-auto">{{ str_limit($demand->description, 120 ) }}</p>
   <div class="flex items-center justify-center">
      <div class="flex">
         @if (!$demand->isContracted())
            @can('manage', $demand)
               <button class="btn btn-info text-sm ml-auto">Update</button>
               <button class="btn btn-small text-sm ml-auto">Delete</button>
            @endcan
         @else
            Demand already contracted
         @endif
        
      </div>
      <div>
      {{-- @can('apply', $demand)
         <a class="btn btn-small text-sm ml-auto" href="{{route('demands.apply.show', $demand->id)}}">Postuler</a>
      @endif --}}
      @if (Auth::user()->can("apply", $demand))
         <a class="btn btn-small text-sm ml-auto" href="{{route('demands.apply.show', $demand->id)}}">Postuler</a>
      @elseif(Auth::user()->hasApply($demand))
            En attente de réponsee
      @endif
   </div>
   </div>
   </div>