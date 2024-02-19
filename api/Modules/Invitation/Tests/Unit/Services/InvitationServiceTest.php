<?php

namespace TeamWorkHub\Modules\Invitation\Tests\Unit\Services;

use Modules\Auth\Tests\TestCase;
use Modules\Invitation\Models\Invitation;

class InvitationServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_activationUrl_of_admin_invitation()
    {
        $invitation = Invitation::factory()->make();


    }
}
