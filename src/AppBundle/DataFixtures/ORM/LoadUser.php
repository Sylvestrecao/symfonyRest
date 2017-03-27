<?php
// src/AppBundle/DataFixtures/ORM/LoadUser.php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
class LoadUser implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $userDatas = array(
            array(
                'firstname' => 'John',
                'lastname'  => 'Doe',
                'email'     => 'john@doe.com'
            ),
            array(
                'firstname' => 'Marc',
                'lastname'  => 'Scott',
                'email'     => 'marc@scott.com'
            )
        );
        foreach ($userDatas as $userData){
            $user = new User();
            $user->setFirstname($userData['firstname']);
            $user->setLastname($userData['lastname']);
            $user->setEmail($userData['email']);

            $manager->persist($user);
        }

        $manager->flush();
    }
}