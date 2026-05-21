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

        if (!empty($cart[$id])) {
            $cart[$id] += $quantity;
        } else {
            $cart[$id] = $quantity;
        }

        $session->set('cart', $cart);
    }

    // 2. Vider complètement le panier
    public function empty(): void
    {
        $this->requestStack->getSession()->remove('cart');
    }

    // 3. Récupérer le panier complet avec les objets Product et le total
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
