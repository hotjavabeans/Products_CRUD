<?php
require_once '../../database.php';

$search = $_GET['search'] ?? '';
if ($search) {
  $statement = $pdo->prepare("SELECT * FROM products WHERE title LIKE :title ORDER BY create_date DESC");
  $statement->bindValue(':title', "%$search%");
} else {
  $statement = $pdo->prepare("SELECT * FROM products ORDER BY create_date DESC");
}

$statement->execute();
$products = $statement->fetchAll(PDO::FETCH_ASSOC);

?>

<?php include_once '../../views/partials/header.php'; ?>

<body>
  <h1>Products CRUD</h1>

  <p>
    <a href="create.php" class="btn btn-success">Create Product</a>
  </p>

  <form action="">
    <div class="input-group mb-3">
      <input type="text" class="form-control" placeholder="Search products..." name="search" value="<?php echo $search ?>">
      <button class="btn btn-outline-secondary" type="submit">Search</button>
    </div>
  </form>

  <table class="table">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Image</th>
        <th scope="col">Title</th>
        <th scope="col">Price</th>
        <th scope="col">Create Date</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($products as $i => $product) : ?>
        <tr>
          <th scope="row"><?php echo $i + 1 ?></th>
          <td>
            <img class="thumb-image" src="<?php echo $product['image'] ?>" alt="">
          </td>
          <td><?php echo $product['title'] ?></td>
          <td><?php echo $product['price'] ?></td>
          <td><?php echo $product['create_date'] ?></td>
          <td>
            <div class="index-form-btn">
              <a href="update.php?id=<?php echo $product['id'] ?>"><button class="btn-sm btn-primary">Edit</button></a>
              <form action="delete.php" method="post">
                <input type="hidden" name="id" value="<?php echo $product['id'] ?>">
                <button type="submit" class="btn-sm btn-danger">Delete</button>
              </form>
            </div>
          </td>
        </tr>
      <?php endforeach ?>
    </tbody>
  </table>
</body>

</html>