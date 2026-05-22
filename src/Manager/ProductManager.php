<?php

namespace App\Manager;

use App\Repository\ProductRepository;

class ProductManager
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Récupère et formate la liste des produits pour l'API
     */
    public function getProductsForApi(): array
    {
        $products = $this->productRepository->findAll();
        $formattedData = [];

        foreach ($products as $product) {
            $formattedData[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice() / 100,
                'description' => $product->getLongDescription(),
            ];
        }

        return $formattedData;
    }
}
