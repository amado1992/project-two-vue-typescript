<?php

namespace Modules\Contracts\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Notification;
use Modules\Contracts\Entities\Contract;
use Modules\Contracts\Notifications\ContractExpiration;
use Modules\Settings\Entities\GeneralSettings;
use Modules\Users\Entities\User;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class NotifyContractsExpiration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'contracts:expiration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify to the users all contracts.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        private readonly GeneralSettings $settings
    )
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        Contract::all()->each(function (Contract $contract) {

            if ($expire_at = Carbon::make($contract->expire_at)) {

                switch ($contract->status) {

                    case Contract::ACTIVE_STATUS:
                    case Contract::RENOVATED_STATUS:
                        if ($this->settings->expire_contract_notification > 0) {

                            $now = now()->addDays($this->settings->expire_contract_notification);

                            if ($now->gte($expire_at)) {

                                $this->notifyToTheUsers($contract, false);
                            }
                        }
                        break;
                    case Contract::DEFEATED_STATUS:

                        $expire_at = $expire_at->addDays(1);

                        if (now()->lte($expire_at)) {

                            $this->notifyToTheUsers($contract, true);
                        }
                        break;
                }
            }
        });
    }

    /**
     * @param Contract $contract
     * @param bool $expired
     * @return void
     */
    private function notifyToTheUsers(Contract $contract, bool $expired): void
    {
        Notification::send($contract->client->users, new ContractExpiration($contract, $expired));
    }
}
