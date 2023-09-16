<?php

namespace App\Tests\Unit\Entity;

use App\Entity\ResetPasswordRequest;
use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class ResetPasswordRequestTest extends TestCase
{
    public function testCanSetThenGetValues(): void
    {
        $owner = (new User())->setUsername('tester');
        $expiresAt = new \DateTime('+1 day');

        $resetPasswordRequest = new ResetPasswordRequest(
            $owner,
            $expiresAt,
            'Request selector',
            'TestHashedToken'
        );

        $this->assertInstanceOf(User::class, $resetPasswordRequest->getUser());
        $this->assertSame('tester', $resetPasswordRequest->getUser()->getUsername());
        $this->assertSame($expiresAt, $resetPasswordRequest->getExpiresAt());
        $this->assertSame('TestHashedToken', $resetPasswordRequest->getHashedToken());
        $this->assertFalse($resetPasswordRequest->isExpired());

        // Test private ID
        $reflection = new \ReflectionClass($resetPasswordRequest);

        $id = $reflection->getProperty('id');
        $id->setAccessible(true);
        $id->setValue($resetPasswordRequest, 13);
        $this->assertSame(13, $resetPasswordRequest->getId());

        $selector = $reflection->getProperty('selector');
        $selector->setAccessible(true);
        $this->assertSame('Request selector', $selector->getValue($resetPasswordRequest));

        $dateExpired = new \DateTime('-1 day');
        $expiresAt = $reflection->getProperty('expiresAt');
        $expiresAt->setAccessible(true);
        $expiresAt->setValue($resetPasswordRequest, $dateExpired);
        $this->assertSame($dateExpired, $resetPasswordRequest->getExpiresAt());
        $this->assertTrue($resetPasswordRequest->isExpired());
    }
}
