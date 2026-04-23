<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SuperAdminFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(
        UserPasswordHasherInterface $hasher,
    ) {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $superAdmin = $this->createSuperAdmin();
        $manager->persist($superAdmin);
        $manager->flush();
    }

    /**
     * Permet de créer le super admin.
     */
    private function createSuperAdmin(): User
    {
        $superAdmin = new User();

        $superAdmin->setFirstName('Cheikh');
        $superAdmin->setLastName('Dieye');
        $superAdmin->setEmail('cybertarantula9@gmail.com');
        $superAdmin->setRoles(['ROLE_SUPER_ADMIN', 'ROLE_ADMIN', 'ROLE_USER']);
        $superAdmin->setPassword('');

        $passwordHashed = $this->hasher->hashPassword($superAdmin, 'mewtwo40');
        $superAdmin->setPassword($passwordHashed);

        $superAdmin->setIsVerified(true);

        $superAdmin->setCreatedAt(new \DateTimeImmutable());
        $superAdmin->setUpdatedAt(new \DateTimeImmutable());
        $superAdmin->setCreatedAt(new \DateTimeImmutable());

        return $superAdmin;
    }
}
