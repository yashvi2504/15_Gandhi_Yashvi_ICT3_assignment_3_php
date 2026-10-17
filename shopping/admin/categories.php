<?php
require __DIR__.'/../config.php'; require_admin();
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $name=trim($_POST['name'] ?? '');
  if (isset($_POST['create']) && $name!=='') { $s=$pdo->prepare('INSERT INTO categories(name) VALUES(?)'); $s->execute([$name]); }
  if (isset($_POST['update']) && $name!=='') { $s=$pdo->prepare('UPDATE categories SET name=? WHERE id=?'); $s->execute([$name,(int)$_POST['id']]); }
  if (isset($_POST['delete'])) { $s=$pdo->prepare('DELETE FROM categories WHERE id=?'); $s->execute([(int)$_POST['id']]); }
  header('Location: categories.php'); exit;
}
$edit=null;
if(isset($_GET['edit'])){$s=$pdo->prepare('SELECT * FROM categories WHERE id=?');$s->execute([(int)$_GET['edit']]);$edit=$s->fetch();}
$rows=$pdo->query('SELECT * FROM categories ORDER BY id DESC')->fetchAll();
?><!doctype html><html><body><nav><a href="categories.php">Categories</a> | <a href="products.php">Products</a> | <a href="logout.php">Logout</a></nav><h1>Manage Categories</h1>
<form method="post"><?php if($edit): ?><input type="hidden" name="id" value="<?= (int)$edit['id'] ?>"><?php endif; ?>
<input name="name" placeholder="Category name" required value="<?= h($edit['name'] ?? '') ?>">
<button name="<?= $edit?'update':'create' ?>"><?= $edit?'Update':'Add' ?></button><?php if($edit): ?><a href="categories.php">Cancel</a><?php endif; ?></form>
<table border="1" cellpadding="8"><tr><th>ID</th><th>Name</th><th>Actions</th></tr>
<?php foreach($rows as $r): ?><tr><td><?= (int)$r['id'] ?></td><td><?= h($r['name']) ?></td><td><a href="?edit=<?= (int)$r['id'] ?>">Edit</a>
<form method="post" style="display:inline" onsubmit="return confirm('Delete category and its products?')"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>"><button name="delete">Delete</button></form></td></tr><?php endforeach; ?></table></body></html>
