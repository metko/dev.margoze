@if (Auth::user())
@if ( ! Auth::user()->hasVerifiedEmail())
<div class="bg-blue-200 rounded p-6" role="alert">
   @if (session('resent'))
      {{ __('A fresh verification link has been sent to your email address.') }} 
   @else
      Votre compte n'est pas validé, merci de cliquer sur lien compris dans le mail. <a class="text-blue-800 font-bold" href="{{ route('verification.resend') }}">Renvoyer un email de confirmation</a>
   @endif
</div>
@endif  
@endif

