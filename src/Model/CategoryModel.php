<?php

namespace App\Model;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryModel
{
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getById(string $categoryId)
    {
        return $this->entityManager
            ->createQuery('SELECT c FROM App\Entity\Category c WHERE c.id = :categoryId')
            ->setParameter('categoryId', $categoryId)
            ->getOneOrNullResult();
    }

    public function getAllCategories()
    {
        return $this->entityManager
            ->createQuery('SELECT c FROM App\Entity\Category c')
            ->getResult();
    }
}
