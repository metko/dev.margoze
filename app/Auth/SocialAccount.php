<?php

namespace App;

use App\User\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class SocialAccount extends Model
{
    protected $table = 'user_social_account';

    protected $fillable = ['user_id', 'provider_user_id', 'provider'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function findOrCreateUserWithProvider($providerUser, $provider)
    {
        //Check if the user had been connected already with this provider
        $account = SocialAccount::whereProvider($provider)
                    ->whereProviderUserId($providerUser->getId())
                    ->first();

        //if yes, return the user associate
        if ($account) {
            return $account->user;
        }

        return self::createUserProvider($providerUser, $provider);
    }

    private static function createUserProvider($providerUser, $provider)
    {
        //dd($providerUser->getId());
        //Else, create a new social account with this provider
        $socialAccount = new SocialAccount([
            'provider' => $provider,
            'provider_user_id' => $providerUser->getId(),
        ]);

        //Maybe the user used another social login to connect
        //If yes get it, else create one
        $user = User::whereEmail($providerUser->getEmail())->first();

        //If not, create a new user
        if (!$user) {
            if ($provider == 'google') {
                $user = self::createUserFromGoogle($providerUser);
            } elseif ($provider == 'facebook') {
                $user = self::createUserFromFacebook($providerUser);
            } elseif ($provider == 'twitter') {
                $user = self::createUserFromTwitter($providerUser);
            }
            $user->sendEmailVerificationNotification();
        }
        // Then , link the user to the provider and save them
        $socialAccount->user()->associate($user);
        //Persisit the data
        $socialAccount->save();
        // return the instanf of User class
        return $socialAccount->user;
    }

    private static function createUserFromGoogle($providerUser)
    {
        return User::create([
            'email' => $providerUser->getEmail(),
            'username' => self::createUsername($providerUser->getName()),
            'password' => bcrypt(Str::random(40)),
            'first_name' => $providerUser->user['name']['givenName'],
            'last_name' => $providerUser->user['name']['familyName'],
            'avatar' => $providerUser->avatar,
        ]);
    }

    private static function createUserFromFacebook($providerUser)
    {
        return User::create([
            'email' => $providerUser->getEmail(),
            'username' => self::createUsername($providerUser->getName()),
            'password' => bcrypt(Str::random(40)),
            'avatar' => $providerUser->avatar,
        ]);
    }

    private static function createUsername($username)
    {
        $user = User::whereUsername($username)->first();
        if (!$user) {
            return $username;
        } else {
            return $username.' '.rand(0, 100);
        }
    }
}
