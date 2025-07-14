<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class SyncUserRoles extends Command
{
    protected $signature = 'users:sync-roles';
    protected $description = 'Sync users enum roles with Spatie roles';

    public function handle()
    {
        $users = User::all();
        $bar = $this->output->createProgressBar(count($users));

        $this->info('Starting role sync...');

        foreach ($users as $user) {
            if ($user->role) {
                $user->syncRoles([$user->role]);
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Roles synced successfully!');
    }
}
