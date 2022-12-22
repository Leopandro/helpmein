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
    protected $signature = 'command:create_nodes';

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
        /** @var User $user */
        $user = User::query()->firstOrFail();
        $node = new TaskCategory([
            'name' => 'Koren2',
            'user_id' => $user->id
        ]);
        $node->makeRoot()->save();
        $node->children()->create([
            'name' => 'Children3',
            'user_id' => $user->id
        ]);
        /** @var TaskCategory $children */
        $children = $node->children()->create([
            'name' => 'Test2',
            'user_id' => $user->id
        ]);
        $children->children()->create([
            'name' => 'Children children',
            'user_id' => $user->id
        ]);
        $pause = 1;
    }
}
