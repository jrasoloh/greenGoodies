<?php

namespace App\Manager;

use App\Entity\Order;
use App\Entity\OrderLine;
use App\Entity\User;
use App\Model\OrderModel;
use Doctrine\ORM\EntityManagerInterface;

class OrderManager
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createOrderFromModel(User $user, OrderModel $orderModel): void
    {
        $order = new Order();
        $order->setCustomer($user);
        $order->setReference('CMD-' . strtoupper(uniqid()));
        $order->setStatus('VALIDATED');
        $order->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($order);

        foreach ($orderModel->getItems() as $item) {
            $product = $item['product'];

            $orderLine = new OrderLine();
            $orderLine->setCustomerOrder($order);
            $orderLine->setProduct($product);
            $orderLine->setProductName($product->getName());
            $orderLine->setProductPrice($product->getPrice());
            $orderLine->setQuantity($item['quantity']);

            $this->entityManager->persist($orderLine);
        }

        $this->entityManager->flush();
    }
}
