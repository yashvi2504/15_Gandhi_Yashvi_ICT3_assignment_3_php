<?php
require __DIR__.'/config.php';
if(isset($_POST['add'])){
 $id=(int)($_POST['product_id']??0);$s=$pdo->prepare('SELECT id FROM products WHERE id=?');$s->execute([$id]);
 if($s->fetch())$_SESSION['cart'][$id]=($_SESSION['cart'][$id]??0)+1;
 header('Location: cart.php');exit;
}
if(isset($_POST['remove'])){unset($_SESSION['cart'][(int)$_POST['remove']]);header('Location: cart.php');exit;}
$cart=$_SESSION['cart']??[];$items=[];$total=0;
if($cart){$ids=array_map('intval',array_keys($cart));$marks=implode(',',array_fill(0,count($ids),'?'));$s=$pdo->prepare("SELECT * FROM products WHERE id IN ($marks)");$s->execute($ids);
 foreach($s as $p){$p['qty']=$cart[$p['id']];$p['subtotal']=$p['price']*$p['qty'];$total+=$p['subtotal'];$items[]=$p;}}
?><!doctype html><html><body>
    <a href="logout.php">Logout</a>|||<a href="index.php">Continue shopping</a><h1>Your Cart</h1>
<?php if(!$items): ?><p>Cart is empty.</p><?php else: ?><table border="1" cellpadding="8"><tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th><th></th></tr>
<?php foreach($items as $p): ?><tr><td><?= h($p['pname']) ?></td><td><?= h($p['price']) ?></td><td><?= (int)$p['qty'] ?></td><td><?= number_format($p['subtotal'],2) ?></td><td><form method="post"><button name="remove" value="<?= (int)$p['id'] ?>">Remove</button></form></td></tr><?php endforeach; ?></table><h3>Total: ₹<?= number_format($total,2) ?></h3><?php endif; ?></body></html>
