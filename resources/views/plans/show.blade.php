@extends('layouts.app')

@section('content')
<div class="border-b border-gray-100 py-24 pt-32 px-4 md:px-0">
      <div class="container mx-auto">
            <div class="title l3  text-blue-primary ">
               Formule {{ $plan->slug }}
           </div>
      </div>
</div>
<div class="container mx-auto mt-6 mb-10 overflow-x-hidden">
      <div class="-mx-4 flex flex-wrap">
      
            <div class="w-full md:w-2/3 px-4 ">
               <div class="px-4 md:px-0">
                  <div class="title l4 text-blue-primary ">
                        Formule {{ $plan->slug }} description
                  </div>
                  <div class="mt-6 text-gray-700 leading-loose">
                     Lorem ipsum dolor sit amet consectetur adipisicing elit. Consequatur pariatur tempora dolores nisi alias dolorum distinctio necessitatibus modi omnis similique? Reprehenderit voluptatibus repellendus suscipit vel nihil harum saepe, tempora expedita.
                  </div>
                  <ul class="mt-6 text-gray-700 leading-loose">
                     <li>5 demandes par moi</li>
                     <li>1 offres par moi</li>
                     <li>1 offres par moi</li>

                     <li>Visa : 4242424242424242</li>
                     <li>MasterCard : 5555555555554444</li>
                     <li>MasterCard : 5555555555554444</li>
                     <li>Amercian Express : 378282246310005</li>
                     <li>3d secure : 4000000000003220</li>
                     <li>3d secure declined : 4000008400001629</li>
                     <li>card declined : 4000000000000002</li>
                  </ul>
               </div>
            </div>
            <div class="w-full md:w-1/3 px-4  mt-10 md:mt-0">
                <div class="px-4 md:px-0">
                  
                        @guest
                        <div class="bg-gray-100 rounded p-6">
                           <div class="mb-6 text-blue-primary text-xl font-bold text-center">
                              Veuillez vous identifier pour profiter des abonnements Margoze
                           </div>
                           <div class="text-center">
                              <a href="/login" 
                              v-on:click.prevent="openLoginModal()"
                              class="btn inline-block">Se connecter</a>
                           </div>
                        </div>
                     @else
                        @if (auth()->user()->onPlan($plan->slug))
                           
                           <div class="bg-gray-100 rounded p-6">
                                 <div class="mb-6 text-gray-700 text-lg text-center">
                                       Vous êtes déja souscris a cet abonnement depuis le 
                                       <span class="text-blue-primary text-font">{{auth()->user()->subscribeSince }}</span>
                                 </div>
                                 <div class="text-center">
                                    Prochaine facture dans {{ auth()->user()->upCommingInvoiceInDays}} jours
                                 </div>
                                 <div href="#" class="flex items-center justify-center mt-3">
                                    <a href="#" class="btn small inline-block"> Gerer mes abonnements</a>
                                 </div>
                              </div>
                        @elseif( auth()->user()->hasPaymentMethod() ) 
                                 <subscribe-button 
                                 stripekey="{{ config("services.stripe.key") }}"
                                 :user="{{ auth()->user() }}"
                                 :plan="{{ $plan }}"
                                 >
                              </subscribe-button>
                        @else
                           <subscribe-form 
                                 stripekey="{{ config("services.stripe.key") }}"
                                 :user="{{ auth()->user() }}"
                                 :plan="{{ $plan }}"
                                 >
                              </subscribe-form>
                        @endif
                     @endguest
                  </div>            
               </div>  
         </div>    
      </div>
</div>

@endsection


@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
@endpush