<?php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\User;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Créer un utilisateur normal
        $user = new User();
        $user->setEmail('user@example.com');
        $user->setPassword(password_hash('password', PASSWORD_DEFAULT));
        $user->setRoles(['ROLE_USER']);
        $user->setFirstname('John');
        $user->setLastname('Doe');

        $manager->persist($user);
        $this->addReference('user', $user); // Ajouter une référence pour l'utilisateur normal

        // Créer un administrateur
        $admin = new User();
        $admin->setEmail('admin@example.com');
        $admin->setPassword(password_hash('admin_password', PASSWORD_DEFAULT));
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setFirstname('Admin');
        $admin->setLastname('User');

        $manager->persist($admin);
        $this->addReference('admin', $admin); // Ajouter une référence pour l'administrateur

        $manager->flush();
    }
}