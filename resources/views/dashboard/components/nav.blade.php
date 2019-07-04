<div class="bg-white shadow w-full my-2">
      <div class="flex items-center p-4 ">
         <img class="w-10 h-10 rounded-full mr-4 border-4  border-gray-400 @if(Auth::user()->subscriber )border-indigo-800 @endif" src="{{ $user->avatar }}" alt="Avatar of Jonathan Reinink">
         <div class="text-sm">
            <p class="text-gray-900 leading-none">{{ $user->username }}</p>
            <p class="text-gray-600">Member since {{ $user->created_at}}</p>
         </div>
      </div>
   <ul class="list-reset">
      <li >
         <a href="{{ route('dashboard.index') }}" class="block p-4 text-gray-darker border-indigo-800 hover:bg-gray-lighter hover:bg-gray-100 border-r-4">Dashboard</a>
      </li>
      <li >
         <a href="{{ route('dashboard.demands') }}" class="block p-4 text-gray-darker border-gray-lighter hover:border-indigo-400 hover:bg-gray-lighter hover:bg-gray-100 border-r-4">Demandes</a>
      </li>
      <li >
         <a href="{{ route('dashboard.contracts') }}" class="block p-4 text-gray-darker border-gray-lighter hover:border-indigo-400 hover:bg-gray-lighter hover:bg-gray-100 border-r-4">Contracts</a>
      </li>
      <li >
         <a href="{{ route('dashboard.inbox') }}" class="block p-4 text-gray-darker border-gray-lighter hover:border-indigo-400 hover:bg-gray-lighter hover:bg-gray-100 border-r-4">Messages</a>
      </li>
      <li >
         <a href="{{ route('dashboard.profile') }}" class="block p-4 text-gray-darker border-gray-lighter hover:border-indigo-400 hover:bg-gray-lighter hover:bg-gray-100 border-r-4">Settings</a>
      </li>
      <li >
         <form action="/logout" method="POST">
            @csrf
            <button type="submit" class="text-left w-full block p-4 text-gray-darker border-gray-lighter hover:border-indigo-400 hover:bg-gray-lighter hover:bg-gray-100 border-r-4">Se déconnecter</button>
         </form>
      </li>
   </ul>
</div>