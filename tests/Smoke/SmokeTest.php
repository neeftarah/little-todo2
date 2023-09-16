<?php

namespace App\Tests\Smoke;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SmokeTest extends WebTestCase
{
    /**
     * @dataProvider publicUrlProvider
     */
    public function testUrlAccessibilityWithoutAuthentication(string $uri, int $expectedStatusCode): void
    {
        $client = static::createClient();
        $client->request('GET', $uri);
        $this->assertResponseStatusCodeSame($expectedStatusCode);
    }

    public function publicUrlProvider(): \Generator
    {
        yield 'URL = /' => ['/', 302];
        yield 'URL = /login' => ['/login', 200];
        yield 'URL = /logout' => ['/logout', 302];
        yield 'URL = /register' => ['/register', 200];
        yield 'URL = /reset-password' => ['/reset-password', 200];
        yield 'URL = /reset-password/check-email' => ['/reset-password/check-email', 200];
    }

    /**
     * @dataProvider authenticatedUrlProvider
     */
    public function testUrlAccessibilityWithAuthentedUser(string $uri, int $expectedStatusCode): void
    {
        $client = static::createClient();

        // retrieve the test user
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('tester@app-test.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $client->request('GET', $uri);
        $this->assertResponseStatusCodeSame($expectedStatusCode);
    }

    public function authenticatedUrlProvider(): \Generator
    {
        yield 'URL = /' => ['/', 200];
        yield 'URL = /login' => ['/login', 200];
        yield 'URL = /logout' => ['/logout', 302];
        yield 'URL = /register' => ['/register', 200];
        yield 'URL = /reset-password' => ['/reset-password', 200];
        yield 'URL = /reset-password/check-email' => ['/reset-password/check-email', 200];
    }
}
