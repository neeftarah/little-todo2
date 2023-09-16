<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Tag;
use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class TagTest extends TestCase
{
    public function testCanSetThenGetValues(): void
    {
        $owner = (new User())->setUsername('tester');
        $task1 = (new Task())->setTitle('Testing task 1');
        $task2 = (new Task())->setTitle('Testing task 2');

        $tag = (new Tag())
            ->setName('Tag test')
            ->setColor('#FF0000')
            ->setOwner($owner)
            ->addTask($task1)
            ->addTask($task2);

        $this->assertSame('Tag test', $tag->getName());
        $this->assertSame('#FF0000', $tag->getColor());
        $this->assertInstanceOf(User::class, $tag->getOwner());
        $this->assertSame('tester', $tag->getOwner()->getUsername());
        $this->assertSame(2, count($tag->getTasks()));
        $this->assertSame('Testing task 1', $tag->getTasks()->get(0)->getTitle());
        $this->assertSame('Testing task 2', $tag->getTasks()->get(1)->getTitle());

        // Test removing task
        $tag->removeTask($task1);
        $this->assertSame(1, count($tag->getTasks()));
        $this->assertNull($tag->getTasks()->get(0));
        $this->assertSame('Testing task 2', $tag->getTasks()->get(1)->getTitle());

        // Test private ID
        $reflection = new \ReflectionClass($tag);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($tag, 13);
        $this->assertSame(13, $tag->getId());
    }
}
