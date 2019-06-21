<?php

namespace App\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User\Events\UserPasswordUpdated;
use App\User\Requests\UpdateUserRequest;
use App\User\Events\UserSuspendedAccount;

class UserController extends Controller
{
    public function profile(User $user = null)
    {
        if (!$user) {
            $user = Auth::user();
        }

        return view('users/profile', compact('user'));
    }

    public function edit(User $user)
    {
        $this->authorize('manage', $user);

        return view('users.edit', compact('user'));
    }

    public function update(User $user, UpdateUserRequest $request)
    {
        $this->authorize('manage', $user);
        $user->update($request->all());

        return redirect(route('users.profile'))->with('success', 'user updated');
    }

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

    public function suspendAccount(User $user)
    {
        $this->authorize('manage', $user);
        $user->suspendAccount();
        event(new UserSuspendedAccount($user));
    }
}
