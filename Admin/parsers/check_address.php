<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'/Project/princespark/core/init.php';
  $name = sanitize($_POST['full_name']);
  $email = sanitize($_POST['email']);
  $street = sanitize($_POST['street']);
  $street2 = sanitize($_POST['street2']);
  $city = sanitize($_POST['city']);
  $errors = array();
  $required = array(
    'full_name'  => 'Full Name',
    'email'      => 'Email',
    'street'     => 'Street',
    'city'       => 'City',
  );

  //check if all require fields are filled out
  foreach ($required as $f => $d) {
    if(empty($_POST[$f]) || $_POST[$f] == ''){
      $errors[] = $d.' is required.';
    }
  }

  //check if valid email address
  if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
    $errors[] = 'Please enter a valid email address';
  }


  if (!empty($errors)) {
    echo display_errors($errors);
  }else{
    echo 'passed';
  }
 ?>
