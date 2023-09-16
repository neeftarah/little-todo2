<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Category;
use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PropertyAccess\PropertyAccess;

class CategoryTest extends TestCase
{
    public function testCanSetThenGetValues(): void
    {
        $owner = (new User())->setUsername('tester');
        $task1 = (new Task())->setTitle('Testing task 1');
        $task2 = (new Task())->setTitle('Testing task 2');

        $category = (new Category())
            ->setName('Category test')
            ->setOwner($owner)
            ->addTask($task1)
            ->addTask($task2);

        $this->assertSame('Category test', $category->getName());
        $this->assertInstanceOf(User::class, $category->getOwner());
        $this->assertSame('tester', $category->getOwner()->getUsername());
        $this->assertSame(2, count($category->getTasks()));
        $this->assertSame('Testing task 1', $category->getTasks()->get(0)->getTitle());
        $this->assertSame('Testing task 2', $category->getTasks()->get(1)->getTitle());

        // Test removing task
        $category->removeTask($task1);
        $this->assertSame(1, count($category->getTasks()));
        $this->assertNull($category->getTasks()->get(0));
        $this->assertSame('Testing task 2', $category->getTasks()->get(1)->getTitle());

        // Test private ID
        $reflection = new \ReflectionClass($category);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($category, 13);
        $this->assertSame(13, $category->getId());
    }
}
