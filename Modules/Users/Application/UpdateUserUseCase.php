<?php

namespace Modules\Users\Application;

use Exception;
use Illuminate\Support\Facades\Hash;
use Modules\Users\Entities\User;
use Modules\Users\Events\UpdatingUser;
use Modules\Users\Events\UserUpdated;
use Modules\Users\Http\Requests\UpdateUserRequest;

/**
 * Update a user.
 *
 * @author Abel David.
 */
class UpdateUserUseCase
{
    /**
     * @param UpdateUserRequest $request
     * @param User $user
     * @return bool
     * @throws Exception
     */
    public function __invoke(UpdateUserRequest $request, User $user): bool
    {
        $active = $request->boolean('active');

        if (! $active) {

            $this->ensureWitchHasSomeActiveUser();
        }

        $oldUser = clone $user;

        $user->forceFill([
            'name' => $request->input('name'),
            'lastname' => $request->input('lastname'),
            'email' => $request->input('email'),
            'client_id' => $request->input('client'),
            'active' => $request->boolean('active', $user->active)
        ]);

        if ($password = $request->input('password')) {

            $user->password = Hash::make($password);
        }

        if ($user->isDirty()) {

            $user->fill([
                'updated_by' => auth()->user()->getAuthIdentifier()
            ]);

            UpdatingUser::dispatch($user);
        }

        $user->save();

        $role = $request->input('role');

        $roleChanged = $role != $user->role?->name;

        $user->syncRoles($role);

        $wasChanged = $user->wasChanged() || $roleChanged;

        if ($wasChanged) {

            UserUpdated::dispatch($user, $oldUser);
        }

        return $wasChanged;
    }

    /**
     * @return void
     * @throws Exception
     */
    private function ensureWitchHasSomeActiveUser(): void
    {
        if (! User::query()->where('active', true)->count()) {

            throw new Exception(__('Can\'t update the user because no has more actives users.'));
        }
    }
}
