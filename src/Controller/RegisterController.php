<?php
/**
*Ce code est le contrôleur d'inscription.
Il gère le processus d'inscription des utilisateurs.
Lorsque l'utilisateur soumet le formulaire d'inscription,
le code vérifie la validité des données, encode le mot de passe de l'utilisateur,
persiste l'utilisateur dans la base de données et redirige l'utilisateur vers la page de connexion.
Le code utilise également des services Symfony
tels que UserPasswordHasherInterface et UserAuthenticatorInterface pour gérer l'authentification de l'utilisateur.
*
*/
namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegisterController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Gère le processus d'inscription des utilisateurs.
     *
     * @param Request $request
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @param UserAuthenticatorInterface $userAuthenticator
     * @param LoginFormAuthenticator $authenticator
     * @return Response
     */
    #[Route('/register', name: 'app_register')]
    public function index(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, LoginFormAuthenticator $authenticator): Response
    {
        // Instancie le user et le formulaire et retourne la vue
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode le mot de passe avant de le stocker
            $user->setPassword(
                $userPasswordHasher->hashPassword($user, $user->getPassword())
            );

            $user = $form->getData();
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash('success', 'Inscription réussie. Connectez-vous maintenant.');

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('register/index.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }
}
