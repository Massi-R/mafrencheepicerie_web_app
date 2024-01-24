<?php

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
     * @param Request $request
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @return Response
     */
    #[Route('/register', name: 'app_register')]
    public function index(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, LoginFormAuthenticator $authenticator,): Response
    {
        //Instancier le user et le formulaire et retourner la vue
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Encodez le mot de passe avant de le stocker
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
            //'controller_name' => 'RegisterController',
            'user' => $user,
            'form' => $form->createView()
        ]);
    }
}
