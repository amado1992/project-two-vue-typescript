<?php

namespace Modules\Providers\Application;

use Illuminate\Support\Collection;
use Modules\Providers\Entities\Provider;


class ReadProviderContactUseCase
{


    public function __invoke(Provider $provider)
    {
        return Provider::all()->where('id','=',$provider->id)->only(['contacts']);
    }}
