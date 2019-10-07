@extends('layouts.dashboard')

@section('main')

<div class="p-10 border-b border-gray-100">
   <div class="flex">
      <div>
            <div class="title  l3 md:l2 gray ">Mes abonnements</div>
            <div class="text-gray-800 mt-2">Ici, retrouvez les abonements auquels vous avez souscrit, vos factures et vos inormations de cb</div>
      </div>      
   </div>

   

   <div class="flex flex-wrap -mx-4 mt-2">
            <div class="w-full lg:w-1/2 lg:px-4">
               <div class="rounded p-6 border border-blue-primary">
                  @if ($subscription && !$subscription->ended() )
                        <div class="title l5 text-gray-800">Vous avez souscris à l'abonnement <span class="text-blue-primary">{{ucfirst($subscription->stripe_plan)}}</span></div>
                        
                        <div class="text-gray-700 mt-2">Statut :  

                           @if($subscription->hasIncompletePayment())
                              <span class="border border-orange-600 font-bold rounded text-orange-600 px-1">En attente de paiment</span>   
                           @elseif($subscription->ended())
                              <span class="border border-red-600 font-bold rounded text-red-600 px-1">Expiré</span>   
                           @elseif ($subscription->active())
                              <span class="border border-blue-primary font-bold rounded text-blue-primary px-1">Active</span>   
                           @endif
                           
                           @if ($subscription->onGracePeriod())
                               <div>
                                  Votre abonnement prendra fin dans {{ days_count($subscription->ends_at) }} jours
                              </div>
                           @endif
                        </div>

                        @if ( ! $subscription->onGracePeriod() && !$subscription->hasIncompletePayment())
                           <div class="text-gray-700 mt-4">Prochaine facture le 
                              <span class="font-bold">{{ human_date($nextInvoice->created) }}</span>
                              d'un montant de 
                              <span class="font-bold"> 
                                 {{substr_replace($nextInvoice->amount_due, ",", -2, 0)   }}€ 
                              </span>
                           </div>
                        @endif
                        @if ( $subscription->hasIncompletePayment())
                           <div class="text-gray-700 mt-4">
                              <a href="{{ route('cashier.payment', $subscription->latestPayment()->id) }}" class="btn inline-block">
                                 Payer ma facture d'un montant de {{substr_replace($subscription->latestPayment()->amount, ",", -2, 0)}}€
                              </a>
                           </div>
                        @endif

                        @if($subscription->onGracePeriod())
                           <div class="mt-3">
                                 <form action="{{ route('subscriptions.resume', $subscription->stripe_plan)}}" method="POST">
                                    @csrf
                                  <button  class="btn  inline-block">Reprendre/resume mon abonnement</button>
                           </form>
                           </div>
                        @elseif(!$subscription->hasIncompletePayment())
                        <div class="mt-3">
                               <form action="{{ route('subscriptions.cancel', $subscription->stripe_plan)}}" method="POST">
                                 @csrf
                                 @method('DELETE')
                                 <button class="btn small btn-danger inline-block">Annuler mon abonnement</button>
                              </form>
                           </div>
                        @endif
                  @else
                     <div>
                           <div class="title l5 text-center">Vous n'avez aucuns abonnements actifs pour le moment</div>
                           <div class="flex justify-center mt-4">
                                 <a href="/plans" class="btn inline-block">Choisir une formule</a>
                           </div>
                     </div>
                      
                  @endif
               </div>
            </div>
            <div class="w-full lg:w-1/2 mt-6 lg:mt-0 lg:px-4">
               @if( auth()->user()->defaultPaymentMethod() )
                  <div class="rounded p-6 border border-gray-400">
                        <div class="card_type text-gray-800 text-center">
                           <span class="uppercase font-bold text-lg text-blue-primary">{{ auth()->user()->card_brand}}</span>
                        </div>
                        <div class="flex justify-center">
                              <div class="mt-3 card_number text-center inline-block tracking-wide tracking-widest font-mono text-gray-600 rounded bg-gray-100 px-4 py-3 ">
                                    **** - **** - **** -  <span class="uppercase font-bold text-gray-800">{{ auth()->user()->card_last_four}}</span>
                                 </div>
                        </div>
                        <div class="flex justify-center items-center mt-4">
                           <form action="{{ route('users.destroy.paymentmethod')}}" method="POST">
                                  @csrf
                                  @method('DELETE')
                                 <button class="text-red-600 inline-block mr-3">Supprimer la carte</button>
                           </form>
                          
                           <a href="#"  @click.prevent="$modal.show('add-card')" class="btn small inline-block">Modififier la carte</a>
                        </div>
                       
                  </div>
               @else
                  <div class="rounded p-6 border border-gray-400">
                        <div class=" title l5 text-gray-800"> Vous n'avez aucuns moyen de paiment enregistré actuellement</div>
                        <div class="flex mt-3">
                           <a href="" @click.prevent="$modal.show('add-card')" class="btn inline-block">Enregistrer un moyen de paiment</a>
                     </div>
                  </div>
               @endif
            </div>



      <div class="w-full p-4  mt-10 flex flex-wrap">

            <div class="w-full lg:px-4">
               <div class="flex">
                     <div>
                           <div class="title  l3 md:l2 gray ">Mes factures</div>
                     </div>      
                  </div>
            </div>
            <div class="w-full mt-4 lg:px-4 ">
               @isset($invoices)
                   
              
                  @foreach ($invoices as $invoice)
                     <div class="w-full px-4 py-3 bg-gray-100 rounded flex justify-between items-center mb-3">
                           <div class="periods text-gray-700">
                                 Période du 
                                 <span class="text-gray-800 font-bold">
                                    {{ human_date($invoice->lines->data[0]->period->start) }} 
                                 </span>
                                 au  
                                 <span class="text-gray-800 font-bold">
                                      {{ human_date($invoice->lines->data[0]->period->end) }}  
                                 </span>
                           </div>
                           <div class="amount text-gray-800 font-bold">
                              {{substr_replace($invoice->total, ",", -2, 0)   }}€ 
                           </div>
                           <div class="status">
                                 @if ($invoice->paid)
                                    <span class="btn small btn-inverse btn-blue">Payé</span> 
                                 @else
                                    {{ $invoice->status }}
                                 @endif
                           </div>
                           <div class="actions">
                                 <a href="{{ $invoice->invoice_pdf}}" class="btn small inline-block"> telecharger la facture</a>
                           </div>
                     </div>   
                  @endforeach
               @else
               <div>
                     Vous n'avez aucunes factures pour le moment
                  </div>
               @endisset
            </div>



      </div>
   </div>
</div>


<modal name="add-card"  height="auto" :adaptive="true">
   <add-card-form
      :user="{{ auth()->user() }}"
      stripekey="{{ config("services.stripe.key") }}"
   ></add-card-form>
</modal>

@endsection


@push('scripts')
   <script src="https://js.stripe.com/v3/"></script>
@endpush