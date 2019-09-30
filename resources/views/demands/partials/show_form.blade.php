 @guest

   <div class='lg:sticky ' style="top:100px">
      <div class="mb-6 text-white text-xl font-bold text-center">Connectez vous pour déposer votre candidature à Samuel</div>
      <div class="text-center"><a href="{{ route('login') }}" 
         v-on:click.prevent="openLoginModal()"
         class="btn btn-white inline-block">Se connecter</a></div>
   </div> 

@else
   @if (auth()->user()->isOwnerDemand($demand))
      <div class="mx-6 mb-10 lg:m-0  rounded p-6 xl:p-10 lg:sticky flex items-center justify-center" style="top:100px">
      <a href="" class="btn btn-white">Editer ma demande</a>
      </div>
   @else
      <div class="bg-white mx-6 mb-10 lg:m-0  rounded p-6 xl:p-10 lg:sticky " style="top:100px">
         <div class="text-blue-primary font-bold text-xl text-center mb-10">Envoyez votre candidature à Samuel</div>
         <form action="">
            <textarea name="" class="rounded mb-10 border border-blue-primary p-4 w-full h-32 focus:outline-none" >Bonjour,
   je suis intéressé par votre demande, je pense pouvoir vous aider...</textarea>
            <div class="text-center">
               <button class="btn rounded-full">Envoyer ma candidature</button>
            </div>
         </form>
      </div>
   @endif
  

@endauth
         

