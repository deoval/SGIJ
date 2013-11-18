<?php
include('config.php');
$url_font = FONT_FOLDER . "/" . FONT_FILE;
$size_font = 20;
$anglulo = 0;
$x = 15;
$y = 30;
$count_chars = strlen(EMPRESA_NOME);
$image = imagecreate(($count_chars*14)+$x+10 ,50);
imagesavealpha($image ,true); 
$white_bg = imagecolorallocate($image ,255 ,255 ,255);
ImageColorTransparent($image ,$white_bg);
$black = imagecolorallocate($image ,0 ,0 ,0);
$branco = imagecolorallocate($image ,255 ,255 ,255);
imagettftext($image ,$size_font ,$anglulo ,$x ,$y ,$branco ,$url_font ,EMPRESA_NOME);
header("Content-type: image/png");
imagepng($image);
imagedestroy($image);
exit();
?> 

