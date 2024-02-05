<?php

namespace Modules\Contracts\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;
use Modules\Contracts\Entities\Contract;
use Modules\Settings\Applications\ReadBillersUserCase;
use Modules\Settings\Entities\GeneralSettings;

class ContractExpiration extends Notification
{
    use Queueable;

    protected bool $expired;
    protected Contract $contract;

    /**
     * Create a new notification instance.
     */
    public function __construct(Contract $contract, bool $expired = false)
    {
        $this->expired = $expired;
        $this->contract = $contract;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $readBillersUserCase = app(ReadBillersUserCase::class); //new ReadBillersUserCase();
        $generalSettings = app(GeneralSettings::class);
        $cc = $this->getCC($this->contract, $readBillersUserCase);

        $daysBeforeNotification = $generalSettings->expire_contract_notification; //setting('expire_contract_notification');

        if($this->expired) {
            $message = (new MailMessage)
                ->subject('Contrato vencido')
                ->greeting('Hola '.$notifiable->name)
                ->line('El contrato '. $this->contract->id.' acaba de vencer.')
                ->salutation('Saludos. Equipo de Mesu Alquileres')
                ->cc($cc);
        } else {
            $message = (new MailMessage)
                ->subject('Contrato cerca de vencer')
                ->greeting('Hola '.$notifiable->name)
                ->line('El contrato '. $this->contract->id.' vence el día '. Carbon::now()->addDays($daysBeforeNotification)->format('d/m/Y'))
                ->salutation('Saludos. Equipo de Mesu Alquileres')
                ->cc($cc);
        }
        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    private function getCC(Contract $contract, ReadBillersUserCase $readBillersUserCase): array
    {
        $billers = $readBillersUserCase();
        $cc = array();

        foreach ($billers as $index => $biller) {
            $cc[] = $biller->email;
        }

        $cc[] = $contract->commercial?->email;

        return array_filter($cc);
    }
}
