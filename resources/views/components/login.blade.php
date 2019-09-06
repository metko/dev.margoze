<transition name="fromTop">

<div v-if="loginModalIsOpen" 
   class="modal full  md:flex md:flex-wrap ">
   <div class="bg-blue-primary w-full md:w-3/5 md:flex md:items-center relative">
         <div class="w-full px-6 py-16 md:px-0 md:w-2/3 max-w-sm mx-auto text-center">

            <h3 class="title l3 white center">Heureux de vous revoir</h3>

            <form action="{{ route('login') }}" method='post' class="flex flex-col py-5 pt-12 pb-3">
               @csrf
               <input type="email" 
                  placeholder="Email"
                  name="email"
                  class="input inverse mb-6">
               <input type="password" 
                  placeholder="Mot de passe"
                  name="password"
                  class="input inverse mb-6">
               <div class="mt-6">
                  <button class="btn btn-inverse">Se connecter</button>
               </div>
            </form>
            <div class="md:absolute w-full md:mb-6 md:bottom-0  md:left-0 md:right-0 text-center md:text-lg text-gray-500 hover:text-white">
               <a href="">J'ai oublier mon mot de passe</a>
            </div>
         </div>
         
   </div>
   <div class="bg-blue-darken w-full md:w-2/5 md:flex md:items-center">
      <div class="w-full px-6 py-16 md:px-0 md:w-3/4 mx-auto">
         <h4 class="title l3 white center">Connecter vous avec vos autres compte</h4>
         <div class="mx-auto text-center">
            <div action="" class="flex flex-col w-48 mx-auto py-5 pt-12">
               <a href="" class="btn btn-white mb-6"><span class="btn-icon icon-facebook"></span>Facebook</a>
               <a href="" class="btn btn-white mb-6"><span class="btn-icon icon-twitter"></span>Twitter</a>
               <a href="" class="btn btn-white"><span class="btn-icon icon-google"></span>Google</a>
            </div>
         </div>
      </div>
   </div>
   
   <div @click.prevent="closeLoginModal()" class="absolute  top-0 right-0  text-2xl p-3 mr-4 text-gray-500 hover:text-white">
         <a href="">X</a>
      </div>
</div>
</transition>