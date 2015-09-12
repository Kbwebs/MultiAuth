<?php

namespace Kbwebs\MultiAuth\Console;

use Illuminate\Console\Command;

class ClearResetsTableCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'kbwebs:multi-auth:clear-resets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flush expired password reset tokens';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $this->laravel['auth.password.tokens']->deleteExpired();
        $this->info('Expired reset tokens cleared!');
    }
}