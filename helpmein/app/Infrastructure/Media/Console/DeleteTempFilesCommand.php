<?php

namespace App\Infrastructure\Media\Console;

use App\Infrastructure\ConsoleCommand\BaseConsoleCommand;
use App\Infrastructure\Media\Model\Media;
use App\Infrastructure\Model\File\TmpEntity;
use Illuminate\Database\Eloquent\Collection;

class DeleteTempFilesCommand extends BaseConsoleCommand
{
    protected $signature = 'media:delete-temp-files';

    protected $description = 'Удаляет временные файлы';

    /**
     * @throws \Exception
     */
    public function handleInternal(): void
    {
        /** @var Media[]|Collection $media */
        $media = Media::query()
            ->where('model_id', TmpEntity::MODEL_UUID)
            ->where('created_at', '<', now()->subDays(10))
            ->get();

        $this->progressBar($media->count());

        foreach ($media as $mediaItem) {
            $mediaItem->delete();
            $this->progressBarAdvance();
        }

        $this->progressBarFinish();
    }
}
