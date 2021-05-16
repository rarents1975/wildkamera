<?php
$methode = $_SERVER['REQUEST_METHOD'];
 
switch ($methode) {
  case 'GET':
   $zeiten = file_get_contents('./zeiten.txt'); 
    echo ($zeiten);
    break;
}
?>
