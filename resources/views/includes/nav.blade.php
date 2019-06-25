<nav class="bg-gray-400  ">
   <div class="flex items-center justify-center h-12 px-2">  
       <a class="font-bold" href="{{ url('/') }}">{{ config('app.name', 'Laravel') }}</a>
      
        <!-- Right Side Of Navbar -->
        <ul class="flex ml-auto">
            <!-- Authentication Links -->
            @guest
                <li class="">
                    <a class="font-light text-gray-800 px-3 py-1" href="{{ route('login') }}">{{ __('Login') }}</a>
                </li>
                @if (Route::has('register'))
                    <li class="">
                        <a class="font-light text-gray-800 px-3 py-1" href="{{ route('register') }}">{{ __('Register') }}</a>
                    </li>
                @endif
            @else
                <li class="flex justify-center items-center">
                    <a class="font-light text-gray-800 px-3 py-1" href="{{ route('demands.index') }}">
                        {{ __('Demands') }}
                    </a>
                </li>
                <li class="flex justify-center items-center">
                    <a class="font-light text-gray-800 px-3 py-1" href="{{ route('contracts.index') }}">
                        {{ __('My Contracts') }}
                    </a>
                </li>
                <li class="flex justify-center items-center">
                    <img class="rounded-full w-8 mr-2" src="{{ Auth::user()->avatar }}" alt="">
                    <a id="" class="text-xs" href="{{ route('users.profile') }}" >{{ Auth::user()->username }} </a> 
                </li>
                <li class="flex justify-center items-center">
                    <a class="font-light text-gray-800 px-3 py-1" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form> 
                </li>
            @endguest
        </ul>
    </div>
</nav>