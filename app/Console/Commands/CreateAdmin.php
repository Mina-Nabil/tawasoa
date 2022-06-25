<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:admin {username} {fullname} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates new admin from command line';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $user = User::newUser($this->argument('username'), $this->argument('fullname'), User::TYPE_ADMIN, $this->argument('password'));
        if ($user) {
            echo "User Created";
        } else {
            echo "Creation failed";
        }
        return 0;
    }
}
