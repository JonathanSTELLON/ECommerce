<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use DateTimeImmutable;
use App\Entity\Product;
use App\Entity\Category;
use App\Avatar\AvatarHelper;
use App\Avatar\AvatarSvgFactory;
use Bluemmb\Faker\PicsumPhotosProvider;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AppFixtures extends Fixture{

    private $slugger;
    private $hasher;
    private $avatarSvgFactory;
    private $avatarHelper;

    public function __construct(SluggerInterface $slugger, UserPasswordHasherInterface $hasher, AvatarSvgFactory $avatarSvgFactory, AvatarHelper $avatarHelper){
        
        $this->slugger = $slugger;
        $this->hasher = $hasher;
        $this->avatarSvgFactory = $avatarSvgFactory;
        $this->avatarHelper = $avatarHelper;
    }

    public function load(ObjectManager $manager): void{

        //On supprime le dossier d'avatars quand on relance d:f:l
        $this->avatarHelper->removeAvatarFolder();

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
            $product->setPrice($faker->numberBetween(1000,5000));
            $product->setDescription($faker->text(2500));
            $product->setThumbnail($faker->imageUrl(300, 200, true));
            $product->setCreatedAt(DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-3 years', 'now', 'Europe/Paris')));
            $product->setSlug($this->slugger->slug($product->getName()));
            $manager->persist($product);
        }

        $size = AvatarSvgFactory::DEFAULT_SIZE;
        $color = AvatarSvgFactory::DEFAULT_NB_COLORS;
    
        for ($i = 0; $i < 10; $i++){

            //Création des avatars
            $svg = $data['svg'] ?? $this->avatarSvgFactory->createRandomAvatar($size,$color);

            //On crée 10 nouveaux users
            $user = new User();
            $user->setFirstname($faker->firstName());
            $user->setLastname($faker->lastname());
            $user->setEmail('user'.$i.'@gmail.com');
            $user->setPassword($this->hasher->hashPassword($user, 'password'));
            $user->setAvatar($this->avatarHelper->saveSvg($svg));
            $createdAt = $faker->dateTimeBetween('-5 years', 'now');
            $user->setCreatedAt(DateTimeImmutable::createFromMutable($createdAt));
            $manager->persist($user);

        }

        //On Crée 1 admin

        $svg = $data['svg'] ?? $this->avatarSvgFactory->createRandomAvatar($size,$color);

        $admin = new User;
        $admin->setFirstname('Big');
        $admin->setLastname('Brother');
        $admin->setEmail('admin@gmail.com');
        $admin->setPassword($this->hasher->hashPassword($admin, 'password'));
        $admin->setAvatar($this->avatarHelper->saveSvg($svg));
        $admin->setRoles(['ROLE_ADMIN']);
        $createdAt = $faker->dateTimeBetween('-5 years', 'now');
        $admin->setCreatedAt(DateTimeImmutable::createFromMutable($createdAt));
        $manager->persist($admin);

        //Le flush éxécute le SQL, on ne le fait qu'une fois
        $manager->flush();
    }
}
