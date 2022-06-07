<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

final class ImageService
{
    public function upload(UploadedFile $file, string $path = 'files')
    {
        return Storage::disk('admin')->put($path, $file);
    }
}
