<?php

declare(strict_types=1);

namespace Tests\Domain\User;

use App\Domain\User\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    // private ?int $id;
    // private string $name;
    // private string $lastname;
    // private int $cc;
    // private int $gender;
    // private string $username;
    // private string $email;
    // private string $password;
    // private int $is_active;
    // private int $user_level;
    // private int $usersprivileges_id;
    // private int $is_admin;
    // private string $created_at;
    // private ?string $last_login;

    public function userProvider(): array
    {
        return [
            [1,  'Bill', 'Gates', 123456789, 1, 'bill.gates@notaria62bogota.com', 'bill.gates@notaria62bogota.com', '123456', 1, 1, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00'],
            [2,  'Steve', 'Jobs', 123456789, 1, 'steve.jobs@notaria62bogota.com', 'steve.jobs@notaria62bogota.com', '123456', 1, 1, 1, 1, '2021-01-01 00:00:00', '2021-01-01 00:00:00']
            
        ];
    }

    /**
     * @dataProvider userProvider
     * @param int    $id
     * @param string $name
     * @param string $lastName
     * @param int    $cc
     * @param int    $gender
     * @param string $username
     * @param string $email
     * @param string $password
     * @param int    $is_active
     * @param int    $user_level
     * @param int    $usersprivileges_id
     * @param int    $is_admin
     * @param string $created_at
     * @param string $last_login
     * @return void
     */
    public function testGetters(int $id, string $name, string $lastname, int $cc, int $gender, string $username, string $email, string $password, int $is_active, int $user_level, int $usersprivileges_id, int $is_admin, string $created_at, string $last_login)
    {
        $user = new User($id, $name, $lastname, $cc, $gender, $username, $email, $password, $is_active, $user_level, $usersprivileges_id, $is_admin, $created_at, $last_login);

        $this->assertEquals($id, $user->getId());
        $this->assertEquals($name, $user->getName());
        $this->assertEquals($lastname, $user->getLastName());
        $this->assertEquals($cc, $user->getCc());
        $this->assertEquals($gender, $user->getGender());
        
    }

    /**
     * @dataProvider userProvider
     * @param int    $id
     * @param string $username
     * @param string $firstName
     * @param string $lastName
     */
    public function testJsonSerialize(int $id, string $name, string $lastname, int $cc, int $gender, string $username, string $email, string $password, int $is_active, int $user_level, int $usersprivileges_id, int $is_admin, string $created_at, string $last_login)
    {
        $user = new User($id, $name, $lastname, $cc, $gender, $username, $email, $password, $is_active, $user_level, $usersprivileges_id, $is_admin, $created_at, $last_login);

        $expectedPayload = json_encode([
            'id' => $id,
            'name' => $username,
            'lastName' => $lastname,
            'cc' => $cc,
            'gender'=> $gender,
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'is_active' => $is_active,
            'user_level' => $user_level,
            'usersprivileges_id' => $usersprivileges_id,
            'is_admin' => $is_admin,
            'created_at' => $created_at,
            'last_login' => $last_login


        ]);

        $this->assertEquals($expectedPayload, json_encode($user));
    }
}
