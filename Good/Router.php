<?php

namespace app;

use app\controllers\ProductController;

class Router
{
  public array $getRoutes = [];
  public array $postRoutes = [];
  public Database $db;

  public function __construct()
  {
    $this->db = new Database();
  }


  public function get($url, $fn)
  {
    $this->getRoutes[$url] = $fn;
  }

  public function post($url, $fn)
  {
    $this->postRoutes[$url] = $fn;
  }

  public function resolve()
  {
    // to fix VirtualHost products/create bug
    // $currentUrl = $_SERVER['PATH_INFO'] ?? '/';
    $currentUrl = $_SERVER['REQUEST_URI'] ?? '/';
    if (strpos($currentUrl, '?') !== false) {
      $currentUrl = substr($currentUrl, 0, strpos($currentUrl, '?'));
    }
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'GET') {
      $fn = $this->getRoutes[$currentUrl] ?? null;
    } else {
      $fn = $this->postRoutes[$currentUrl] ?? null;
    }
    if ($fn) {
      call_user_func($fn, $this);
    } else {
      echo 'Page Not Found';
      // OR http_response_code();
    }
  }

  //products/index
  public function renderView($view, $params = [])
  {
    foreach ($params as $key => $value) {
      $$key = $value;
    }
    ob_start();
    include_once __DIR__ . "/views/$view.php";
    $content = ob_get_clean();
    include_once __DIR__ . "/views/_layout.php";
  }
}
