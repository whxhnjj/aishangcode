<?php
//header("content-type:image/jpeg");
require_once("../includes/interface.php");

$w = _GET('w','i',200);
$h = _GET('h','i',200);
$photo = _GET('photo');
$photo = base64_decode($photo);

$tb=new thumb();
$tb->SetVar($photo,"link");
$tb->Cut('',$w,$h);
?>
