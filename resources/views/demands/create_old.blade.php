@extends('layouts.app')

@section('content')

<div class="flex mb-4 items-center">
   <h3 class="text-2xl">Créer une nouvelle demande</h3>
</div>

<div class="mt-4 bg-white rounded overflow-hidden shadow-lg w-full">
      <form class="flex flex-wrap mt-8 " action="{{route('demands.store')}}" method="POST">
         @csrf
         <div class="px-6 py-4 w-1/2">
        
            <div  class="items-center mb-3">
               <label class="block tracking-wide text-gray-700 text-xs font-bold mb-2" for="title">
               Titre 
               </label>
               <input 
                     class="appearance-none block w-full bg-gray-100 text-gray-700 border 
                           rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-indigo-800
                           @error('title') border-red-600  @else border-indigo-600  @enderror" 
                     id="title" 
                     name="title" 
                     type="text"
                     value="{{ old('title') }}"
                     placeholder="Titre de la demande">    

                     @error('title')
                        <p  class="text-red-500 text-xs italic">{{ $message }}</p>
                     @enderror
            </div>

            <div  class="items-center mb-3">
               <label class="block tracking-wide text-gray-700 text-xs font-bold mb-2" for="location">
               Location 
               </label>
               <input 
                     class="appearance-none block w-full bg-gray-100 text-gray-700 border 
                      rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-indigo-800
                      @error('location') border-red-600  @else border-indigo-600  @enderror"  
                     id="location" 
                     name="location" 
                     value="{{ old('location') }}"
                     type="text"
                     placeholder="Lieux de la demande">    
                     @error('location')
                        <p  class="text-red-500 text-xs italic">{{ $message }}</p>
                     @enderror
            </div>

            <div  class="items-center mb-3">
               <label class="block tracking-wide text-gray-700 text-xs font-bold mb-2" for="postal">
               Code postal 
               </label>
               <input 
                     class="appearance-none block w-full bg-gray-100 text-gray-700 border 
                     rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-indigo-800
                     @error('postal') border-red-600  @else border-indigo-600  @enderror" 
                     id="postal" 
                     name="postal" 
                     value="{{ old('postal') }}"
                     type="text"
                     placeholder="Code postal de la demande">    
                     @error('postal')
                        <p  class="text-red-500 text-xs italic">{{ $message }}</p>
                     @enderror
            </div>

            <div  class="items-center mb-3">
               <label class="block tracking-wide text-gray-700 text-xs font-bold mb-2" for="budget">
               Budget
               </label>
               <input 
                     class="appearance-none block w-full bg-gray-100 text-gray-700 border 
                      rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-indigo-800
                      @error('budget') border-red-600  @else border-indigo-600  @enderror" 
                     id="budget" 
                     name="budget" 
                     value="{{ old('budget') }}"
                     type="text"
                     placeholder="Budget de la demande">    
                     @error('budget')
                        <p  class="text-red-500 text-xs italic">{{ $message }}</p>
                     @enderror
            </div>

            <div  class="items-center mb-3">
               <label class="block tracking-wide text-gray-700 text-xs font-bold mb-2" for="be_done_at">
               Date de réalisation souhaité
               </label>
               <input 
                     class="appearance-none block w-full bg-gray-100 text-gray-700 border 
                     rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-indigo-800
                     @error('be_done_at') border-red-600  @else border-indigo-600  @enderror" 
                     id="be_done_at" 
                     name="be_done_at" 
                     value="{{ old('be_done_at') }}"
                     type="date"
                     placeholder="date de réalisation souhaité">    
                     @error('be_done_at')
                        <p  class="text-red-500 text-xs italic">{{ $message }}</p>
                     @enderror
            </div>
            
         </div>
      
         <div class="px-6 py-4 p-4 w-1/2">
               <div  class="items-center mb-3">
                  <label class="block tracking-wide text-gray-700 text-xs font-bold mb-2" for="description">
                  Description courte 
                  </label>
                  <textarea 
                        class="appearance-none block w-full bg-gray-100 text-gray-700 border
                         rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-indigo-800
                         @error('description') border-red-600  @else border-indigo-600  @enderror" 
                        id="description" 
                        name="description" 
                        type="text"
                        value="{{ old('description') }}"
                        placeholder="Description courte"></textarea>  
                        @error('description')
                           <p  class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
               </div>
               <div  class="items-center mb-3">
                  <label class="block tracking-wide text-gray-700 text-xs font-bold mb-2" for="content">
                  Description longue 
                  </label>
                  <textarea 
                        class="appearance-none block w-full bg-gray-100 text-gray-700 border 
                         rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-indigo-800
                         @error('content') border-red-600  @else border-indigo-600  @enderror" 
                        id="content" 
                        name="content" 
                        type="text"
                        value="{{ old('content') }}"
                        placeholder="Description longue"></textarea>  
                        @error('content')
                           <p  class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
               </div>

               <div  class="items-center mb-3">
                  <label class="block tracking-wide text-gray-700 text-xs font-bold mb-2" for="sector_id">
                  Secteur de la demande
                  </label>
                  <select 
                        class="appearance-none block w-full bg-gray-100 text-gray-700 border 
                        rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-indigo-800
                        @error('sector_id') border-red-600  @else border-indigo-600  @enderror" 
                        id="sector_id" 
                        name="sector_id" 
                        placeholder="Sector de la demande"> 
                        <option value=""  @if( !old('sector_id')) selected @endif disabled hidden>Choisir secteur</option>
                        @foreach ($sectors as $key => $value)
                           <option value="{{ $key }}" @if(old('sector_id') == $key ) selected @endif>{{ $value }}</option>
                        @endforeach        
                  </select>                
                     @error('sector_id')
                        <p  class="text-red-500 text-xs italic">{{ $message }}</p>
                     @enderror
               </div>

               <div  class="items-center mb-3">
                     <label class="block tracking-wide text-gray-700 text-xs font-bold mb-2" for="category_id">
                     Categorie de la demande
                     </label>
                     <select 
                           class="appearance-none block w-full bg-gray-100 text-gray-700 border
                           rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white focus:border-indigo-800
                           @error('category_id') border-red-600  @else border-indigo-600  @enderror"  
                           id="category_id" 
                           name="category_id" 
                           placeholder="Sector de la demande"> 
                           <option value="" @if( !old('category_id')) selected @endif disabled hidden>Choisir categorie</option>
                           @foreach ($categories as $key => $value)
                              <option value="{{ $key }}" @if(old('category_id') == $key ) selected @endif>{{ $value }}</option>
                           @endforeach        
                     </select>                
                     @error('category_id')
                        <p  class="text-red-500 text-xs italic">{{ $message }}</p>
                     @enderror
                  </div>     
         </div>
         <div class=" flex px-6 py-4 p-4 w-full mb-8">
            <button
               type="submit"
               class="bg-indigo-500 hover:bg-indigo-700 text-white text-sm font-semibold py-2 px-4 rounded-full"
            >Créer   </button>

            <a
               href="{{url()->previous()}}"
               class="ml-auto bg-red-500 hover:bg-red-700 text-white text-sm font-semibold py-2 px-4 rounded-full"
            >Annuler</a>
         </div>
      </form>
</div>
    
@endsection