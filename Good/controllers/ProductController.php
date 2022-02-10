<?php

namespace app\controllers;

// Contains all the logic regarding Product. Controller cconnects the product data inside the view


use app\models\Product;
use app\Router;

class ProductController
{

  public static function index(Router $router)
  {
    // echo 'Index page';
    $search = $_GET['search'] ?? '';
    $products = $router->db->getProducts($search);
    $router->renderView('products/index', [
      'products' => $products,
      'search' => $search
    ]);
  }

  public static function create(Router $router)
  {
    $errors = [];
    $productData = [
      'title' => '',
      'description' => '',
      'image' => '',
      'price' => '',
    ];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $productData['title'] = $_POST['title'];
      $productData['description'] = $_POST['description'];
      $productData['price'] = (float)$_POST['price'];
      $productData['imageFile'] = $_FILES['image'] ?? null;

      $product = new Product();
      $product->load($productData);
      $errors[] = $product->save();
      if (empty($errors[0])) {
        header('Location: /products');
        exit;
      }
    }

    $router->renderView('products/create', [
      'product' => $productData,
      'errors' => $errors
    ]);
  }

  public static function update(Router $router)
  {
    // echo 'Update page';
    $id = $_GET['id'] ?? null;
    if (!$id) {
      header('Location: /products');
      exit;
    }
    $errors = [];
    $productData = $router->db->getProductById($id);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $productData['title'] = $_POST['title'];
      $productData['description'] = $_POST['description'];
      $productData['price'] = (float)$_POST['price'];
      $productData['imageFile'] = $_FILES['image'] ?? null;

      $product = new Product();
      $product->load($productData);
      $errors[] = $product->save();
      if (empty($errors[0])) {
        header('Location: /products');
        exit;
      }
    }

    $router->renderView('products/update', [
      'product' => $productData,
      'errors' => $errors
    ]);

    // if ($productData) {
    //   $router->db->updateProduct($id);
    //   header('Location: /products');
    // }
  }

  public static function delete(Router $router)
  {
    // echo 'Delete page';
    $id = $_POST['id'] ?? null;
    if (!$id) {
      header('Location: /products');
      exit;
    }
    $router->db->deleteProduct($id);
    header('Location: /products');
  }
}
