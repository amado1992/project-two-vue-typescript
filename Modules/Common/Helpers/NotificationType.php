<?php

namespace Modules\Common\Helpers;

/**
 * @author Abel David.
 */
enum NotificationType
{
    case Success;
    case Warning;
    case Error;
    case Info;

    public function toString(): string
    {
        return match ($this) {

            NotificationType::Success => 'positive',
            NotificationType::Warning => 'warning',
            NotificationType::Error => 'negative',
            NotificationType::Info => 'info'
        };
    }
}
