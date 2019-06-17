<?php

namespace App;

use Stripe\Stripe;
use Stripe\Subscription as StripeSubscription;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    public function saveStripeSubscription($request, $plan)
    {
        $token = $request->stripeToken;
        if (!$customer = $request->user()->stripe_id) {
            $customer = $request->user()->createAsStripeCustomer();
        } else {
            $customer = $request->user()->asStripeCustomer();
        }
        Stripe::setApiKey(config('services.stripe.secret'));
        $request->user()->updateCard($request->stripeToken);

        return  StripeSubscription::create([
            'customer' => $customer->id,
            'items' => [
                ['plan' => $plan->stripe_id],
            ],
            'expand' => ['latest_invoice.payment_intent'],
        ]);
    }

    public function saveSubscription($subscription, $user)
    {
        $user->subscriptions()->create([
            'name' => 'main',
            'stripe_id' => $subscription->id,
            'stripe_plan' => $subscription->plan->id,
            'quantity' => $subscription->quantity,
            'trial_ends_at' => null,
            'ends_at' => null,
        ]);
    }
}
