<?php

namespace Modules\Auth\Tests\Unit\Services;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Modules\Auth\Contracts\AvatarService;
use Modules\Auth\Tests\TestCase;

class AvatarServiceTest extends TestCase
{
    private AvatarService $avatarService;

    private Filesystem $storage;

    protected function setUp(): void
    {
        parent::setUp();
        $this->storage = Storage::fake('avatars');
        $this->avatarService = $this->app->make(AvatarService::class, [$this->storage]);
    }


    public function test_save()
    {
        $file = UploadedFile::fake()->image('avatar.png');

        $fileName = $this->avatarService->save($file);

        $this->storage->assertExists($fileName);
    }

    public function test_getUrl()
    {

        $avatarFileName = 'avatar.png';

        $avatar = UploadedFile::fake()->image($avatarFileName);

        $this->storage->putFileAs('', $avatar, $avatarFileName);

        $url = $this->avatarService->getUrl($avatarFileName);
        $expectedFileUrl = $this->storage->url($avatarFileName);

        $this->assertEquals($expectedFileUrl, $url);
    }

    public function test_delete()
    {
        $avatarFileName = 'avatar.png';

        $avatar = UploadedFile::fake()->image($avatarFileName);

        $this->storage->putFileAs('', $avatar, $avatarFileName);

        $fileName = $this->avatarService->delete($avatarFileName);

        $this->storage->assertMissing($fileName);
    }
}
