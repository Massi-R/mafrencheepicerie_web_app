<?php

/**
*Il s'agit du fichier "ChangePasswordController.php" qui contient un contrôleur Symfony chargé de gérer la modification du mot de passe d'un utilisateur.

Le code commence par définir l'espace de noms et les utilisations nécessaires.
Ensuite, il définit une classe appelée "ChangePasswordController" qui étend la classe "AbstractController" de Symfony.

La classe a une dépendance sur l'interface "EntityManagerInterface" qui est injectée via le constructeur.
Le contrôleur a une seule méthode publique appelée "index" qui est annotée avec l'attribut #[Route] pour définir l'URL de la route.

Dans la méthode "index", le contrôleur récupère l'utilisateur actuellement connecté,
crée un formulaire de modification de mot de passe à partir de la classe "ChangePasswordType" et le lie à l'utilisateur. Le contrôleur traite ensuite la soumission du formulaire et vérifie si l'ancien mot de passe fourni correspond au mot de passe actuel de l'utilisateur.

Si l'ancien mot de passe est valide, le contrôleur hache le nouveau mot de passe, le met à jour dans l'entité utilisateur, persiste les modifications dans la base de données et ajoute un message flash de succès. Sinon, il ajoute un message flash d'erreur.

Enfin, le contrôleur renvoie la vue "password.html.twig" en passant le formulaire en tant que variable.
*
*
*/
/**
 * Ce contrôleur gère la modification du mot de passe d'un utilisateur.
 */

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ChangePasswordController extends AbstractController
{
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param Request $request
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @return Response
     */
    #[Route('/compte/modifier-mot-de-passe', name: 'app_change_password')]
    public function index(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $oldPassword = $form->get('old_password')->getData();

            if ($userPasswordHasher->isPasswordValid($user, $oldPassword)) {
                $newPassword = $form->get('new_password')->getData();
                $hashedPassword = $userPasswordHasher->hashPassword($user, $newPassword);
                $user->setPassword($hashedPassword);

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $this->addFlash('success', 'Mot de passe modifié avec succès.');
            } else {
                $this->addFlash('danger', 'Ancien mot de passe incorrect.');
            }
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
