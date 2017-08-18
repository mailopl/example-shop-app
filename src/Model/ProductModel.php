<?php

namespace App\Model;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class ProductModel
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getAllProducts() : array
    {
        return $this->entityManager
            ->createQuery('SELECT p, c FROM App\Entity\Product p JOIN p.category c')
            ->getResult();
    }

    public function getProductsByCategoryId(string $categoryId)
    {
        return $this->entityManager
            ->createQuery(
            'SELECT p, c FROM App\Entity\Product p ' .
                 'JOIN p.category c ' .
                 'WHERE p.category = :categoryId'
            )
            ->setParameter('categoryId', $categoryId)
            ->getResult();
    }

    public function getById(string $id)
    {
        return $this->entityManager
            ->getRepository(Product::class)
            ->find($id);
    }
}
