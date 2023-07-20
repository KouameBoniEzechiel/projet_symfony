<?php

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;
use App\Entity\Ingredients;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{

    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        for($i=1; $i<51; $i++){
            $ingredients = new Ingredients();
            $ingredients->setNom($this->faker->word())
                ->setPrice(rand(1,300));

            $manager->persist($ingredients);
        }

        $manager->flush();
      
    } 
}
