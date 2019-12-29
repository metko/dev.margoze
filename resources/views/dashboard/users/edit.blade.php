@extends('layouts.dashboard')


@section('main')
<div class="px-10 pt-10 pb-5 ">
      <div class="hero lg:flex  border-b pb-4 border-gray-100 items-center">
         <div class="flex justify-center items-center">
            <div class="flex items-center">
               <img class="w-10 h-10 rounded-full mr-4" src="{{$user->getAvatar()}}" alt="Avatar of Jonathan Reinink">
            </div>
            <a href="" class="btn small">Changer d'avatar</a>
         </div>
         <div class="ml-auto">
            <a href="{{route('dashboard.password.edit')}}" class="btn small btn-blue">Changer de mot de passe</a>
         </div>  
      </div>   
      
</div>
<form action="{{ route('dashboard.profile.update') }}" method="POST" >
   <div class="px-10 pb-10  border-b border-gray-100 flex -mx-6 flex-wrap">
    
         @csrf
         <div class="w-full md:w-1/3 px-6">
            <div class="text-blue-primary font-bold title l4 mb-4">Infos perso</div>
               <div class="w-full relative py-3">
                   <input type="text" name="first_name" value="{{$user->first_name}}" class="input" placeholder="Prénom">
                     @error('first_name')
                        <div  class="absolute top-0 mt-10  text-sm left-0 text-red-400" role="alert">
                           {{ $message }}
                        </div>
                     @enderror
               </div>
               <div class="w-full relative py-3">
                  <input type="text" name="last_name" value="{{$user->last_name}}" class="input" placeholder="Nom">
                    @error('last_name')
                       <div  class="absolute top-0 mt-10  text-sm left-0 text-red-400" role="alert">
                          {{ $message }}
                       </div>
                    @enderror
              </div>   
              <div class="w-full relative py-3">
               <input type="email" name="email" value="{{$user->email}}" class="input" placeholder="Email">
                 @error('email')
                    <div  class="absolute top-0 mt-10  text-sm left-0 text-red-400" role="alert">
                       {{ $message }}
                    </div>
                 @enderror
              </div> 
              <div class="w-full relative py-3">
               <input type="text" name="username" value="{{$user->username}}" class="input" placeholder="Nom d'utilisateur">
                 @error('username')
                    <div  class="absolute top-0 mt-10  text-sm left-0 text-red-400" role="alert">
                       {{ $message }}
                    </div>
                 @enderror
              </div> 
              <div class="w-full relative py-3">
               <input type="datetime" name="date_of_birth" value="{{$user->date_of_birth}}" class="input" placeholder="Date de naisaance">
                 @error('date_of_birth')
                    <div  class="absolute top-0 mt-10  text-sm left-0 text-red-400" role="alert">
                       {{ $message }}
                    </div>
                 @enderror
              </div> 

              
         </div>
         <div class="w-full md:w-1/3 px-6 pt-6 md:pt-0">
            <div class="text-blue-primary font-bold title l4 mb-4">Contact</div>
            
            <div class="w-full relative py-3">
               <input type="number" name="phone_1" value="{{$user->phone_1}}" class="input" placeholder="Télephone 1">
                  @error('phone_1')
                     <div  class="absolute top-0 mt-10  text-sm left-0 text-red-400" role="alert">
                        {{ $message }}
                     </div>
                  @enderror
            </div> 
            
            <div class="w-full relative py-3">
               <input type="number" name="phone_2" value="{{$user->phone_2}}" class="input" placeholder="Télephone 2">
                  @error('phone_2')
                     <div  class="absolute top-0 mt-10  text-sm left-0 text-red-400" role="alert">
                        {{ $message }}
                     </div>
                  @enderror
               </div> 

            <div class="w-full relative py-3">
               <input type="text" name="adress_1" value="{{$user->adress_1}}" class="input" placeholder="Adresse">
               @error('adress_1')
                  <div  class="absolute top-0 mt-10  text-sm left-0 text-red-400" role="alert">
                     {{ $message }}
                  </div>
               @enderror
            </div> 

            <div class="w-full relative py-3">
               <input type="text" name="adress_2" value="{{$user->adress_2}}" class="input" placeholder="Adresse 2">
               @error('adress_2')
                  <div  class="absolute top-0 mt-10  text-sm left-0 text-red-400" role="alert">
                     {{ $message }}
                  </div>
               @enderror
            </div> 
            <div class="w-full relative py-3">
               <input type="datetime" name="postal" value="{{$user->postal}}" class="input" placeholder="Date de naisaance">
                 @error('postal')
                    <div  class="absolute top-0 mt-10  text-sm left-0 text-red-400" role="alert">
                       {{ $message }}
                    </div>
                 @enderror
              </div> 
            <div class="w-full relative py-3 w-1/3">
               <select name="commune_id"  class="input" id="dashboard-edit-profile-commune">
                  @foreach ($communes as $commune)
                     <option value="{{ $commune->id}}" @if($commune->id == $user->commune_id) {{'selected' }}@endif>{{ $commune->name}}</option>
                  @endforeach
               </select>
                 @error('commune_id')
                    <div  class="absolute top-0 mt-10  text-sm left-0 text-red-400" role="alert">
                       {{ $message }}
                    </div>
                 @enderror
           </div>
            <div class="w-full relative py-3 w-1/3">
               <select name="district_id"  id="dashboard-edit-profile-district" class="input">
                  <option data-commune-id="0" value="">-</option>
                  @foreach ($districts as $district)
                     <option data-commune-id="{{$district->commune_id}}" value="{{ $district->id }}" @if($district->id == $user->district_id) {{'selected' }}@endif>{{ $district->name}}</option>
                  @endforeach
               </select>
               @error('district_id')
                  <div  class="absolute top-0 mt-10  text-sm left-0 text-red-400" role="alert">
                     {{ $message }}
                  </div>
               @enderror
            </div>
            
         </div>  
         <div class="w-full md:w-1/3 px-6 pt-6 md:pt-0">
            <div class="text-blue-primary font-bold title l4 mb-4">Aider les autres à mieux vous connaître</div>

            <div class="w-full relative py-3">
            <textarea name="biography" value="{{$user->biography}}" class="input" placeholder="Biographie">{{$user->biography}}</textarea>
                 @error('biography')
                    <div  class="absolute text-sm left-0 text-red-400" role="alert">
                       {{ $message }}
                    </div>
                 @enderror
           </div>
         </div>  
         <div class="w-full flex px-6 pt-6 md:pt-0">
               <div class="ml-auto">
                  <button class="btn btn-blue">Update profile</button>
               </div>
         </div>   
   </div>
</form>
@endsection