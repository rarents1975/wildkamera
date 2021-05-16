<?php
$methode = $_SERVER['REQUEST_METHOD'];
 
switch ($methode) {
  case 'GET':
   $location = file_get_contents('./streamlocation.txt'); 
    echo ($location);
    break;
}
?>
