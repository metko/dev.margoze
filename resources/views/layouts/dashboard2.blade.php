@extends('layouts.empty')

@section('content')

<div class="panel flex flex-col md:flex-row md:flex-wrap border-t border-gray-200 h-screen pt-12">

   <div class="menu-demands  w-full h-full lg:w-1/5  bg-blue-primary pb-6">
      @include('components.dashboard.nav2') 
   </div>

   <div class="demands  w-full h-full overflow-x-scroll  lg:w-4/5  lg:border-l lg:border-gray-200">

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