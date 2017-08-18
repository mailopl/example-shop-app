<?php

namespace App\Controller;

use App\Model\CategoryModel;
use App\Model\ProductModel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        $productsModel = $this->get(ProductModel::class);
        $products = $productsModel->getAllProducts();

        $categoryModel = $this->get(CategoryModel::class);
        $categories = $categoryModel->getAllCategories();

        return $this->render('Product/index.html.twig', [
            'products' => $products,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/category/{id}")
     * @throws NotFoundHttpException
     */
    public function categoryAction(string $id)
    {
        $productsModel = $this->get(ProductModel::class);
        $products = $productsModel->getProductsByCategoryId($id);

        $categoryModel = $this->get(CategoryModel::class);
        $category = $categoryModel->getById($id);

        if (empty($category)) {
            throw new NotFoundHttpException('Category not found #' . $id);
        }

        return $this->render('Product/category.html.twig', [
            'products' => $products,
            'category' => $category
        ]);
    }
}
