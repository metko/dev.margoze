<transition name="fade">
      <div v-if="userMenuIsOpen"  
      class="user-nav-menu absolute z-0 bg-white w-full md:w-1/6 md:right-0 md:rounded shadow" 
      style="top:55px" >
          <ul class="px-4 text-right ">
              <li class="md:pt-3 text-gray-600 hover:text-gray-800">
                <a href="{{ route('dashboard.profile') }}" class="w-full block py-3 md:py-2">Mon profil</a>
              </li>
              <li class="text-gray-600 hover:text-gray-800">
                <a href="{{ route('dashboard.demands') }}" class="w-full block py-3 md:py-2">Mes demandes</a>
              </li>
              <li class=" text-gray-600 hover:text-gray-800">
                <a href="" class="w-full block py-3 md:py-2">Mes offres</a>
              </li>
              <li class="text-gray-600 hover:text-gray-800 ">
                <a href="{{ route('contracts.index') }}" class="w-full block py-3 md:py-2">Mes contrats</a>
              </li>
              <li class=" text-gray-600 hover:text-gray-800">
                <a href="{{ route('dashboard.inbox') }}" class="w-full block py-3 md:py-2">Mes messagerie</a>
              </li>
              <li class="mt-5 pb-3 md:mt-10 md:pb-6 ">
                <form action="{{ route('logout')}}" method="POST">
                    @csrf
                    <button href="" class="md:cursor-pointer py-2 px-2 bg-blue-primary text-white rounded ">Se déconnecter</button>
                </form>
              </li>
            </ul>
      </div>
    </transition>