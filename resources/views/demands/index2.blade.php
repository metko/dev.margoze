@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 md:px-0 lg:px-0">
      <div class=" py-8 leading-none text-3xl mt-4 flex ">
         <h3>Demands </h3>
         <a class="ml-auto bg-indigo-500 hover:bg-indigo-700 text-white text-sm font-semibold py-2 px-4 rounded-full"
         href="{{ route('demands.create')}}">Create demande</a>
      </div>
      <div class="md:flex flex-wrap -mx-2 mt-4">
            @forelse ($demands as $demand)
            <div class="p-2 md:w-1/3">
               <demand-card
                  :auth_user="{{ Auth::user()}}"
                  user_has_apply="{{ auth()->user()->hasApply($demand)  }}"
                  :demand="{{ $demand }}"
                  action_candidature="{{route('demands.apply', $demand->id)}}"
                  demand_path="{{$demand->path()}}"
               ></demand-card>
               {{-- @include('demands.components.card') --}}
            </div>
            @empty
               Vous n'avez créer aucunes demandes encore
            @endforelse
         </div>
         <div class="mt-8 flex justify-center">
               {{ $demands->links() }}
         </div>
         
         
         
</div>
      
@endsection