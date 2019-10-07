@extends('layouts.app')

@section('content')
<div class="bg-white flex pt-20 py-10 lg:pt-32 lg:pb-0 border-b border-gray-100">
    <div class="container mx-auto lg:flex lg:items-center flex-wrap px-3">
        <div class="w-full lg:w-3/5">
            <div class="title l3  text-4xl lg:text-5xl  leading-tight  text-gray-600 w-full lg:w-3/4">
                Trouvez le <span class="text-blue-primary">prestataire idéal</span> pour vos <span class="text-blue-primary ">petites oeuvres</span>
            </div>
            <div class="mt-5">
                <search-input-home></search-input-home>
               
            </div>
            <div class="mt-5 text-gray-700">
                <div>Service le plus populaire cette semaine : <a href="#" class="btn small mr-2 inline-block">Bricolage</a>
                    <a href="#" class="btn small mr-2 inline-block">Prostitution</a><a href="#" class="btn small inline-block ">Jardinnage</a></div>
               
            </div>
        </div>
        <div class="w-full hidden lg:block lg:w-2/5">
            <img src="{{ asset('img/hero.png') }}"  class="w-5/6" alt="">
        </div>
    </div>
</div>



<div class="container mx-auto py-10 px-3">
    <div class="title l3  text-4xl lg:text-5xl  leading-tight  text-gray-700 w-full lg:w-3/4">De quel service vous voulez de l’aide ? </div>
    <div class="-mx-3 mt-10 flex flex-wrap">
        @foreach ($categories as $categ)
            <div class="px-3 w-full md:w-1/2 lg:w-1/4 mb-4">
                <a href="#" class="bg-categ flex justify-center items-end rounded overflow-hidden" style="background-image: url('https://picsum.photos/id/1025/250/250') ">
                    <div class="categ-overlay w-full  relative">
                        <div class="px-3 title l4 text-white pt-4 z-10 relative">{{$categ->name}}</div>
                        <div class="px-3  text-gray-400 z-10 pb-4 relative"> personnes proposent ce service</div>
                    </div>
                </a>
            </div>
        @endforeach
      
    </div>
</div>

<div class="container mx-auto py-10 px-3 mt-10 border-b border-gray-100">
    <div class="flex">
        <div class="w-full lg:w-1/2 mx-auto text-center ">
            <div class="title l3  text-4xl lg:text-5xl  leading-tight  text-blue-primary w-full ">
                Engagez vous et profitez d’une experience sans tracas 
            </div>
            <div class="text-gray-700 w-full mt-10 font-body leading-relaxed md:leading-loose">
                Toute communication est sécurisée - tous les profils et demandes sont vérifiés, les prestataires et clients sont notés et évalués. Tous les echanges sont sécurisé grace a lintégration d’un systeme de messagerie simple et intuitive. 
                Les tarifs sont fixé d’avance. - gérez votre budget et evitez les mauvaises surprises. Les prix sont négocié en avance entre vous et le prestataire.
                Grace a notre large evental, trouvez l’abo qui vs convient,   
            </div>
        </div>
    </div>      
</div>

<div class="container mx-auto pt-10 px-3 ">
    <div class="flex">
        <div class="w-full lg:w-1/2 mx-auto text-center ">
            <div class="title l3  text-4xl lg:text-5xl  leading-tight  text-blue-primary w-full ">
                Module abonements 
            </div>
            <div class="text-gray-700 w-full mt-10 font-body leading-relaxed md:leading-loose">
                    Nos prestations sont vérifiés et encadrés. - tous les utilisateurs sont vérifiés, suivis et évalués pour chaque job rendu afin de vous offrir la meilleur qualité d’experience possible.  
            </div>
            
        </div>
    </div>      
</div>

    
@endsection
