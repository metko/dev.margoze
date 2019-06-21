@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-title">{{ __('Register') }}</div>

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
    </div>
</div>
@endsection
