<?php

namespace App\Subscription;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $subscription = auth()->user()->subscriptions();
        if ($subscription->first()) {
            $subscription = $subscription->first();
            $latestPayment = $subscription->latestPayment();
            $nextInvoice = auth()->user()->upcomingInvoice();
            // dd(auth()->user()->defaultPaymentMethod());

            $invoices = auth()->user()->invoices();

            return view('dashboard.subscriptions.index', compact('subscription', 'latestPayment', 'nextInvoice', 'invoices'));
        }
        // dd($subscription->latestPayment());

        return view('dashboard.subscriptions.index', compact('subscription'));
    }

    public function cancel(Request $request, $slug)
    {
        $subscription = auth()->user()->subscription('main');
        if ($subscription->stripe_plan != $slug) {
            abort(500);
        }
        if ($subscription->cancel()) {
            return $this->index($request);
        }
    }

    public function resume(Request $request, $slug)
    {
        $subscription = auth()->user()->subscription('main');
        if ($subscription->stripe_plan != $slug) {
            abort(500);
        }
        if ($subscription->resume()) {
            return $this->index($request);
        }
    }
}
