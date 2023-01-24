<?php

namespace App\Infrastructure\Media\Service;

use Spatie\MediaLibrary\Conversions\Conversion;
use Spatie\MediaLibrary\Conversions\ConversionFileNamer;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SpecialistPhotoConversionFileNamer extends ConversionFileNamer
{
    public function getFileName(Conversion $conversion, Media $media): string
    {
        $fileName = pathinfo($media->file_name, PATHINFO_FILENAME);

        $fileNameHash = md5($fileName . '-hSalt');

        return "{$fileNameHash}-{$conversion->getName()}";
    }
}
