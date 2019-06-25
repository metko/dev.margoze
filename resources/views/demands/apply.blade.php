@extends('layouts.app')

@section('content')
<div class="card">

    <div class="card-title">Apply for demand {{$demand->title}} </div>
        <div class="card-body">
            <form method="POST" action="{{ route('demands.apply', $demand->id) }}">
                @csrf

                @include('components.input',[
                    'name' => 'content',
                    'type' => 'textarea',
                    'label' => __('Message pour le demandeur') 
                ])

                <div class="">
                    <div class="text-center">
                        <button type="submit" class="btn">
                            {{ __('Poster') }}
                        </button>

                    </div>
                    <div class="text-center">
                       
                    </div>
                </div>
            
            </form>
            <div class="mt-4 flex justify-center">
               <a class="btn btn-alert" href="{{ url()->previous() }}">Cancel</a>
            </div>
        </div>
</div>
               
@endsection