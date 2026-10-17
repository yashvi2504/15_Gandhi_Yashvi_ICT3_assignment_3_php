<?php
$dir=__DIR__.'/sample';
if(!is_dir($dir))mkdir($dir,0775,true);
$entries=array_values(array_filter(scandir($dir),fn($x)=>$x!=='.'&&$x!=='..'));
?><!doctype html><html><body><h1>Files in <?= htmlspecialchars($dir) ?></h1>
<?php if(!$entries): ?><p>No files yet. Add some files to the sample directory.</p><?php else: ?><ul><?php foreach($entries as $entry): ?><li><?= htmlspecialchars($entry) ?> <?= is_dir($dir.'/'.$entry)?'(directory)':'(file)' ?></li><?php endforeach; ?></ul><?php endif; ?></body></html>
