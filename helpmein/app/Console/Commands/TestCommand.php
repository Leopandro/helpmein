<?php

namespace App\Console\Commands;

use App\Domain\Task\Model\Task;
use App\Domain\TaskCategory\Model\TaskCategory;
use App\Domain\User\Model\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Password;
use Spatie\Permission\Models\Role;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

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
        $task = new Task();
        $task->name = '123';
        $task->description = '123';
        $task->comment = '123';
        $task->type = 'essay';
        $task->questions = [
            [
                'title' => 'Where is it',
                'type' => 'radio',
                'options' => [
                    [
                        'id' => 1,
                        'text' => 'Here',
                    ],
                    [
                        'id' => 2,
                        'text' => 'There',
                    ],
                    [
                        'id' => 3,
                        'text' => 'Nowhere',
                    ],
                ],
                'answer' => 2
            ]
        ];
        $task->difficult_level = 'A1';
        $task->folder_id = TaskCategory::query()->first()->id;
        $task->user_id = User::query()->first()->id;
        $task->save();
    }
}
