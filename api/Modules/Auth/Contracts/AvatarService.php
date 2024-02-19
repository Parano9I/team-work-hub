<?php

namespace Modules\Auth\Contracts;

use Illuminate\Http\UploadedFile;

interface AvatarService
{
    public function save(UploadedFile $file): string;

    public function delete(string $fileName):bool;

    public function getUrl(string $fileName):string;
}
