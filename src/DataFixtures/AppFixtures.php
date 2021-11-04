<?php

namespace App\DataFixtures;

use App\Entity\Picture;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for($i=0;$i<50;$i++){
        $picture = new Picture();
        $picture->setUrl($faker->imageUrl(800,600,'project',true));
        $picture->setAuthor( $faker->firstName().' '.$faker->lastName() );
        $picture->setTitle( $faker->sentence(4) );
        $picture->setDescription( $faker->paragraphs(4,true) );
        $date = new \DateTime();
        $date->format('Y-m-d H:i:s');
        $picture->setDateCreated($date);
        $picture->setDateUpdated($date);
        $manager->persist($picture);
        }
        $manager->flush();
    }
}
