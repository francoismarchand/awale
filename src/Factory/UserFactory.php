<?php
namespace App\Factory;

use App\Entity\User;

class UserFactory
{
    public function createGuestUser()
    {
        return (new User())
            ->setUsername('Guest' . \uniqid() . \uniqid())
            ->setRole(User::ROLE_GUEST)
            ->setDateCreation(new \DateTime())
        ;
    }
}