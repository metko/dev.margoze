<transition name="fade">
      <div v-if="middleMenuIsOpen"  v-bind:class="{'block-imp': middleMenuIsOpen }" 
      class="user-nav-menu absolute z-0 bg-white w-full shadow md:hidden-imp" 
      style="top:55px" >
          <ul class="px-4 text-left">
                <a href="#" class="w-full block py-3 md:py-2 ">Demandes</a>
              </li>
              <li class="text-gray-600 hover:text-gray-800">
                <a href="#" class="w-full block py-3 md:py-2 ">Offres</a>
              </li>
              <li class="text-gray-600 hover:text-gray-800">
                <a href="#" class="w-full block py-3 md:py-2 ">Prix</a>
              </li>
            </ul>
      </div>
    </transition>