<?php

namespace Modules\Users\Application;

use Illuminate\Support\Collection;
use Modules\Users\Entities\User;

/**
 * @author Abel David.
 */
class ReadUsersUseCase
{
    /**
     * @return Collection<User>
     */
    public function __invoke($onlyUsers = false): Collection
    {
        $query = User::query();
        if($onlyUsers) {
            $query = $query->whereNull('client_id' );
        }
        return $query->get();
    }
}
