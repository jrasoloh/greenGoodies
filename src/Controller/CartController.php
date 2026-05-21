<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderLine;
use App\Entity\User;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'app_cart')]
    public function index(CartService $cartService): Response
    {
        // On récupère le tableau formaté par notre service
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

        // On redirige vers le panier (qui sera du coup vide)
        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/validate', name: 'app_cart_validate', methods: ['POST'])]
    public function validate(CartService $cartService, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $cartData = $cartService->getFullCart();

        if (!$user instanceof User || empty($cartData['items'])) {
            return $this->redirectToRoute('app_cart');
        }

        // 1. Création de la commande principale
        $order = new Order();
        $order->setCustomer($user);
        $order->setReference('CMD-' . strtoupper(uniqid()));
        $order->setStatus('VALIDATED');
        $order->setCreatedAt(new \DateTimeImmutable());

        $em->persist($order);

        foreach ($cartData['items'] as $item) {
            $product = $item['product'];

            $orderLine = new OrderLine();
            $orderLine->setCustomerOrder($order);
            $orderLine->setProduct($product);
            $orderLine->setProductName($product->getName());
            $orderLine->setProductPrice($product->getPrice());
            $orderLine->setQuantity($item['quantity']);

            $em->persist($orderLine);
        }

        $em->flush();

        $cartService->empty();

        $this->addFlash('success', 'Votre commande a été validée avec succès !');

        return $this->redirectToRoute('app_home');
    }
}
