@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 lg:px-0">
      <div class=" py-8 text-3xl mt-4">
         Edit profile 
      </div>
   <form action="{{ route('users.update' ,$user->id )}}" method="POST">
         @csrf
         <div class="flex flex-wrap">

            <div class="w-1/3 px-2">
               @include('components.input',[
                  'name' => 'username',
                  'type' => 'text',
                  'value' => $user->username,
                  'label' => __('Username') 
               ])
               @include('components.input',[
                  'name' => 'email',
                  'type' => 'email',
                  'value' => $user->email,
                  'label' => __('Email') 
               ])
               @include('components.input',[
                  'name' => 'first_name',
                  'type' => 'text',
                  'value' => $user->first_name,
                  'label' => __('First name') 
               ])
               @include('components.input',[
                  'name' => 'last_name',
                  'type' => 'text',
                  'value' => $user->last_name,
                  'label' => __('Last name') 
               ])
               @include('components.input',[
                  'name' => 'biography',
                  'type' => 'textarea',
                  'value' => $user->biography,
                  'label' => __('Biographie') 
               ])
               
            </div>
            <div class="w-1/3 px-2 ">
               @include('components.input',[
                  'name' => 'adress_1',
                  'type' => 'text',
                  'value' => $user->adress_1,
                  'label' => __('Adress 1') 
               ])
               @include('components.input',[
                  'name' => 'adress_2',
                  'type' => 'text',
                  'value' => $user->adress_2,
                  'label' => __('Adress 2') 
               ])
               @include('components.input',[
                  'name' => 'sector',
                  'type' => 'text',
                  'value' => $user->sector,
                  'label' => __('Sector') 
               ])
               @include('components.input',[
                  'name' => 'postal',
                  'type' => 'number',
                  'value' => $user->postal,
                  'label' => __('Postal') 
               ])
               @include('components.input',[
                  'name' => 'city',
                  'type' => 'text',
                  'value' => $user->city,
                  'label' => __('City') 
               ])
               
            </div>
            <div class="w-1/3 px-2 ">
               @include('components.input',[
                  'name' => 'phone_1',
                  'type' => 'text',
                  'value' => $user->phone_1,
                  'label' => __('Phone 1') 
               ])
               @include('components.input',[
                  'name' => 'phone_2',
                  'type' => 'text',
                  'value' => $user->phone_2,
                  'label' => __('Phone 2') 
               ])
               @include('components.input',[
                  'name' => 'date_of_birth',
                  'type' => 'date',
                  'value' => $user->date_of_birth,
                  'label' => __('Date of birth') 
               ])
               @include('components.input',[
                  'name' => 'vehiculable',
                  'type' => 'checkbox',
                  'value' => $user->vehiculable,
                  'label' => __('Vehiculable') 
               ])
            </div>
         </div>
         <button type="submit" class="btn btn-primary">Update</button>
      </form>
         
   </div>
      
</div>
@endsection