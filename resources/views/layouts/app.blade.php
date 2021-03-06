<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" >
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

  
    <link rel="stylesheet" href="https://use.fontawesome.com/627d535388.css">
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="font-sans text-base w-full min-h-screen m-0">
    <div id="app">
        
        
        @include('includes.nav')
       <div v-on:click="closeMenus()">
           
            <div class="fixed z-50 top-0 right-0 mt-20 mr-4">
                {!! laraflash()->render() !!}
            </div>
            @yield('content')
            @include('components.footer')
        </div>
        
        <search-modal ref="searchModal"></search-modal>
        @guest
            <login-modal ref="loginModal"
                login-url="{{ route('login') }}"
                csrf="{{ csrf_token() }}"
            ></login-modal>
        @endguest
       
        

    </div>

   


    
    <!-- Scripts -->
    
    @stack('scripts')
    <script src="/js/manifest.js"></script>
    <script src="/js/vendor.js"></script>
    <script src="/js/app.js"></script>
    
    
    
</body>
</html>
