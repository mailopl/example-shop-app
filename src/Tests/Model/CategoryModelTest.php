<?php

namespace Tests\Repository;

use App\Entity\Product;
use App\Model\CategoryModel;
use App\Repository\CartRepository;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;

class CategoryModelTest extends \PHPUnit\Framework\TestCase
{
    /** @var CategoryModel */
    private $sut;

    /** @var CartRepository */
    private $entityManagerMock;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->entityManagerMock = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->sut = new CategoryModel(
            $this->entityManagerMock
        );
    }

    public function testGetsCategoryById()
    {
        $categoryId = 1;
        $resultingArray = [];

        $queryMock = $this->getMockBuilder(AbstractQuery::class)
            ->disableOriginalConstructor()
            ->getMock();

        $queryMock
            ->method('getOneOrNullResult')
            ->willReturn($resultingArray);

        $queryMock
            ->method('setParameter')
            ->with('categoryId', $categoryId)
            ->willReturn($queryMock);

        $this->entityManagerMock
            ->expects($this->once())
            ->method('createQuery')
            ->willReturn($queryMock);

        $this->sut->getById($categoryId);
    }

    public function testGetsAllCategories()
    {
        $resultingArray = [];

        $queryMock = $this->getMockBuilder(AbstractQuery::class)
            ->disableOriginalConstructor()
            ->getMock();

        $queryMock
            ->method('getResult')
            ->willReturn($resultingArray);

        $this->entityManagerMock
            ->expects($this->once())
            ->method('createQuery')
            ->willReturn($queryMock);

        $this->assertSame($resultingArray, $this->sut->getAllCategories());
    }


}
