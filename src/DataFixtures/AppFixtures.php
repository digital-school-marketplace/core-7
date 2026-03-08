<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Category;
use App\Entity\Listing;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Users
        $user1 = new User();
        $user1->setEmail('john@example.com');
        $user1->setPassword('password');
        $manager->persist($user1);

        $user2 = new User();
        $user2->setEmail('jane@example.com');
        $user2->setPassword('password');
        $manager->persist($user2);

        // Categories
        $cat1 = new Category();
        $cat1->setName('Electronics');
        $manager->persist($cat1);

        $cat2 = new Category();
        $cat2->setName('Vehicles');
        $manager->persist($cat2);

        // Listings
        for ($i = 1; $i <= 5; $i++) {
            $listing = new Listing();
            $listing->setName("Listing $i");
            $listing->setDescription("Description for listing $i");
            $listing->setPrice(rand(10, 200));

            $listing->setUser($i % 2 ? $user1 : $user2);
            $listing->setCategory($i % 2 ? $cat1 : $cat2);

            $manager->persist($listing);
        }

        $manager->flush();
    }
}
