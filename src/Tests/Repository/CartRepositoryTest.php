<?php

namespace Tests\Repository;

use App\Entity\Product;
use App\Repository\CartRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartRepositoryTest extends \PHPUnit\Framework\TestCase
{
    const PRODUCT_ID = '666333';

    /** @var CartRepository */
    private $sut;

    /** @var SessionInterface */
    private $sessionMock;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->sessionMock = $this->getMockBuilder(SessionInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->sut = new CartRepository($this->sessionMock);
    }

    public function testGetsExistingProduct()
    {
        $productMock = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->getMock();

        $productMock
            ->method('getId')
            ->willReturn(self::PRODUCT_ID);

        $this->sessionMock
            ->expects($this->once())
            ->method('get')
            ->willReturn($productMock);

        $result = $this->sut->get($productMock);

        $this->assertSame($productMock, $result);
    }

    public function testGetsAllProducts()
    {
        $productMock = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->getMock();

        $productMock
            ->method('getId')
            ->willReturn(self::PRODUCT_ID);

        $cartProducts = [
            ['product' => $productMock, 'count' => 1]
        ];

        $this->sessionMock
            ->expects($this->once())
            ->method('all')
            ->willReturn($cartProducts);

        $result = $this->sut->all();

        $this->assertSame($cartProducts, $result);
    }

    public function testStoresProductInCartCorrectly()
    {
        $productMock = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->getMock();

        $productMock
            ->method('getId')
            ->willReturn(self::PRODUCT_ID);

        $this->sessionMock
            ->expects($this->once())
            ->method('set')
            ->with(self::PRODUCT_ID, ['product' => $productMock, 'count' => 1])
            ->willReturn($productMock);

        $this->sut->set($productMock, 1);
    }

    public function testRemovesProductCorrectly()
    {
        $productMock = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->getMock();

        $productMock
            ->method('getId')
            ->willReturn(self::PRODUCT_ID);

        $this->sessionMock
            ->expects($this->once())
            ->method('remove')
            ->with(self::PRODUCT_ID);

        $this->sut->remove($productMock);
    }

    public function testGetsProductTotalPriceCorrectly()
    {
        $price = 500;

        $productMock = $this->getMockBuilder(Product::class)
            ->disableOriginalConstructor()
            ->getMock();

        $productMock
            ->method('getId')
            ->willReturn(self::PRODUCT_ID);

        $productMock
            ->method('getPrice')
            ->willReturn($price);

        $productInCart = [
            'count' => 2,
            'product' => $productMock,
        ];

        $this->sessionMock
            ->expects($this->once())
            ->method('all')
            ->willReturn([$productInCart]);

        $this->assertSame($price * $productInCart['count'], $this->sut->getTotal());
    }
}
