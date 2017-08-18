<?php

namespace App\Controller;

use App\Model\CartModel;
use App\Model\ProductModel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CartController extends Controller
{
    /**
     * @Route("/cart/add/{id}", name="cart_add")
     * @throws NotFoundHttpException
     */
    public function addAction(string $id)
    {
        $cartModel = $this->get(CartModel::class);
        $productsModel = $this->get(ProductModel::class);

        $product = $productsModel->getById($id);

        if (empty($product)) {
            throw new NotFoundHttpException('Product not found.');
        }

        $cartModel->addProduct($product);

        return $this->redirectToRoute('cart_view');
    }

    /**
     * @Route("/cart/remove/{id}", name="cart_remove", methods={"POST"})
     */
    public function removeAction(string $id)
    {
        $cartModel = $this->get(CartModel::class);

        $productsModel = $this->get(ProductModel::class);
        $product = $productsModel->getById($id);

        $cartModel->removeProduct($product);

        return $this->redirectToRoute('cart_view');
    }

    /**
     * @Route("/cart", name="cart_view")
     */
    public function viewAction()
    {
        $cartModel = $this->get(CartModel::class);

        return $this->render('Cart/view.html.twig', [
            'cart' => $cartModel
        ]);
    }
}
