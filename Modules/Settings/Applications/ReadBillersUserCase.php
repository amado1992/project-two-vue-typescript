<?php

namespace Modules\Settings\Applications;

use Illuminate\Support\Collection;
use Modules\Settings\Entities\GeneralSettings;
use Modules\Users\Entities\User;

/**
 * Create a user.
 *
 * @author Abel David.
 */
class ReadBillersUserCase
{
    /**
     * @param GeneralSettings $settings
     */
    public function __construct(
        private readonly GeneralSettings $settings
    )
    {
        //
    }

    /**
     * @return Collection
     */
    public function __invoke(): Collection
    {

        $billermapped = array_map(function ($e){
               return is_array($e) ? $e['id'] : $e;
        },$this->settings->billers);
        return User::whereIn('id', $billermapped)->get();
    }
}
