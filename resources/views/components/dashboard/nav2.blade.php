<div class="hero  lg:block bg-blue-darken">
      <div class="w-full  px-4 py-6 md:py-12">
         <div class="title l3 md:l2 white ">Dashboard</div>
         <div class="text-gray-400 mt-2">Home</div>
      </div>
   
   </div>
   <div class="w-full mt-4">
      <ul class="">
         <li >
            <a href="{{ route('dashboard.index') }}" class="block p-4 text-white font-bold ">Dashboard</a>
         </li>
         <li >
            <a href="{{ route('dashboard.demands') }}" class="block p-4 text-gray-400 hover:text-white hover:font-bold">Demandes</a>
         </li>
         <li >
            <a href="{{ route('contracts.index') }}" class="block p-4 text-gray-400 hover:text-white hover:font-bold">Contracts</a>
         </li>
         <li >
            <a href="{{ route('dashboard.inbox') }}" class="block p-4 text-gray-400 hover:text-white hover:font-bold">Messages</a>
         </li>
         <li >
            <a href="{{ route('dashboard.profile') }}" class="block p-4 text-gray-400 hover:text-white hover:font-bold">Profile</a>
         </li>
         <li >
            <form action="/logout" method="POST">
               @csrf
               <button type="submit" class="text-left w-full block p-4 text-gray-600 hover:text-gray-800 hover:font-bold">Se déconnecter</button>
            </form>
         </li>
      </ul>
   </div>