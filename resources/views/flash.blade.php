@if (session('success'))
<div class="container mx-auto mt-3">
      <div class="rounded p-4 text-gray-700 bg-green-300 text-sm" role="alert">
         {{ session('success') }}
      </div>
   </div>
@endif

@if (session('error'))
   <div class="container mx-auto mt-3">
      <div class="rounded p-4 text-gray-700 text-red-600 bg-green-600 text-sm" role="alert">
         {{ session('error') }}
      </div>
   </div>
@endif