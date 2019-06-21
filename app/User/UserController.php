<?php

namespace App\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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
}
