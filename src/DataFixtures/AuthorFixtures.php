<?php

namespace App\DataFixtures;

use App\Entity\Author;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AuthorFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $author = new Author();
        $author->setFirstName('Henryk');
        $author->setLastName('Sienkiewicz');
        $manager->persist($author);

        $author = new Author();
        $author->setFirstName('Adam');
        $author->setLastName('Mickiewicz');
        $manager->persist($author);

        $manager->flush();

    }
}
