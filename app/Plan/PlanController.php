<?php

namespace App\Plan;

use App\Subscription\Subscription;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::all();

        return view('plans.index', compact('plans'));
    }

    public function show($slug)
    {
        $plan = Plan::whereSlug($slug)->first();
        if (!$plan) {
            abort(404);
        } elseif (Auth::user()->subscribed('main', $plan->stripe_id)) {
            //abort(500, 'you already scubscribed');
        }

        return view('plans.show', compact('plan'));
    }

    /**
     * subscribe.
     *
     * @param mixed $request
     */
    public function subscribe(Request $request)
    {
        $sub = new Subscription();
        $plan = Plan::whereStripeId($request->plan_name)->first();
        if (!$plan) {
            // TODO Create exception Plan not exist
            abort(500);
        }
        // return error if je sais pas
        $subscription = $sub->saveStripeSubscription($request, $plan);

        if ($subscription->latest_invoice->payment_intent->status == 'requires_action') {
            //return view('plans.confirmsecurecode', compact('subscription'));
            return response()->json([
                'status' => 'incomplete',
                'message' => 'Vous allez être redirigé pour confirmer le paiement',
                'data' => $subscription,
            ]);
        }

        if ($subscription) {
            $sub->saveSubscription($subscription, $request->user());

            return response()->json([
                'status' => 'success',
                'message' => 'Felicitation, vous êtes maintenant un membre de la famille =) !',
            ]);
        }

        // return view('plans.subscriptionconfirmed', compact('subscription'));
    }
}
