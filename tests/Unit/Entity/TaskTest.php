<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Category;
use App\Entity\Tag;
use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function testCanSetThenGetValues(): void
    {
        $owner = (new User())->setUsername('tester');
        $tag1 = (new Tag())->setName('Testing tag 1');
        $tag2 = (new Tag())->setName('Testing tag 2');
        $category = (new Category())->setName('Testing task category');

        $dtCreation = new \DateTimeImmutable('2023-09-16');
        $dtUpdate = new \DateTimeImmutable('2023-09-17');
        $dueDate = new \DateTimeImmutable('2023-09-25');

        $task = (new Task())
            ->setTitle('Task title')
            ->setDescription('A short description for the task...')
            ->setPriority(1)
            ->setOwner($owner)
            ->setCategory($category)
            ->setCreatedAt($dtCreation)
            ->setUpdatedAt($dtUpdate)
            ->setDueDate($dueDate)
            ->setOwner($owner)
            ->addTag($tag1)
            ->addTag($tag2);

        $this->assertSame('Task title', $task->getTitle());
        $this->assertSame('A short description for the task...', $task->getDescription());
        $this->assertSame(1, $task->getPriority());
        $this->assertInstanceOf(User::class, $task->getOwner());
        $this->assertSame('tester', $task->getOwner()->getUsername());
        $this->assertInstanceOf(Category::class, $task->getCategory());
        $this->assertSame('Testing task category', $task->getCategory()->getName());
        $this->assertEquals($dtCreation, $task->getCreatedAt());
        $this->assertEquals($dtUpdate, $task->getUpdatedAt());
        $this->assertEquals($dueDate, $task->getDueDate());
        $this->assertSame(2, count($task->getTags()));
        $this->assertSame('Testing tag 1', $task->getTags()->get(0)->getName());
        $this->assertSame('Testing tag 2', $task->getTags()->get(1)->getName());
        $this->assertFalse($task->isDone());

        $task->setDone(true);
        $this->assertTrue($task->isDone());

        // Test private ID
        $reflection = new \ReflectionClass($task);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($task, 13);
        $this->assertSame(13, $task->getId());


        // Test removing tag
        $task->removeTag($tag1);
        $this->assertSame(1, count($task->getTags()));
        $this->assertNull($task->getTags()->get(0));
        $this->assertSame('Testing tag 2', $task->getTags()->get(1)->getName());
    }
}
