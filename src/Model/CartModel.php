<?php

namespace App\Model;

use App\Entity\Product;
use App\Repository\CartRepository;

class CartModel
{
    /** @var CartRepository */
    private $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function addProduct(Product $product)
    {
        $productInCart = $this->cartRepository->get($product);

        $count = $productInCart['count'] ?? 0;
        ++$count;

        $this->cartRepository->set($product, $count);
    }

    public function removeProduct(Product $product)
    {
        $productInCart = $this->cartRepository->get($product);

        $count = $productInCart['count'] ?? 0;

        if (--$count <= 0) {
            $this->cartRepository->remove($product);
            return;
        }

        $this->cartRepository->set($product, $count);
    }

    public function getProductsInVatBuckets()
    {
        $vatBuckets = [];
        foreach ($this->cartRepository->all() as $cartProduct) {
            /** @var Product $product */
            $product = $cartProduct['product'];

            $vatPercent = $product->getVatPercent();
            $vatBuckets[$vatPercent][] = $cartProduct;
        }

        return $vatBuckets;
    }

    public function getTotal()
    {
        return $this->cartRepository->getTotal();
    }

    public function getProducts()
    {
        return $this->cartRepository->all();
    }
}
