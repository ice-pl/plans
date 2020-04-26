<?php

namespace App\Entity;

use PHPUnit\Framework\TestCase;



class UserTest extends TestCase
{

    protected $user;

    public function setUp() :void
    {
        $this->user = new User();
    }


    /** @test */
    public function User_Variables()
    {

        $this->user
            ->setUserName("Name")
            ->setRoles($this->user->getRoles())
            ->setPassword('uuu');
        $this->assertEquals("Name", $this->user->getUserName());
        $this->assertEquals(["ROLE_USER"], $this->user->getRoles());
        $this->assertEquals("uuu", $this->user->getPassword());
    }












}