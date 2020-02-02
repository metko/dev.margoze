@extends('layouts.app')

@section('content')

<div class="panel px-6 md:px-0 flex flex-col md:flex-row md:flex-wrap border-t border-gray-200 pt-12 overflow-x-hidden">

   <div class="  w-full ">
      @include('components.dashboard.nav') 
   </div>

   <div class="demands  w-full container mx-auto  shadow-lg mb-12 " >

      @include('includes.notifications')
      @yield('main')

   </div>

</div>


{{-- <div class="container mx-auto px-4 lg:px-0">
   <div class=" py-8 text-3xl leading-tight">
      Dashboard 
   </div>
   <div class="lg:flex -mx-4">  
      <div class="w-full lg:w-1/4 px-4">
         @include('dashboard.components.nav')
      </div> 
      <div class="md:w-full lg:w-3/4 px-4">
         @include('includes.notifications')
         @yield('main')
      </div>
   </div>
</div> --}}
@endsection