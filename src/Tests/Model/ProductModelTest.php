<?php

namespace Tests\Repository;

use App\Entity\Product;
use App\Model\ProductModel;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;

class ProductModelTest extends \PHPUnit\Framework\TestCase
{
    const PRODUCT_ID = '666333';
    const SECOND_PRODUCT_ID = '111000';

    /** @var ProductModel */
    private $sut;

    /** @var EntityManagerInterface */
    private $entityManagerMock;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->entityManagerMock = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->sut = new ProductModel(
            $this->entityManagerMock
        );
    }

    public function testGetsAllProducts()
    {
        $resultingArray = [];

        $queryMock = $this->getMockBuilder(AbstractQuery::class)
            ->disableOriginalConstructor()
            ->getMock();

        $queryMock->method('getResult')->willReturn($resultingArray);

        $this->entityManagerMock
            ->expects($this->once())
            ->method('createQuery')
            ->willReturn($queryMock);

        $this->assertSame($resultingArray, $this->sut->getAllProducts());
    }

    public function testGetProductsInCategorySuccess()
    {
        $categoryFound = ['whatever-category-data-not-empty'];
        $categoryId = 1;

        $queryMock = $this->getMockBuilder(AbstractQuery::class)
            ->disableOriginalConstructor()
            ->getMock();

        $queryMock
            ->method('setParameter')
            ->with('categoryId', $categoryId)
            ->willReturn($queryMock);

        $queryMock
            ->method('getResult')
            ->willReturn($categoryFound);

        $this->entityManagerMock
            ->expects($this->once())
            ->method('createQuery')
            ->willReturn($queryMock);

        $this->sut->getProductsByCategoryId($categoryId);
    }

    public function testGetByIdWorksFine()
    {
        $object = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->getMock();

        $objectRepositoryMock = $this->getMockBuilder(ObjectRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $objectRepositoryMock
            ->expects($this->once())
            ->method('find')
            ->willReturn($object);

        $this->entityManagerMock
            ->expects($this->once())
            ->method('getRepository')
            ->willReturn($objectRepositoryMock);

        $this->assertSame($object, $this->sut->getById(1));
    }
}
