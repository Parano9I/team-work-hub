<?php

namespace TeamWorkHub\Modules\Auth\Services;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use TeamWorkHub\Modules\Auth\Contracts\AvatarService;

class AvatarServiceImpl implements AvatarService
{
    private readonly Filesystem $disk;

    public function __construct(Filesystem $disk)
    {
        $this->disk = $disk;
    }

    public function getUrl(string $fileName): string
    {
        return $this->disk->url($fileName);
    }

    public function save(UploadedFile $file): string
    {
        return $this->disk->put(null, $file);
    }

    public function delete(string $fileName):bool
    {
        $this->disk->delete($fileName);

        return true;
    }
}
