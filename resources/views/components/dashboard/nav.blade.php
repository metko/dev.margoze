<div class="w-full container mx-auto  bg-blue-primary rounded-tl rounded-tr mt-10">
   <ul class="flex pt-3 pb-2 px-5">
      <li >
         <a href="{{ route('dashboard.index') }}" class="block p-5 text-white font-bold ">Home</a>
      </li>
      <li >
         <a href="{{ route('dashboard.demands') }}" class="block p-5 text-gray-400 hover:text-white hover:font-bold">Mes demandes</a>
      </li>
      <li >
         <a href="{{ route('contracts.index') }}" class="block p-5 text-gray-400 hover:text-white hover:font-bold">Mes contracts</a>
      </li>
      <li >
         <a href="{{ route('dashboard.inbox') }}" class="block p-5 text-gray-400 hover:text-white hover:font-bold">Messagerie</a>
      </li>
      <li >
         <a href="{{ route('dashboard.profile') }}" class="block p-5 text-gray-400 hover:text-white hover:font-bold">Mon profile</a>
      </li>
      <li class="ml-auto">
         <form action="/logout" method="POST">
            @csrf
            <button type="submit" class="text-left w-full block p-5 text-white  hover:font-bold">Se déconnecter</button>
         </form>
      </li>
   </ul>
</div>