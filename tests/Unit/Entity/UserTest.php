<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Category;
use App\Entity\Tag;
use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;
use Symfony\Polyfill\Intl\Icu\Exception\MethodNotImplementedException;

class UserTest extends TestCase
{
    public function testCanSetThenGetValues(): void
    {
        $tag1 = (new Tag())->setName('Testing tag 1');
        $tag2 = (new Tag())->setName('Testing tag 2');
        $category1 = (new Category())->setName('Testing category 1');
        $category2 = (new Category())->setName('Testing category 2');
        $task1 = (new Task())->setTitle('Testing task 1');
        $task2 = (new Task())->setTitle('Testing task 2');

        $user = (new User())
            ->setUsername('User name')
            ->setRoles(['ROLE_TESTER'])
            ->setEmail('tester@test.com')
            ->setPassword('HisSuperP@ssw0rd')
            ->addTag($tag1)
            ->addTag($tag2)
            ->addCategory($category1)
            ->addCategory($category2)
            ->addTask($task1)
            ->addTask($task2);

        $this->assertSame('User name', $user->getUsername());
        $this->assertSame('User name', $user->getUserIdentifier());
        $this->assertSame(2, count($user->getRoles()));
        $this->assertContains('ROLE_TESTER', $user->getRoles());
        $this->assertContains('ROLE_USER', $user->getRoles());
        $this->assertSame('tester@test.com', $user->getEmail());
        $this->assertSame('HisSuperP@ssw0rd', $user->getPassword());
        $this->assertFalse($user->isVerified());
        $this->assertNotEmpty($user->getUuid());
        $this->assertTrue(Uuid::isValid($user->getUuid()));
        $this->assertSame(2, count($user->getTags()));
        $this->assertSame('Testing tag 1', $user->getTags()->get(0)->getName());
        $this->assertSame('Testing tag 2', $user->getTags()->get(1)->getName());
        $this->assertSame(2, count($user->getCategories()));
        $this->assertSame('Testing category 1', $user->getCategories()->get(0)->getName());
        $this->assertSame('Testing category 2', $user->getCategories()->get(1)->getName());
        $this->assertSame(2, count($user->getTasks()));
        $this->assertSame('Testing task 1', $user->getTasks()->get(0)->getTitle());
        $this->assertSame('Testing task 2', $user->getTasks()->get(1)->getTitle());

        $user->setIsVerified(true);
        $this->assertTrue($user->isVerified());

        $uuid = Uuid::v4();
        $user->setUuid($uuid);
        $this->assertSame($uuid, $user->getUuid());

        // Test removing tag
        $user->removeTag($tag1);
        $this->assertSame(1, count($user->getTags()));
        $this->assertNull($user->getTags()->get(0));
        $this->assertSame('Testing tag 2', $user->getTags()->get(1)->getName());

        // Test removing category
        $user->removeCategory($category1);
        $this->assertSame(1, count($user->getCategories()));
        $this->assertNull($user->getCategories()->get(0));
        $this->assertSame('Testing category 2', $user->getCategories()->get(1)->getName());

        // Test removing task
        $user->removeTask($task1);
        $this->assertSame(1, count($user->getTasks()));
        $this->assertNull($user->getTasks()->get(0));
        $this->assertSame('Testing task 2', $user->getTasks()->get(1)->getTitle());

        // Test private ID
        $reflection = new \ReflectionClass($user);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($user, 13);
        $this->assertSame(13, $user->getId());

        // Do nothing
        $user->eraseCredentials();
    }
}
