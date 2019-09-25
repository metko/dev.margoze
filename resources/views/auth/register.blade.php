@extends('layouts.app')

@section('content')
<div class="md:flex md:justify-end pt-12">
    <div class=" w-full md:w-3/5 md:max-w-5xl">
        <div class="p-16">
            <div class="title l3 blue separator leading-tight">Dés maintenant, connectez vous à la communauté Margoze</div>
            <form action="{{ route('register')}}" method="POST">
                @csrf
                <div class="mt-6">
                    <div class="title l4 text-gray-800">Identifiants de connexion</div>
                    @if ($errors->any())
                        <div class="w-full ">
                            <div class="w-1/2 mx-auto text-center bg-red-600 p-2 rounded mt-4 text-white ">
                                Merci de corriger les erreurs
                            </div>   
                        </div>
                    @endif
                    <div class="lg:flex mt-6 lg:-mx-3">
                        <div class="w-full lg:w-1/2 lg:px-3 my-8 lg:my-6 relative">
                            <input type="email"  name="email" value="{{ old('email') }}"
                            class="input @error('email') has-error @enderror" placeholder="Email" required>
                            @error('email')
                                <div class="absolute top-0 mt-7 lg:ml-3  text-sm left-0 text-red-400">{{$message}}</div>
                            @enderror
                        </div>    
                    </div>
                    <div class="lg:flex lg:-mx-3">
                            <div class="w-full lg:w-1/2 lg:px-3 my-8 lg:my-6 relative">
                                <input type="password" name="password" value=""
                                class="input @error('password') has-error @enderror" placeholder="Mot de passe" required>
                                @error('password')
                                    <div class="absolute top-0 mt-7 lg:ml-3  text-sm left-0 text-red-400">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="w-full lg:w-1/2 lg:px-3  my-8 lg:my-6 relative">
                                <input type="password" name="password_confirmation" value=""
                                class="input @error('password_confirmation') has-error @enderror" placeholder="Confirmez le mot de passe" required>
                                @error('password_confirmation')
                                    <div class="absolute top-0 mt-7 lg:ml-3  text-sm left-0 text-red-400">{{$message}}</div>
                                @enderror
                            </div>
                    </div>
                </div>
                <div class="mt-6">
                    
                    <div class="title l4 text-gray-800">Informations personnelles</div>

                    <div class="lg:flex  lg:-mx-3">
                        <div class="w-full lg:w-1/2 lg:px-3  my-8 lg:my-6 relative">
                            <input type="text" name="first_name" class="input @error('first_name') has-error @enderror" value="{{ old('first_name') }}"
                             placeholder="Prénom" required id="first_name">
                            @error('first_name')
                                <div class="absolute top-0 mt-7 lg:ml-3  text-sm left-0 text-red-400">{{$message}}</div>
                            @enderror
                        </div>
                        <div class="w-full lg:w-1/2 lg:px-3  my-8 lg:my-6 relative">
                            <input type="text" name="last_name"class="input @error('last_name') has-error @enderror" value="{{ old('last_name') }}"
                            placeholder="Nom" required>
                            @error('last_name')
                                <div class="absolute top-0 mt-7 lg:ml-3  text-sm left-0 text-red-400">{{$message}}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="lg:flex mt-12 lg:mt-0 lg:-mx-3">
                        <div class="w-full lg:w-1/2 lg:px-3  my-8 lg:my-6 relative">
                            {{-- <input type="text" class="input" placeholder="Commune" required> --}}
                            <form-select  placeholder="Communes" name="commune_id"
                                v-on:update-data-select="updateDataSelectDistrict"
                                old-selected="{{ old('commune_id') }}"
                                :data="{{ $communes }}"/></form-select>
                            @error('commune_id')
                                <div class="absolute top-0 mt-7 lg:ml-3  text-sm left-0 text-red-400">{{$message}}</div>
                            @enderror
                            
                            
                        </div>
                        <div class="w-full lg:w-1/2 lg:px-3  my-8 lg:my-6 relative">
                            {{-- <input type="text" class="input" placeholder="Quartier" required> --}}
                            <form-select placeholder="-" name="district_id"
                            ref="districtSelectComponent"
                            old-selected="{{ old('district_id') }}"/></form-select>
                            @error('district_id')
                                <div class="absolute top-0 mt-7 lg:ml-3  text-sm left-0 text-red-400">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="lg:flex mt-12 lg:mt-0 lg:-mx-3">
                        <div class="w-full lg:w-1/2 lg:px-3  my-8 lg:my-6 relative">
                        <input type="date" name="date_of_birth" class="input" placeholder="Date de naissance" svalue="{{ old('date_of_birth')}}" required>
                            @error('date_of_birth')
                                <div class="absolute top-0 mt-7 lg:ml-3  text-sm left-0 text-red-400">{{$message}}</div>
                            @enderror
                        </div>
                        <div class="w-full lg:w-1/2 lg:px-3  my-8 lg:my-6 relative">
                            <input type="text" name="phone_1" class="input" value="{{ old('phone_1') }}"
                            placeholder="Portable" required>
                            @error('phone_1')
                                <div class="absolute top-0 mt-7 lg:ml-3  text-sm left-0 text-red-400">{{$message}}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="lg:flex lg:-mx-3 mt-6">
                        <button class="btn lg:mx-3">Créer un compte</button>
                    </div>
                </div>
            </form>
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
