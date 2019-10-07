<?php

namespace App\Plan;

use Stripe\Exception\CardException;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Request;
use Laravel\Cashier\Exceptions\IncompletePayment;

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
        $user = auth()->user();
        if ($user) {
            if (!$user->onPlan($slug)) {
                return view('plans.show', compact('plan'));
            }
            $user->load('subscriptions');
            $nextInvoice = $user->upcomingInvoice();

            return view('plans.show', compact('plan', 'nextInvoice'));
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
        $user = auth()->user();

        // dd($user->paymentMethods());
        try {
            $pm = $user->defaultPaymentMethod()->id;
            $subscription = $user->newSubscription('main', $request->plan['slug'])
                ->create($pm,
                [
                    // 'customer' => $params->customer,
                    'items' => [['plan' => $request->plan['slug']]],
                    'expand' => ['latest_invoice.payment_intent', 'pending_setup_intent'],
                ]);
        } catch (IncompletePayment $exception) {
            $data = [
                'require_actions' => $exception->payment->requiresAction(),
                'client_secret' => $exception->payment->clientSecret(),
                'payment_intent' => $exception->payment->id,
                'payment_method' => $exception->payment->payment_method,
                'status' => $exception->payment->status,
                'error_message' => $exception->payment->last_payment_error->message ?? '',
            ];

            return response()->json($data);
        } catch (CardException $exception) {
            //dd($exception->getJsonBody());

            return response()->json($exception->getJsonBody());
        }

        return response()->json($subscription);
    }
}
