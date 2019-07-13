@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 lg:px-0">
   <div class=" py-8 text-3xl leading-tight">
      Profile {{ $user->username}}
   </div>
   <div class="lg:flex -mx-4">  
      
      <div class="md:w-full px-4">
         @include('includes.notifications')
         {{$user->getAverageNote()}}/5
      </div>
   </div>
</div>
      
@endsection