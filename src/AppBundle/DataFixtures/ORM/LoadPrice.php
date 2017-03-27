<?php
// src/AppBundle/DataFixtures/ORM/LoadPrice.php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Place;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Price;
class LoadPrice implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $price = new Price();
        $price->setType("less_than_12");
        $price->setValue(6.7);

        $price2 = new Price();
        $price2->setType("all");
        $price2->setValue(7.5);

        $placeDatas = array(
            array(
                'name'    => 'Tour Eiffel',
                'address' => 'Paris',
                'price'   => $price
            ),
            array(
                'name'    => 'Big Ben',
                'address' => 'London',
                'price'   => $price2
            )
        );

        foreach ($placeDatas as $placeData){
            $place = new Place();
            $place->setName($placeData['name']);
            $place->setAddress($placeData['address']);
            $place->addPrice($placeData['price']);
            $manager->persist($place);
        }

        $manager->flush();
    }
}