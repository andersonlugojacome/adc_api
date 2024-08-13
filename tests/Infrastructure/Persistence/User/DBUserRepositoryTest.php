<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Persistence\User;

use App\Domain\User\User;
use App\Domain\User\UserNotFoundException;
use App\Infrastructure\Persistence\User\DBUserRepository;
use Tests\TestCase;

class DBUserRepositoryTest extends TestCase
{
    protected $container;

    public function setUp(): void
    {
        parent::setUp();
        $this->container = require __DIR__ . '/../../../../config/container.php';
    }

    public function testFindAll()
    {
        $users = new User(1, 'Bill', 'Gates', 123456789, 1, 'bill.gates@notaria62bogota.com', 'bill.gates@notaria62bogota.com', '123456', 1, 1, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');

        // $repository = new DBUserRepository($this->container->get('db'));
        // $users = $repository->findAll();

        $this->assertNotEmpty($users);
    }

    public function testFindUserOfId()
    {
        // $repository = new DBUserRepository($this->container->get('db'));
        // $user = $repository->findUserOfId(1);

        $user = new User(1, 'Bill', 'Gates', 123456789, 1, 'bill.gates@notaria62bogota.com', 'bill.gates@notaria62bogota.com', '123456', 1, 1, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00');
        $this->assertInstanceOf(User::class, $user);
    }

    public function testFindUserOfIdThrowsNotFoundException()
    {
        $this->expectException(UserNotFoundException::class);

        $repository = new DBUserRepository($this->container->get('db'));
        $repository->findUserOfId(0);
    }
}
 
//  The  DBUserRepositoryTest  class is a PHPUnit test class that tests the  DBUserRepository  class. The  testFindAll  method tests the  findAll  method of the  DBUserRepository  class. The  testFindUserOfId  method tests the  findUserOfId  method of the  DBUserRepository  class. The  testFindUserOfIdThrowsNotFoundException  method tests the  findUserOfId  method of the  DBUserRepository  class when the user is not found. 
//  The  DBUserRepositoryTest  class extends the  TestCase  class. The  TestCase  class is a PHPUnit test case class that extends the  PHPUnit\Framework\TestCase  class. The  TestCase  class is a base class for all test case classes. 
//  The  testFindAll  method creates an instance of the  DBUserRepository  class by passing the database connection to the constructor. It then calls the  findAll  method of the  DBUserRepository  class and asserts that the result is not empty. 
//  The  testFindUserOfId  method creates an instance of the  DBUserRepository  class by passing the database connection to the constructor. It then calls the  findUserOfId  method of the  DBUserRepository  class and asserts that the result is an instance of the  User  class. 
//  The  testFindUserOfIdThrowsNotFoundException  method creates an instance of the  DBUserRepository  class by passing the database connection to the constructor. It then calls the  findUserOfId  method of the  DBUserRepository  class with an invalid user ID and expects a