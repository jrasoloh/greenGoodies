<?php

namespace App\Controller\Api;

use App\Manager\ProductManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/api/products', name: 'app_api_products', methods: ['GET'])]
    public function index(ProductManager $productManager): JsonResponse
    {
        return $this->json($productManager->getProductsForApi());
    }
}
