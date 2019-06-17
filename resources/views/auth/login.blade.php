@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-title">{{ __('Login') }}</div>
        <div class="card-body">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                @include('components.input',[
                    'id' => 'email',
                    'type' => 'email',
                    'label' => __('E-Mail Address') 
                ])

                @include('components.input',[
                    'id' => 'password',
                    'type' => 'passsword',
                    'label' => __('Password') 
                ])


                <div class="text-center flex items-center justify-center mt-2 mb-2">
                    <input class="mr-2" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="text-gray-700" for="remember">
                        {{ __('Remember Me') }}
                    </label>
                </div>

                <div class="">
                    <div class="text-center">
                        <button type="submit" class="btn">
                            {{ __('Login') }}
                        </button>

                    </div>
                    <div class="text-center">
                        @if (Route::has('password.request'))
                            <a class=" text-gray-600 text-xs font-light mt-3 hover:text-gray-800" href="{{ route('password.request') }}">
                                {{ __('Forgot Your Password?') }}
                            </a>
                        @endif
                    </div>
                </div>
                <div class="social-icon flex justify-center">
                        <a href="" class="p-2 text-gray-700 hover:text-gray-900"><i class="fa fa-facebook-f"></i></a>
                        <a href="" class="p-2 text-gray-700 hover:text-gray-900"><i class="fa fa-twitter"></i></a>
                        <a href="" class="p-2 text-gray-700 hover:text-gray-900"><i class="fa fa-google"></i></a>
                </div>
            </form>
        </div>
</div>
@endsection
