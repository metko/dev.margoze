<?php

namespace App\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Socialite;
use App\SocialAccount;

class SocialLoginController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::with($provider)->redirect();
    }

    public function handleProviderCallback($provider, Request $request)
    {
        $providerUser = Socialite::driver($provider)->user();
        //dd($providerUser);
        auth()->login(SocialAccount::findOrCreateUserWithProvider($providerUser, $provider));
        $request->session()->flash('status', 'Connected with '.$provider.' successfully!');

        return redirect()->to('/home');
    }
}
