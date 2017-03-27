<?php
// src/AppBundle/DataFixtures/ORM/LoadPlace.php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Place;
class LoadPlace implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $placeDatas = array(
            array(
                'name'    => 'Taj mâle',
                'address' => 'Inde'
            ),
            array(
                'name'    => 'Status liberté',
                'address' => 'US'
            )
        );
        foreach ($placeDatas as $placeData){
            $place = new Place();
            $place->setName($placeData['name']);
            $place->setAddress($placeData['address']);

            $manager->persist($place);
        }

        $manager->flush();
    }
}