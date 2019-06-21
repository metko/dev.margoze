<?php

namespace App\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User\Requests\UpdateUserRequest;

class UserController extends Controller
{
    public function profile()
    {
        $user = Auth::user();

        return view('users/profile', compact('user'));
    }

    public function edit(User $user, UpdateUserRequest $request)
    {
        $this->authorize('manage', $user);
        $user->update($request->all());
    }

    public function editPassword(User $user, Request $request)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $user->update([
            'password' => Hash::make($request->password),
        ]);
    }
}
