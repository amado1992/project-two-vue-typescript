<?php

namespace Modules\Settings\Applications;

use Illuminate\Http\Request;
use Modules\Settings\Entities\GeneralSettings;
use Throwable;

/**
 * Create a user.
 *
 * @author Abel David.
 */
class StoreSettingUserCase
{
    public function __construct(
        private readonly GeneralSettings $settings
    )
    {
        //
    }

    /**
     * @param Request $request
     * @return bool
     * @throws Throwable
     */
    public function __invoke(Request $request): bool
    {
        $this->settings->billers = $request->input('billers');
        $this->settings->tax_itbms = $request->input('tax_itbms');
        $this->settings->expire_contract_notification = $request->input('expire_contract_notification');
        $this->settings->save();


        return true;

    }
}
