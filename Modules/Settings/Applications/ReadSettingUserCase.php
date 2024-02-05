<?php

namespace Modules\Settings\Applications;

use Illuminate\Support\Collection;
use Modules\Settings\Entities\GeneralSettings;

class ReadSettingUserCase
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
     * @return array
     */
    public function __invoke(): array
    {
        return $this->settings->toArray();
    }
}
