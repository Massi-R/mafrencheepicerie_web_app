<?php

namespace App\Controller;

use App\Service\CartService;
use App\Entity\Address;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *
 */
class AccountAddressController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @param $entityManager
     */
    public function __construct( EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @return Response
     */
    #[Route('/compte/addresses', name: 'app_account_address')]
    public function index(): Response
    {
        return $this->render('account/address.html.twig', [
            'controller_name' => 'AccountAddressController',
        ]);
    }
    /**
     * @param CartService $cart
     * @param Request $request
     * @return Response
     */
    #[Route('/compte/ajouter-une-addresse', name: 'app_account_address_add')]
    public function addAdress(CartService $cart, Request $request ): Response
    {
        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $address->setUser($this->getUser());
            $this->entityManager->persist($address);
            $this->entityManager->flush();
            if ($cart->get()){
                return $this->redirectToRoute('app_order');
            }else{
                return $this->redirectToRoute('app_account_address');
            }
        }
        return $this->render('account/address_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @param Request $request
     * @param $id
     * @return Response
     */
    #[Route('/compte/modifier-une-addresse/{id}', name: 'app_account_address_edit')]
    public function edit(Request $request, $id): Response
    {
        $address = $this->entityManager->getRepository(Address::Class)->findOneById($id);
        if (!$address || $address->getUser() != $this->getUser()){
            return $this->redirectToRoute('app_account_address');
        }
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $this->entityManager->flush();
                // Ajout d'un message de succès
                $this->addFlash('success', 'Adresse mise à jour avec succès.');
                return $this->redirectToRoute('app_account_address');
            } catch (\Exception $e) {
                // afficher l'erreur
                $this->addFlash('error', "Erreur lors de la mise à jour de l'adresse  ");
            }
        }

        return $this->render('account/address_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @param $id
     * @return Response
     */
    #[Route('/compte/supprimer-une-addresse/{id}', name: 'app_account_address_delete')]
    public function delete($id): Response
    {
        $address = $this->entityManager->getRepository(Address::Class)->findOneById($id);
        if ($address && $address->getUser() == $this->getUser()){
            $this->entityManager->remove($address);
            $this->entityManager->flush();

            //afficher un message:
            $this->addFlash('success', 'Adresse supprimée avec succès.');
        }
        return $this->redirectToRoute('app_account_address');
    }
}
