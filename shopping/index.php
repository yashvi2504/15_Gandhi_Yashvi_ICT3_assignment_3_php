<?php
require __DIR__.'/config.php';
$categories = $pdo->query('SELECT id,name FROM categories ORDER BY name')->fetchAll();
?>
<!doctype html><html><head><meta charset="utf-8"><title>Shopping Store</title>
<style>body{font-family:Arial;max-width:1000px;margin:25px auto;padding:0 15px}nav a{margin-right:14px}.cats a{display:inline-block;padding:10px;margin:5px;background:#eee;border-radius:5px}#products{display:grid;grid-template-columns:repeat(3,1fr);gap:15px}.product{border:1px solid #ddd;padding:12px}.product img{width:100%;height:160px;object-fit:contain}.pager button{margin:4px}</style></head><body>
<nav><a href="index.php">Home</a>
<a href="logout.php">Logout</a>
<a href="register.php">Register</a><a href="login.php">Login</a><a href="cart.php">Cart (<?= count($_SESSION['cart'] ?? []) ?>)</a></nav>
<h1>Shopping Cart</h1><h2>Categories</h2><div class="cats">
<?php foreach($categories as $c): ?><a href="#" class="category" data-id="<?= (int)$c['id'] ?>"><?= h($c['name']) ?></a><?php endforeach; ?>
</div><h2 id="heading">Products</h2>
<div id="products">Click a category to load products.</div><div id="pager" class="pager"></div>
<script>
let categoryId = 0;
async function loadProducts(page=1) {
  if (!categoryId) return;
  const res = await fetch('products_ajax.php?category_id='+categoryId+'&page='+page);
  const data = await res.json();
  document.getElementById('products').innerHTML = data.html || '<p>No products found.</p>';
  document.getElementById('pager').innerHTML = data.pages > 1
    ? Array.from({length:data.pages},(_,i)=>`<button data-page="${i+1}">${i+1}</button>`).join('') : '';
  document.querySelectorAll('#pager button').forEach(b=>b.onclick=()=>loadProducts(Number(b.dataset.page)));
}
document.querySelectorAll('.category').forEach(a=>a.addEventListener('click',e=>{
 e.preventDefault(); categoryId=a.dataset.id; document.getElementById('heading').textContent=a.textContent; loadProducts(1);
}));
</script></body></html>
