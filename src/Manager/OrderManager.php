<?php

namespace App\Manager;

use App\Entity\Order;
use App\Entity\OrderLine;
use App\Entity\User;
use App\Model\OrderModel;
use App\Repository\OrderLineRepository;
use App\Repository\OrderRepository;

class OrderManager
{
    public function __construct(
        private readonly OrderRepository $orderRepository,
        private readonly OrderLineRepository $orderLineRepository
    )
    {
    }

    public function createOrderFromModel(User $user, OrderModel $orderModel): void
    {
        $order = new Order();
        $order->setCustomer($user);
        $order->setReference('CMD-' . strtoupper(uniqid()));
        $order->setStatus('VALIDATED');
        $order->setCreatedAt(new \DateTimeImmutable());

        $this->orderRepository->persist($order);

        foreach ($orderModel->getItems() as $item) {
            $product = $item['product'];

            $orderLine = new OrderLine();
            $orderLine->setCustomerOrder($order);
            $orderLine->setProduct($product);
            $orderLine->setProductName($product->getName());
            $orderLine->setProductPrice($product->getPrice());
            $orderLine->setQuantity($item['quantity']);

            $this->orderLineRepository->persist($orderLine);
        }

        $this->orderLineRepository->save();
        $this->orderRepository->save();
    }
}
