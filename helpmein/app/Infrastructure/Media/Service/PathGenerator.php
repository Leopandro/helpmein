<?php

declare(strict_types=1);

namespace App\Infrastructure\Media\Service;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\DefaultPathGenerator;

/**
 * Генератор путей для сохранения файлов
 * Использует название коллекции в качестве названия папки
 */
class PathGenerator extends DefaultPathGenerator
{
    /*
     * Get a unique base path for the given media.
     */
    protected function getBasePath(Media $media): string
    {
        return sprintf('/%s/%s', $media->collection_name, $media->getKey());
    }
}
