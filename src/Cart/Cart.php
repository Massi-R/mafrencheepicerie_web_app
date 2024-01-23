<?php

namespace App\Cart;

use App\Entity\Product;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 *
 */
class Cart
{
    private $session;
    private $entityManager;
    private $requestStack;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
    {
        $this->session = $requestStack->getSession();
       $this->entityManager = $entityManager;
    }

    /**
     * @return array
     */
    public function getFull(): array
    {
        $cartComplete = [];

        if ($this->get()){

            foreach ($this->get() as $id => $quantity) {

                $product_object = $this->entityManager->getRepository(Product::class)->findOneById($id);
                //supprimer un produit s'il n'existe pas (par exemple un faux id rentré dans l'url)
                if (!$product_object){
                    $this->delete($id);
                    continue;
                }
                $cartComplete[] = [
                    'product' => $product_object,
                    'quantity' => $quantity
                ];
            }
        }
        return $cartComplete;
    }


    public function add($id): void
    {
        $cart = $this->session->get('cart', []);

        if (!empty($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                'id' => $id,
                'quantity' => 1
            ];
        }

        $this->session->set('cart', $cart);

    }

    public function get()
    {
        return $this->session->get('cart', []);
    }

    public function remove(): void
    {
        $this->session->remove('cart');
    }

    public function delete($id): void
    {
        $cart = $this->session->get('cart', []);

        unset($cart[$id]);

//return cart
       $this->session->set('cart', $cart);
    }

    public function decrease($id): void
    {
        $cart = $this->session->get('cart', []);

        if (isset($cart[$id]) && $cart[$id]['quantity'] > 1) {
            $cart[$id]['quantity']--;

        }else{
                unset($cart[$id]);

        }

        $this->session->set('cart', $cart);
    }

}

