<?php

declare(strict_types=1);

namespace App\Infrastructure\Media\Service;

use App\Infrastructure\Media\Model\Media;

class MediaManager
{
    /**
     * Получает содержимое файла
     * @param Media $media
     * @return string
     */
    public function getMediaContent(Media $media): string
    {
        $stream = $media->stream();

        $mediaContent = '';

        while (! feof($stream)) {
            $mediaContent .= fgets($stream, 1024);
        }

        return $mediaContent;
    }
}
