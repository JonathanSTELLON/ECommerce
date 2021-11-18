<?php

namespace App\DataFixtures;

use Faker\Factory;
use DateTimeImmutable;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\User;
use Bluemmb\Faker\PicsumPhotosProvider;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture{

    private $slugger;
    private $hasher;

    public function __construct(SluggerInterface $slugger, UserPasswordHasherInterface $hasher){
        
        $this->slugger = $slugger;
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void{

        //Création de l'objet Faker
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new PicsumPhotosProvider($faker));

        $categories = [
            'Vins',
            'Spiritueux',
            'Bieres',
        ];

        $arrayCat = [];

        foreach ($categories as $category){
            //On crée une nouvelle catégorie en bouclant dans le tableau
            $cat = new Category();
            $cat->setName($category);
            $cat->setCreatedAt(new DateTimeImmutable());
            array_push($arrayCat, $cat);
            $cat->setSlug($this->slugger->slug($cat->getName()));
            $manager->persist($cat);
        }

        for ($i = 0; $i < 20; $i++){

            //On crée 20 nouveaux produits
            $product = new Product();
            $product->setName($faker->word(2));
            $product->setCategory($faker->randomElement($arrayCat));
            $product->setPrice($faker->numberBetween(10,50));
            $product->setDescription($faker->text(2500));
            $product->setThumbnail($faker->imageUrl(300, 200, true));
            $product->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-3 years', 'now', 'Europe/Paris')));
            $product->setSlug($this->slugger->slug($product->getName()));
            $manager->persist($product);
        }

        for ($i = 0; $i < 10; $i++){

            //On crée 10 nouveaux users
            $user = new User();
            $user->setFirstname($faker->firstName());
            $user->setLastname($faker->lastname());
            $user->setEmail('user'.$i.'@gmail.com');
            $user->setPassword($this->hasher->hashPassword($user, 'password'));
            $manager->persist($user);
        }

        //Le flush éxécute le SQL, on ne le fait qu'une fois
        $manager->flush();
    }
}
