<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;
    private EntityManagerInterface $em;

    public function __construct(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em)
    {
        $this->passwordHasher = $passwordHasher;
        $this->em = $em;
    }

    public function load(ObjectManager $manager): void
    {
        if ($this->em->getRepository(User::class)->findOneBy(['login' => 'admin'])) {
            return;
        }

        $admin = new User();
        $admin->setLogin('admin');
        $admin->setPhone('+380000000000');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword(
            $this->passwordHasher->hashPassword($admin, '12345678')
        );

        $manager->persist($admin);
        $manager->flush();

    }
}
