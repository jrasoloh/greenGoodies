<?php

namespace App\Controller;

use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/panier', name: 'app_cart')]
    public function index(CartService $cartService): Response
    {
        // On récupère le tableau formaté par notre service
        $cart = $cartService->getFullCart();

        return $this->render('cart/show.html.twig', [
            'items' => $cart['items'],
            'total' => $cart['total']
        ]);
    }

    #[Route('/panier/add/{id}', name: 'app_cart_add', methods: ['POST'])]
    public function add(int $id, Request $request, CartService $cartService): Response
    {
        $quantity = $request->request->getInt('quantity', 1);

        $cartService->add($id, $quantity);

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/panier/empty', name: 'app_cart_empty', methods: ['POST'])]
    public function empty(CartService $cartService): Response
    {
        $cartService->empty();

        // On redirige vers le panier (qui sera du coup vide)
        return $this->redirectToRoute('app_cart');
    }
}
