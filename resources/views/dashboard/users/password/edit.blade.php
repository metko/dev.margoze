@extends('layouts.dashboard')


@section('main')
<div class="px-10 pt-10 pb-5 ">
      <div class="hero lg:flex  border-b pb-4 border-gray-100 items-center">
         <div class="flex justify-center items-center">
            <div class="flex items-center">
               <h1 class="title l2">Changer de mot de passe</h1>
            </div>
         </div> 
      </div>   
      
</div>
<form action="{{ route('dashboard.password.update') }}" method="POST" >
   <div class="px-10 pb-10  border-b border-gray-100 flex -mx-6 flex-wrap">
    
         @csrf
         <div class="w-full md:w-1/3 px-6">
            <div class="text-blue-primary font-bold title l4 mb-4">Ancien password</div>
               <div class="w-full relative py-3">
                   <input type="password" name="current_password" value="" class="input" placeholder="Mot de passe actuel">
                     @error('current_password')
                        <div  class="absolute top-0 mt-10  text-sm left-0 text-red-400" role="alert">
                           {{ $message }}
                        </div>
                     @enderror
               </div>  
         </div>
         <div class="w-full md:w-1/3 px-6">
            <div class="text-blue-primary font-bold title l4 mb-4">Nouveau password</div>
               <div class="w-full relative py-3">
                   <input type="password" name="new_password" value="" class="input" placeholder="Nouveau mot de passe">
                     @error('new_password')
                        <div  class="absolute top-0 mt-10  text-sm left-0 text-red-400" role="alert">
                           {{ $message }}
                        </div>
                     @enderror
               </div>  
         </div>
         <div class="w-full md:w-1/3 px-6">
            <div class="text-blue-primary font-bold title l4 mb-4">Confirmation password</div>
               <div class="w-full relative py-3">
                   <input type="password" name="new_password_confirmation" value="" class="input" placeholder="Confirmation mot de passe">
                     @error('new_password_confirmation')
                        <div  class="absolute top-0 mt-10  text-sm left-0 text-red-400" role="alert">
                           {{ $message }}
                        </div>
                     @enderror
               </div>  
         </div>
      
       
         <div class="w-full flex px-6 pt-6 md:pt-0">
               <div class="ml-auto">
                  <button class="btn btn-blue">Update password</button>
               </div>
         </div>   
   </div>
</form>
@endsection