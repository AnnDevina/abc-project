<?php
$db = mysqli_connect('localhost:80','root','','abc');
if(mysqli_connect_errno()){
  echo 'Database connection failed with following errors: '. mysqli_connect_error();
  die();
}
session_start();
/*equire_once $_SERVER['DOCUMENT_ROOT'].'/Project/princespark/config.php';
require_once BASEURL.'helpers/helpers.php';
require BASEURL.'vendor/autoload.php';

$cart_id = '';
if(isset($_COOKIE[CART_COOKIE])){
  $cart_id = sanitize($_COOKIE[CART_COOKIE]);
}*/
