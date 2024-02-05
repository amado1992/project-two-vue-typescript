<?php

namespace Modules\Users\Application;

use Illuminate\Support\Facades\Hash;
use Modules\Users\Entities\User;
use Modules\Users\Events\CreatingUser;
use Modules\Users\Events\UserCreated;
use Modules\Users\Http\Requests\StoreUserRequest;

/**
 * Create a user.
 *
 * @author Abel David.
 */
class CreateUserUseCase
{
    /**
     * @param StoreUserRequest $request
     * @return User
     */
    public function __invoke(StoreUserRequest $request): User
    {
        CreatingUser::dispatch();

        $user = User::create([
            'name' => $request->input('name'),
            'lastname' => $request->input('lastname'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'client_id' => $request->input('client'),
            'created_by' => auth()->user()->getAuthIdentifier(),
            'active' => $request->boolean('active')
        ]);

        $user->syncRoles($request->input('role'));

        UserCreated::dispatch($user);

        return $user;
    }
}
