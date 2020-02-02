<nav class="bg-white fixed shadow px-3 w-full z-10 top-0 z-40" style="height:56px">
    <div class="flex items-center justify-between h-full" >

        <div class="left-nav h-full {{! Request::is('/') ?? 'w-full' }} md:w-auto overflow-hidden flex flex-1" >
                <div class="flex flex-shrink-0 md:mr-2 h-full">
                    <div class="burger md:hidden  " @click.prevent="openMiddleMenu()">
                        <span></span>
                      </div>
                      <a href="/" class="ml-2 md:ml-0 flex items-center  flex-shrink-0"  >
                        <div class="flex-shrink-0 w-auto"  style='height:25px;'>
                            <img src="{{asset('img/logo-primary.png')}}"  class="max-h-full flex-shrink-0 " title="Logo Margoze" alt="logo margoze">
                        </div>
                        <div class="font-title hidden lg:block text-xl pl-1 text-blue-primary">Margoze</div>
                      </a>
                </div>
                
               
                  <div class="nav-search flex items-center ml-3 md:ml-0 relative w-full md:w-auto h-full
                  justify-end  md:justify-start hover:pointer">
                      <div v-on:click.prevent="openSearchModal" class=" px-4 flex items-center">
                          @include('components.icons.loupe')
                      </div> 
                  </div> 
            
                <div class="main-menu flex relative w-auto hidden md:flex">
                    <ul class="flex items-center h-full">
                      <li class="h-full flex items-center">
                        <a href="{{ route('demands.index') }}" class="px-4 text-gray-600 hover:text-blue-primary">Demandes</a>
                      </li>
                      <li class="h-full flex items-center">
                          <a href="#" class="px-4 text-gray-600 hover:text-blue-primary">Offres</a>
                      </li>
                      <li class="h-full flex items-center">
                          <a href="{{ route('plans.index') }}" class="px-4 text-gray-600 hover:text-blue-primary">Abonnements</a>
                      </li>
                    </ul>
                 </div>
        </div>

        <div class="right-nav flew-wrap">

          @guest
              <div class="{{ Request::is('/') ? '' : 'hidden md:block'}}">
                <a href="{{ route('register')}}" class="text-base mr-2 text-blue-primary">Créer un compte</a>
                <a href="{{ route('login')}}" 
                v-on:click.prevent="openLoginModal()"
                class="text-base rounded shadow px-2 py-1 text-white bg-blue-primary active:top-1 active:bg-blue-darken active:relative">Se connecter</a>
              </div>
              
            @else

            <div class="flex items-center flex-wrap">
              <div class=" mr-2 h-full hidden md:flex">
                
                <div class="flex items-center nav-notifs px-3">
                    @include('components.icons.notifications')
                </div>
                <div class="flex items-center nav-notifs px-3">
                <a href="{{ route('dashboard.index')}}">
                    @include('components.icons.dashboard')
                  </a>
                </div>
              </div> 

              <div v-on:click="openUserMenu()" class="user-nav flex items-center h-full text-right" style="height:56px">
                  <img class="flex-shrink-0 w-10 h-10 rounded-full mr-2 border-2 ml-3 border-yellow-primary @if(Auth::user()->subscriber )border-indigo-800 @endif" src="{{ Auth::user()->getAvatar() }}" alt="Avatar of Jonathan Reinink">
                  <div class="hidden md:block text-blue-primary font-body text-sm font-semibold mr-2">{{Auth::user()->username}}</div>
                  <div class="">
                      @include('components.icons.carret')
                  </div>
              </div>

            </div>
          @endguest
        </div>
    </div>
  </nav>


  @include('components.menuMobile')
  @if (Auth()->user())
    @include('components.userMenu')
  @endif

  

