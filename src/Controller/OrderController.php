<?php

namespace App\Controller;


use App\Service\CartService;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


 /**
 * Ce fichier contient la classe OrderController qui gère les commandes des utilisateurs.
 * Il utilise l'EntityManagerInterface pour gérer les entités et le MailerInterface pour envoyer des e-mails.
 * Les méthodes principales incluent la création d'une commande, l'enregistrement des détails de la commande et l'envoi d'un e-mail de confirmation.
 * Il permet également de finaliser une commande en mettant à jour le statut de paiement et de rediriger l'utilisateur vers une page de confirmation.
 */
/**
 *
 */
class OrderController extends AbstractController
{

    private EntityManagerInterface $entityManager;
    private MailerInterface $mailer;

    /**
     * Constructeur de la classe OrderController.
     * @param EntityManagerInterface $entityManager
     * @param MailerInterface $mailer
     */

    public function __construct(EntityManagerInterface $entityManager, MailerInterface $mailer)
    {
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
    }

    /**
     * @param CartService $cart
     * @param Request $request
     * @return Response
     */
    #[Route('/commande', name: 'app_order')]
    public function index(CartService $cart, Request $request): Response
    {
        if (!$this->getUser()->getAddresses()->getValues())
        {
            return $this->redirectToRoute('app_account_address_add');
        }
        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser(),
        ]);

        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart->getFull()
        ]);

    }

    /**
     * Méthode add pour enregistrer une nouvelle commande.
     * @param CartService $cart
     * @param Request $request
     * @return Response
     * @throws TransportExceptionInterface
     */
    #[Route('/commande/recapitulatif', name: 'app_order_recap')]
    public function add(CartService $cart, Request $request): Response
    {
// Crée le formulaire de commande en
        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser(),
        ]);

        $carriers = null;
        $form->handleRequest($request);
// Vérifie si le formulaire a été soumi
        if ($form->isSubmitted() && $form->isValid()) {
// Récupère la date actuelle
            $date = new DateTime();
// Récupère le transporteur sélectionné dans le formulaire
            $carriers = $form->get('carriers')->getData();
// Récupère l'adresse de livraison sélectionnée dans le formulaire
            $delivery = $form->get('addresses')->getData();
            $delivery_content = $delivery->getFirstname().' '.$delivery->getLastname();
            $delivery_content.='<br>'.$delivery->getPhone();
                        // Ajoute le nom de la société si elle est renseignée

            if ($delivery->getCompany()){
                $delivery_content.='<br>'.$delivery->getCompany();
            }
            $delivery_content.='<br>'.$delivery->getAddress();
            $delivery_content.='<br>'.$delivery->getPostal().' '.$delivery->getCity();
            $delivery_content.='<br>'.$delivery->getCountry();

            // Enregistre la nouvelle commande
            $order = new Order();
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setCarrierName($carriers->getName());
            $order->setCarrierPrice($carriers->getPrice());
            $order->setDelivery($delivery_content);
            $order->setIsPaid(0);

            $this->entityManager->persist($order);

            // Enregistre les détails de la commande pour chaque produit du panier
            foreach ($cart->getFull() as $product) {
                $orderDetails = new OrderDetails();
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($product['product']->getName());
                $orderDetails->setQuantity((int) $product['quantity']);
                $orderDetails->setPrice($product['product']->getPrice());
                $orderDetails->setTotal($product['product']->getPrice() * (int)$product['quantity']);

                $this->entityManager->persist($orderDetails);
            }
            // Enregistrez les modifications en base de données
            $this->entityManager->flush();

            // Envoyez un e-mail de confirmation à l'utilisateur
           // $this->sendOrderConfirmationEmail($order);

            // Redirigez l'utilisateur vers la page de finalisation avec l'ID de la commande en paramètre
            return $this->redirectToRoute('app_order_finalize.html.twig', ['order_id' => $order->getId()]);
        }

        // Redirige l'utilisateur vers le panier s'il y a une erreur dans le formulaire
        return $this->redirectToRoute('app_cart');
    }
    /**
     * Méthode pour envoyer un e-mail de confirmation de commande.
     * @param Order $order
     * @throws TransportExceptionInterface
     */
    private function sendOrderConfirmationEmail(Order $order): void
    {
        $userEmail = $order->getUser()->getEmail();
        $email = (new Email())
            ->from('votre_email@example.com') // Remplacez par votre adresse e-mail
            ->to($userEmail)
            ->subject('Confirmation de commande')
            ->html($this->renderView('email/order_confirmation.html.twig', ['order' => $order]));

        $this->mailer->send($email);
    }

    /**
     * Méthode finalizeOrder pour finaliser une commande.
     * @param Request $request
     * @return Response
     */
    #[Route('/commande/finaliser', name: 'app_order_finalize.html.twig')]
    public function finalizeOrder(Request $request, SessionInterface $session): Response
    {
        // Récupère l'ID de la commande depuis la requête
        $orderId = $request->query->get('order_id');

        // Vérifie si l'ID de la commande est valide
        if (!$orderId) {
            // Affiche un message flash d'erreur et redirige vers la page d'accueil
            $this->addFlash('error', 'L\'ID de commande est manquant ou invalide.');
            return $this->redirectToRoute('app_home');
        }

        // Récupère la commande depuis la base de données en utilisant l'ID
        $order = $this->entityManager->getRepository(Order::class)->find($orderId);

        // Vérifie que l'utilisateur actuel est bien le propriétaire de la commande
        if ($order && $order->getUser() === $this->getUser()) {
            // Met à jour le statut de paiement de la commande (par exemple, en le définissant sur 1 pour indiquer le paiement effectué)
            $order->setIsPaid(1);

            // Enregistre les modifications en base de données
            $this->entityManager->persist($order);
            $this->entityManager->flush();

            // Redirige l'utilisateur vers une page de confirmation de paiement
            return $this->render('order/confirmation.html.twig', [
                'order' => $order,
            ]);
        }

        // La commande n'existe pas ou l'utilisateur n'est pas autorisé
        // Affiche un message flash d'erreur et redirige vers la page d'accueil
        $this->addFlash("error, La commande n'existe pas ou vous n'êtes pas autorisé à la finaliser.");

        return $this->redirectToRoute('app_home');
    }

// vérifie l'absence ou l'invalidité de l'ID de commande et redirige l'utilisateur vers la page d'accueil avec un message flash approprié dans ces cas.

}
