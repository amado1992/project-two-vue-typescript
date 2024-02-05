<?php

namespace Modules\Providers\Application;

use Modules\Providers\Entities\Provider;
use Modules\Providers\Events\DeletingProvider;
use Modules\Providers\Events\ProviderDeleted;

/**
 * @author cheynerpb.
 */
class DeleteProviderUseCase
{
    public function __invoke(Provider $provider): bool
    {
        DeletingProvider::dispatch($provider);

        $provider->delete();

        ProviderDeleted::dispatch($provider);

        return true;
    }
}
