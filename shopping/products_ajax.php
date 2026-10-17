<?php
require __DIR__.'/config.php';
header('Content-Type: application/json; charset=utf-8');
$cid = filter_input(INPUT_GET, 'category_id', FILTER_VALIDATE_INT) ?: 0;
$page = max(1, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1);
$perPage = 6;
$count = $pdo->prepare('SELECT COUNT(*) FROM products WHERE category_id=?');
$count->execute([$cid]); $total = (int)$count->fetchColumn();
$pages = max(1, (int)ceil($total/$perPage)); $offset = ($page-1)*$perPage;
$stmt = $pdo->prepare('SELECT id,pname,price,picture FROM products WHERE category_id=? ORDER BY id DESC LIMIT ? OFFSET ?');
$stmt->bindValue(1,$cid,PDO::PARAM_INT); $stmt->bindValue(2,$perPage,PDO::PARAM_INT); $stmt->bindValue(3,$offset,PDO::PARAM_INT); $stmt->execute();
$html = '';
foreach($stmt as $p) {
  $html .= '<div class="product">';
  if ($p['picture']) $html .= '<img src="uploads/'.h($p['picture']).'" alt="">';
  $html .= '<h3>'.h($p['pname']).'</h3><p>₹'.number_format((float)$p['price'],2).'</p>';
  $html .= '<form method="post" action="cart.php"><input type="hidden" name="product_id" value="'.(int)$p['id'].'"><button name="add" value="1">Add to cart</button></form></div>';
}
echo json_encode(['html'=>$html,'pages'=>$pages,'total'=>$total]);
