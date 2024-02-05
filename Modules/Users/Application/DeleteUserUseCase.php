<?php

namespace Modules\Users\Application;

use Illuminate\Support\Facades\Auth;
use Modules\Users\Entities\User;
use Modules\Users\Events\DeletingUser;
use Modules\Users\Events\UserDeleted;

/**
 * @author Abel David.
 */
class DeleteUserUseCase
{
    public function __invoke(User $user): bool
    {
        DeletingUser::dispatch($user);

        if (Auth::user()->getAuthIdentifier() == $user->getAuthIdentifier()) {

            return false;
        }

        $user->delete();

        UserDeleted::dispatch($user);

        return true;
    }
}
