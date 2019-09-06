@extends('layouts.app')

@section('content')
<div class="bg-blue-primary flex pt-32 lg:pt-48">
    <div class="container mx-auto flex ">
        <div class="p-4  mb-16 md:w-3/5 lg:w-2/5 ">
            <div class="hero-title w-4/5 md:w-full  text-4xl lg:text-5xl  leading-tight  text-yellow-primary font-title">
                Trouvez le prestataire idéal pour à coté de chez vous.
            </div>
            <div class="mt-0 md:mt-16 relative">
                <input type="text" 
                class="w-full focus:outline-none bg-transparent border-b text-light border-white text-2xl text-white pt-6 pb-2 ">
                <div class="bg-red-200 hidden absolute h-10 w-full"></div>
            </div>
        </div>
        <div class="md:w-2/5 hidden md:flex ml-auto items-end">
            <img src="{{ asset('img/hero.png') }}"  alt="">
        </div>
    </div>
</div>
@endsection
