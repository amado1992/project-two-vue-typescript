<?php

namespace Modules\Providers\Application;

use Illuminate\Support\Collection;
use Modules\Providers\Entities\Provider;

/**
 * @author cheynerpb.
 */
class ReadProvidersUseCase
{

    /**
     * @return Collection<Provider>
     */
    public function __invoke(): Collection
    {
        return Provider::orderBy('name')->get();
    }}
