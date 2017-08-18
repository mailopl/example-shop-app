<?php

namespace Tests\Repository;

use App\Entity\Product;
use App\Model\CartModel;
use App\Repository\CartRepository;

class CartModelTest extends \PHPUnit\Framework\TestCase
{
    const PRODUCT_ID = '666333';
    const SECOND_PRODUCT_ID = '111000';

    /** @var CartModel */
    private $sut;

    /** @var CartRepository */
    private $cartRepositoryMock;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->cartRepositoryMock = $this->getMockBuilder(CartRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->sut = new CartModel(
            $this->cartRepositoryMock
        );
    }

    public function testAddsAProduct()
    {
        $productMock = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->getMock();

        $productMock->method('getId')->willReturn(self::PRODUCT_ID);

        $productInCart = [
            'count' => 1,
            'product' => $productMock
        ];

        $this->cartRepositoryMock
            ->expects($this->once())
            ->method('get')
            ->willReturn($productInCart);

        $this->cartRepositoryMock
            ->expects($this->once())
            ->method('set')
            ->with($productMock, $productInCart['count']+1);

        $this->sut->addProduct($productMock);
    }

    public function testDecrementsAProductIfMoreThanOne()
    {
        $productMock = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->getMock();

        $productMock->method('getId')->willReturn(self::PRODUCT_ID);

        $productInCart = [
            'count' => 2,
            'product' => $productMock
        ];

        $this->cartRepositoryMock
            ->expects($this->once())
            ->method('get')
            ->willReturn($productInCart);

        $this->cartRepositoryMock
            ->expects($this->once())
            ->method('set')
            ->with($productMock, $productInCart['count']-1);

        $this->sut->removeProduct($productMock);
    }

    public function testRemovesAProductIfOne()
    {
        $productMock = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->getMock();

        $productMock->method('getId')->willReturn(self::PRODUCT_ID);

        $productInCart = [
            'count' => 1,
            'product' => $productMock
        ];

        $this->cartRepositoryMock
            ->expects($this->once())
            ->method('get')
            ->willReturn($productInCart);

        $this->cartRepositoryMock
            ->expects($this->once())
            ->method('remove')
            ->with($productMock);

        $this->sut->removeProduct($productMock);
    }

    public function testReturnsProductsInVatBuckets()
    {
        $productMock = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->getMock();

        $productMock->method('getId')->willReturn(self::PRODUCT_ID);
        $productMock->method('getVatPercent')->willReturn(8);

        $secondProductMock = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->getMock();

        $productMock->method('getId')->willReturn(self::SECOND_PRODUCT_ID);
        $secondProductMock->method('getVatPercent')->willReturn(22);

        $productInCart = [
            'count' => 1,
            'product' => $productMock
        ];

        $secondProductInCart = [
            'count' => 2,
            'product' => $secondProductMock
        ];

        $cartProducts = [
            $productInCart,
            $secondProductInCart
        ];

        $this->cartRepositoryMock
            ->expects($this->once())
            ->method('all')
            ->willReturn($cartProducts);

        $expectedBuckets = [
            8 => [$productInCart],
            22 => [$secondProductInCart]
        ];

        $this->assertSame($expectedBuckets, $this->sut->getProductsInVatBuckets());
    }

    public function testGetsTotal()
    {
        $total = 5555;

        $this->cartRepositoryMock
            ->expects($this->once())
            ->method('getTotal')
            ->willReturn($total);


        $this->assertSame($total, $this->sut->getTotal());
    }

    public function testGetsAllProducts()
    {
        $products = [
            ['a'],
            ['b']
        ];

        $this->cartRepositoryMock
            ->expects($this->once())
            ->method('all')
            ->willReturn($products);


        $this->assertSame($products, $this->sut->getProducts());
    }
}
