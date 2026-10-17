<?php
require __DIR__.'/../config.php'; require_admin();
$uploadDir=__DIR__.'/../uploads/';
if (!is_dir($uploadDir)) mkdir($uploadDir,0775,true);
function resizeImage($tmp,$dest,$mime,$maxW=600,$maxH=600) {
  if ($mime==='image/jpeg') $src=@imagecreatefromjpeg($tmp);
  elseif ($mime==='image/png') $src=@imagecreatefrompng($tmp);
  elseif ($mime==='image/gif') $src=@imagecreatefromgif($tmp);
  elseif ($mime==='image/webp' && function_exists('imagecreatefromwebp')) $src=@imagecreatefromwebp($tmp);
  else return false;
  if(!$src)return false;
  $w=imagesx($src);$h=imagesy($src);$scale=min($maxW/$w,$maxH/$h,1);
  $nw=max(1,(int)($w*$scale));$nh=max(1,(int)($h*$scale));
  $out=imagecreatetruecolor($nw,$nh);
  if($mime==='image/png'||$mime==='image/gif'||$mime==='image/webp'){imagealphablending($out,false);imagesavealpha($out,true);$trans=imagecolorallocatealpha($out,0,0,0,127);imagefilledrectangle($out,0,0,$nw,$nh,$trans);}
  imagecopyresampled($out,$src,0,0,0,0,$nw,$nh,$w,$h);
  $ok=match($mime){'image/jpeg'=>imagejpeg($out,$dest,85),'image/png'=>imagepng($out,$dest,6),'image/gif'=>imagegif($out,$dest),'image/webp'=>imagewebp($out,$dest,85),default=>false};
  imagedestroy($src);imagedestroy($out);return $ok;
}
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $id=(int)($_POST['id']??0);$cid=(int)($_POST['category_id']??0);$name=trim($_POST['pname']??'');$price=(float)($_POST['price']??0);
  $picture=$_POST['old_picture']??null;
  if(isset($_POST['delete'])){
    $s=$pdo->prepare('DELETE FROM products WHERE id=?');$s->execute([$id]);
    if($picture && is_file($uploadDir.$picture)) @unlink($uploadDir.$picture);
    header('Location: products.php');exit;
  }
  if($name===''||$cid<1||$price<0){$error='Enter valid product details.';}
  else {
    if(!empty($_FILES['picture']['name'])){
      $tmp=$_FILES['picture']['tmp_name'];$info=@getimagesize($tmp);
      $allowed=['image/jpeg'=>'jpg','image/png'=>'png','image/gif'=>'gif','image/webp'=>'webp'];
      if(!$info || !isset($allowed[$info['mime']])) $error='Upload a valid JPG, PNG, GIF, or WEBP image.';
      else {
        $new=bin2hex(random_bytes(12)).'.'.$allowed[$info['mime']];
        if(!resizeImage($tmp,$uploadDir.$new,$info['mime'])) $error='Image resize failed. Enable PHP GD extension.';
        else { if($picture && is_file($uploadDir.$picture)) @unlink($uploadDir.$picture); $picture=$new; }
      }
    }
    if(!$error){
      if($id){$s=$pdo->prepare('UPDATE products SET category_id=?,pname=?,price=?,picture=? WHERE id=?');$s->execute([$cid,$name,$price,$picture,$id]);}
      else{$s=$pdo->prepare('INSERT INTO products(category_id,pname,price,picture) VALUES(?,?,?,?)');$s->execute([$cid,$name,$price,$picture]);}
      header('Location: products.php');exit;
    }
  }
}
$edit=null;if(isset($_GET['edit'])){$s=$pdo->prepare('SELECT * FROM products WHERE id=?');$s->execute([(int)$_GET['edit']]);$edit=$s->fetch();}
$cats=$pdo->query('SELECT * FROM categories ORDER BY name')->fetchAll();
$rows=$pdo->query('SELECT p.*,c.name category FROM products p JOIN categories c ON c.id=p.category_id ORDER BY p.id DESC')->fetchAll();
?><!doctype html><html><body><nav><a href="categories.php">Categories</a> | <a href="products.php">Products</a> | <a href="logout.php">Logout</a></nav><h1>Manage Products</h1><p style="color:red"><?= h($error) ?></p>
<form method="post" enctype="multipart/form-data">
<input type="hidden" name="id" value="<?= (int)($edit['id']??0) ?>"><input type="hidden" name="old_picture" value="<?= h($edit['picture']??'') ?>">
<label>Category <select name="category_id" required><?php foreach($cats as $c): ?><option value="<?= (int)$c['id'] ?>" <?= (($edit['category_id']??0)==$c['id'])?'selected':'' ?>><?= h($c['name']) ?></option><?php endforeach; ?></select></label>
<label>Product name <input name="pname" required value="<?= h($edit['pname']??'') ?>"></label>
<label>Price <input name="price" type="number" min="0" step="0.01" required value="<?= h($edit['price']??'') ?>"></label>
<label>Picture <input type="file" name="picture" accept="image/jpeg,image/png,image/gif,image/webp"></label>
<button><?= $edit?'Update':'Add Product' ?></button></form>
<table border="1" cellpadding="6"><tr><th>Image</th><th>Name</th><th>Category</th><th>Price</th><th>Actions</th></tr>
<?php foreach($rows as $r): ?><tr><td><?php if($r['picture']): ?><img width="70" src="../uploads/<?= h($r['picture']) ?>"><?php endif; ?></td><td><?= h($r['pname']) ?></td><td><?= h($r['category']) ?></td><td><?= h($r['price']) ?></td><td><a href="?edit=<?= (int)$r['id'] ?>">Edit</a>
<form method="post" style="display:inline" onsubmit="return confirm('Delete product?')"><input type="hidden" name="id" value="<?= (int)$r['id'] ?>"><input type="hidden" name="old_picture" value="<?= h($r['picture']) ?>"><button name="delete">Delete</button></form></td></tr><?php endforeach; ?></table></body></html>
