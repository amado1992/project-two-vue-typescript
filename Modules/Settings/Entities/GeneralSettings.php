<?php

namespace Modules\Settings\Entities;

/**
 * @author Abel David.
 */
class GeneralSettings extends Settings
{
    /**
     * @var array
     */
    public array $billers;

    /**
     * @var int
     */
    public int $tax_itbms;

    /**
     * @var int
     */
    public int $expire_contract_notification;

    protected function group(): string
    {
        return 'generals';
    }

    
}
