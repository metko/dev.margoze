@extends('layouts.app')

@section('content')
<div class="md:flex md:justify-end pt-12">
    <div class=" w-full md:w-3/5 md:max-w-5xl">
        <div class="p-16">
            <div class="title l3 blue separator leading-tight">Dés maintenant, connectez vous à la communauté Margoze</div>
            <register-form 
                register-url="{{ route('register') }}"
            ></register-form>
        </div>
        
    </div>

    <div class="w-full md:w-2/5 self-stretch">
        <div class="bg-blue-primary w-full h-1/2">
            <div class="p-16">
                <div class="title l3 white separator leading-tight">
                    Professionnel ? <br>Nous avons des options specifiques pour vous
                </div>
                <div>
                    <a href="#" class="btn btn-white inline-block">Je suis un professionnel </a>
                </div>
            </div>
        </div>
        <div class="bg-blue-darken w-full h-1/2">
            <div class="p-16">
                <div class="title l3 white separator leading-tight">Découvrez Margoze</div>
                <div class="text-white leading-relaxed mb-6">
                    Avec Margoze, accédez à des prestataire particulier capable de répondre à vos besoin plus rapidement que des entreprises.
                    <br>
                    <br>
                    N’attendez plus, engagez vous !
                </div>
                <div>
                    <a href="#" class="btn btn-inverse inline-block">En savoir plus</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- 
<div class="card-body">
        <form method="POST" action="{{ route('register') }}">
            @csrf

            @include('components.input',['name' => 'username','type' => 'text','label' => __('Username') ])
            @include('components.input',['name' => 'email', 'type' => 'email', 'label' => __('E-Mail Address') ])
            @include('components.input',['name' => 'password', 'type' => 'password', 'label' => __('Passsword') ])

            <div class="form-control">
                <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
                <input id="password-confirm" type="password" class="form-input" name="password_confirmation" required autocomplete="new-password">
            </div>

            <div class="text-center">
                <button type="submit" class="btn ">
                    {{ __('Register') }}
                </button>
            </div>
            
            <div class="social-icon flex justify-center">
                    <a href="{{ route('auth.provider.login', 'facebook') }}" class="p-2 text-gray-700 hover:text-gray-900"><i class="fa fa-facebook-f"></i></a>
                    <a href="{{ route('auth.provider.login', 'twitter') }}" class="p-2 text-gray-700 hover:text-gray-900"><i class="fa fa-twitter"></i></a>
                    <a href="{{ route('auth.provider.login', 'google') }}" class="p-2 text-gray-700 hover:text-gray-900"><i class="fa fa-google"></i></a>
            </div>
        </form>
    </div> --}}
