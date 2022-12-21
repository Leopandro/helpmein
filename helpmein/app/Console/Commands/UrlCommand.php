<?php

namespace App\Console\Commands;

use App\Domain\TaskCategory\Model\TaskCategory;
use App\Domain\User\Model\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Password;
use Spatie\Permission\Models\Role;

class UrlCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:url';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $url = route('frontend.support');
        echo $url;
    }
}
