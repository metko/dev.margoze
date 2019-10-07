<?php

namespace App\User;

use App\Demand\Demand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User\Events\UserPasswordUpdated;
use App\User\Requests\UpdateUserRequest;
use App\User\Events\UserSuspendedAccount;
use Stripe\Exception\CardException;

class UserController extends Controller
{
    /**
     * Get a user profile.
     *
     * @param mixed $user
     */
    public function profile(User $user = null)
    {
        $user = $user ?? Auth::user();

        $demands = Demand::where('owner_id', '=', $user->id)->get();

        return view('users/profile', compact('user', 'demands'));
    }

    /**
     * edit.
     *
     * @param mixed $user
     */
    public function edit(User $user)
    {
        $this->authorize('manage', $user);

        return view('users.edit', compact('user'));
    }

    /**
     * update.
     *
     * @param mixed $user
     * @param mixed $request
     */
    public function update(User $user, UpdateUserRequest $request)
    {
        $this->authorize('manage', $user);
        $user->update($request->all());

        return redirect(route('users.profile'))->with('success', 'user updated');
    }

    /**
     * updatePassword.
     *
     * @param mixed $user
     * @param mixed $request
     */
    public function updatePassword(User $user, Request $request)
    {
        $this->authorize('manage', $user);
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $user->update([
            'password' => Hash::make($request->password),
        ]);
        event(new UserPasswordUpdated($user));
    }

    /**
     * suspendAccount.
     *
     * @param mixed $user
     */
    public function suspendAccount(User $user)
    {
        $this->authorize('manage', $user);
        $user->suspendAccount();
        event(new UserSuspendedAccount($user));
    }

    /**
     * Get the notification for the user.
     *
     * @param mixed $request
     */
    public function notifications(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }
        $notifications = auth()->user()->notifications->take(6);

        return [
            'notifications' => $notifications,
            'unreadcount' => $notifications->where('read_at', null)->count(),
        ];
    }

    /**
     * readNotifications.
     *
     * @param mixed $request
     */
    public function readNotifications(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }

        return auth()->user()->unreadNotifications->markAsRead();
    }

    public function updatePaymentMethod(Request $request)
    {
        $user = auth()->user();

        if (!$user->stripe_id) {
            $user->createAsStripeCustomer();
        }

        if ($user->defaultPaymentMethod()) {
            $user->deletePaymentMethods();
        }

        try {
            $paymentMethod = $user->updateDefaultPaymentMethod($request->paymentMethod['id']);
            $data = [
                    'status' => 'success',
                    'pm' => $paymentMethod->id,
                ];

            return json_encode($data);
        } catch (CardException $exception) {
            return response()->json($exception->getJsonBody());
        }
    }

    public function deletePaymentMethod(Request $request)
    {
        $user = auth()->user();

        if (!$user->stripe_id) {
            abort(500);
        }

        if (!$user->defaultPaymentMethod()) {
            abort(500);
        }

        $user->deletePaymentMethods();

        return redirect()->route('subscriptions.index');
    }
}
