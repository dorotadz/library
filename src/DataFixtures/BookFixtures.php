<?php

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $book = new Book();
        $book->setTitle('Potop');
        $book->setPublisher('Wydawnictwo Sowa');
        $book->setISBN(123-45-67-89012-34);
        $book->setAuthor($this->getReference(BookFixtures::BRANCH_1));
        $manager->persist($book);

        $book = new Book();
        $book->setTitle('Pan Tadeusz');
        $book->setPublisher('Wydawnictwo ABC');
        $book->setISBN(123-45-67-89912-00);
        $manager->persist($book);

        $manager->flush();

    }
}
