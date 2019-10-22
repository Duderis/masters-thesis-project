<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('user@example.com');
        $user->setPlainPassword('123456789');
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);

        $adminUser = new User();
        $adminUser->setEmail('admin@example.com');
        $adminUser->setPlainPassword('123456789');
        $adminUser->setRoles(['ROLE_ADMIN']);
        $manager->persist($adminUser);

        $manager->flush();
    }
}
