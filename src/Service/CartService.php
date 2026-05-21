<?php

namespace App\Service;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    private RequestStack $requestStack;
    private ProductRepository $productRepository;

    public function __construct(RequestStack $requestStack, ProductRepository $productRepository)
    {
        $this->requestStack = $requestStack;
        $this->productRepository = $productRepository;
    }

    public function add(int $id, int $quantity = 1): void
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', []);

        if ($quantity <= 0) {
            unset($cart[$id]);
        } else {
            $cart[$id] = $quantity;
        }

        $session->set('cart', $cart);
    }

    public function empty(): void
    {
        $this->requestStack->getSession()->remove('cart');
    }

    public function getFullCart(): array
    {
        $cart = $this->requestStack->getSession()->get('cart', []);
        $cartData = [];
        $totalCents = 0;

        foreach ($cart as $id => $quantity) {
            $product = $this->productRepository->find($id);

            // Sécurité : si le produit a été supprimé de la BDD entre temps
            if (!$product) {
                continue;
            }

            $cartData[] = [
                'product' => $product,
                'quantity' => $quantity
            ];

            // Calcul du total en centimes
            $totalCents += $product->getPrice() * $quantity;
        }

        return [
            'items' => $cartData,
            'total' => $totalCents / 100
        ];
    }
}
