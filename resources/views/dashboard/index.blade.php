@extends('layouts.dashboard')


@section('main')
<div class="flex mb-4 items-center">
    <h3 class="text-2xl">Dashboard</h3>
</div>

<div class="flex flex-wrap -m-4 mt-4">
    <div class=" w-1/3 p-4">
        <div class="bg-white rounded overflow-hidden shadow-lg">
            <div class="px-6 pt-4 flex items-center">
                <div class="font-bold text-indigo-800 text-xl mr-3">
                    {{$demandsCount}} 
                </div>
                <div >demandes actives</div>
            </div>
            <div class="px-6 pb-4 pt-4 ">
                <a class="ml-auto bg-indigo-500 hover:bg-indigo-700 text-white text-xs font-semibold py-2 px-4 rounded-full ml-auto" 
                    href="{{ route('demands.create') }}">Créer une demande  </a>

            </div>
        </div>
    </div>

    <div class=" w-1/3 p-4">
        <div class="bg-white rounded overflow-hidden shadow-lg">
            <div class="px-6 pt-4 flex items-center">
                <div class="font-bold text-indigo-800 text-xl mr-3">
                    {{$candidaturesCount}} 
                </div>
                <div >candidatures en attente</div>
                
            </div>
            <div class="px-6 pb-4 pt-4 ">
                <a class="ml-auto bg-indigo-500 hover:bg-indigo-700 text-white text-xs font-semibold py-2 px-4 rounded-full ml-auto" href="">Voir toutes les demandes</a>

            </div>
        </div>
    </div>

    <div class=" w-1/3 p-4">
        <div class="bg-white rounded overflow-hidden shadow-lg">
            <div class="px-6 pt-4 flex items-center">
                <div class="font-bold text-indigo-800 text-xl mr-3">
                    {{$contractsCount}} 
                </div>
                <div>Contrats en cours</div>
                
            </div>
            <div class="px-6 pb-4 pt-4 ">
                <a class="ml-auto bg-indigo-500 hover:bg-indigo-700 text-white text-xs font-semibold py-2 px-4 rounded-full ml-auto" href="">Voir toutes les demandes</a>

            </div>
        </div>
    </div>
</div>
@endsection