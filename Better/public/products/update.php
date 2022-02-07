<?php
require_once '../../database.php';
require_once '../../functions.php';

$id = $_GET['id'] ?? null;

if (!$id) {
  header('Location: index.php');
  exit;
}

$statement = $pdo->prepare("SELECT * FROM products WHERE id = :id");
$statement->bindValue(':id', $id);
$statement->execute();
$product = $statement->fetch(PDO::FETCH_ASSOC);
// var_dump($product);
// exit;
$errors = [];

$title = $product['title'];
$description = $product['description'];
$price = $product['price'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  require_once '../../validate_product.php';

  if (empty($errors)) {

    $statement = $pdo->prepare("UPDATE products SET title = :title,
        image = :image, 
        description = :description,
        price = :price WHERE id = :id");

    $statement->bindValue(':title', $title);
    $statement->bindValue(':image', $imagePath);
    $statement->bindValue(':description', $description);
    $statement->bindValue(':price', $price);
    $statement->bindValue(':id', $id);
    $statement->execute();
    header('Location: index.php');
  }
}
?>

<?php include_once '../../views/partials/header.php'; ?>

<body>

  <p><a href="index.php"><button class="btn-sm btn-primary">&#8672; Cancel Edit</button></a></p>

  <h1>Update <strong><?php echo $product['title'] ?></strong> </h1>

  <?php include_once '../../views/products/form.php'; ?>

</body>

</html>