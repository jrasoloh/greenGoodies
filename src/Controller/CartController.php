<?php

namespace App\Controller;

use App\Entity\User;
use App\Manager\OrderManager;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(CartService $cartService): Response
    {
        $cart = $cartService->getFullCart();

        return $this->render('cart/show.html.twig', [
            'items' => $cart['items'],
            'total' => $cart['total']
        ]);
    }

    #[Route('/cart/add/{id}', name: 'app_cart_add', methods: ['POST'])]
    public function add(int $id, Request $request, CartService $cartService): Response
    {
        $quantity = $request->request->getInt('quantity', 1);
        $cartService->add($id, $quantity);

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/empty', name: 'app_cart_empty', methods: ['POST'])]
    public function empty(CartService $cartService): Response
    {
        $cartService->empty();
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/validate', name: 'app_cart_validate', methods: ['POST'])]
    public function validate(CartService $cartService, OrderManager $orderManager): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $cartData = $cartService->getFullCart();

        if (!$user instanceof User || empty($cartData['items'])) {
            return $this->redirectToRoute('app_cart');
        }

        $orderManager->createOrderFromCart($user, $cartData['items']);

        $cartService->empty();
        $this->addFlash('success', 'Votre commande a été validée avec succès !');

        return $this->redirectToRoute('app_home');
    }
}
