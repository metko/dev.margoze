<?php

namespace App\Console\Commands;

use App\Contract\Contract;
use Illuminate\Console\Command;
use App\Contract\Notifications\ContractStartInReminderNotification;

class ContractsReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contractsReminder:send {days}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a email to all contract\'s owners  who starts in 3 days';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $days = $this->argument('days');

        $this->line("Sending email to owner contracts starting in {$days} days...");
        $this->getUsers();
        $bar = $this->output->createProgressBar(count($this->users));
        $bar->start();
        $delay = now()->addSeconds(5);
        foreach ($this->users as $user) {
            $user->notify((new ContractStartInReminderNotification($user->getAttributes()))
            ->delay($delay));
            $delay->addSeconds(5);
        }
        $bar->finish();

        $this->line('
Done!');
    }

    public function getUsers()
    {
        $users = [];
        Contract::where('be_done_at', '<', now())
        ->where('be_done_at', '>', now()->subDays(3))
        ->with(['userDemand:id,email,username,avatar', 'userCandidature:id,email,username,avatar'])
        ->chunk(100, function ($contracts) use ($users) {
            foreach ($contracts as $contract) {
                $user = $contract->userDemand;
                $user = $this->getContractFields($contract, $user);
                $users[] = $user;
                $user = $contract->userCandidature;
                $user = $this->getContractFields($contract, $user);
                $users[] = $user;
                $this->users = $users;
            }
        });
    }

    public function getContractFields($contract, $user)
    {
        $user->contract_id = $contract->id;
        $user->contract_title = $contract->title;
        $user->contract_be_done_at = $contract->be_done_at;
        $user->contract_validated_at = $contract->validated_at;

        $userType = $contract->demand_owner_id == $user->id ? 'userDemand' : 'userCandidature';
        $user->is = $userType;
        $user->other_user_username = $contract->$userType->username;
        $user->other_user_id = $contract->$userType->id;
        $user->other_user_avatar = $contract->$userType->avatar;

        $user->start_in = $this->argument('days');

        return $user;
    }
}
