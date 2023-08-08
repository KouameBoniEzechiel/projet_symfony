<?php

namespace App\DataFixtures;

use App\Entity\Recettes;
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
        $ingredient = [];
        for($i=1; $i<51; $i++){
            $ingredients = new Ingredients();
            $ingredients->setNom($this->faker->word())
                ->setPrice(rand(1,200));

                $ingredient[] = $ingredients;
            $manager->persist($ingredients);
        }


        for($j=1; $j<25; $j++){
            $recipe = new Recettes();
            $recipe->setNom($this->faker->word())
            ->setTime(mt_rand(0, 1) == 1 ? mt_rand(1, 1440): null)
            ->setNbrPersonne(mt_rand(0, 1) == 1 ? mt_rand(1, 50): null)
            ->setDifficulty(mt_rand(0, 1) == 1 ? mt_rand(1, 5): null)
            ->setDescription($this->faker->text(300))
                ->setPrice(mt_rand(1,1000))
                ->setIsFavorite(mt_rand(0, 1) == 1 ? true : false);
                
                for ($k=0; $k < mt_rand(5, 15); $k++) { 
                    $recipe->addIngredient($ingredient[mt_rand(0, count($ingredient) -1)]);
                }
                 
            $manager->persist($recipe);
        }

        $manager->flush();
      
    } 
}
