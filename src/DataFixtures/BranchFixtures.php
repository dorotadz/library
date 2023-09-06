<?php

namespace App\DataFixtures;

use App\Entity\Branch;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BranchFixtures extends Fixture
{
    public const BRANCH_1 = 'branch-1';
    public function load(ObjectManager $manager): void
    {
        $branch = new Branch();
        $branch->setName('Filia nr 5');
        $branch->setAddress('Wiosenna 7');
        $manager->persist($branch);

        $manager->flush();

        $this->addReference(self::BRANCH_1, $branch);

    }
}
