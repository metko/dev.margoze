@if (Auth::user())
   @if ( ! Auth::user()->hasVerifiedEmail())
         <div class="text-center py-4 lg:px-4">
            <div class="w-full p-3 bg-indigo-800 items-center text-indigo-100 leading-none lg:rounded-full flex lg:inline-flex" role="alert">
               <span class="flex rounded-full bg-indigo-500 uppercase px-2 py-1 text-xs font-bold mx-3">Hey!</span>
               <span class="text-sm mr-2 text-left flex-auto leading-tight">
                  @if (session('resent'))
                     {{ __('A fresh verification link has been sent to your email address.') }} 
                  @else
                     Votre compte n'est pas validé, merci de cliquer sur lien compris dans le mail. <a class="font-semi-bold hover:text-indigo-300" href="{{ route('verification.resend') }}">Renvoyer un email de confirmation</a>
                  @endif
               </span>
               <svg class="fill-current opacity-75 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M12.95 10.707l.707-.707L8 4.343 6.586 5.757 10.828 10l-4.242 4.243L8 15.657l4.95-4.95z"/></svg>
            </div>
         </div>
   @endif  
@endif

