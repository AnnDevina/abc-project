<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/ABC_learning/Admin/init.php';

    //Delete product
    if(isset($_GET['delete'])){
      $id = sanitize($_GET['delete']);
      $db->query("UPDATE foods SET deleted = 1 WHERE id = '$id'");
      header('Location: Foods.php');
    }

    $dbPath = '';
    if(isset($_GET['add']) || isset($_GET['edit'])){
      $brandQuery = $db->query("SELECT * FROM brand ORDER BY brand");
      $parentQuery = $db->query("SELECT * FROM categories WHERE parent = 0 ORDER BY category");

      $title =((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):'');
      $brand = ((isset($_POST['brand']) && $_POST['brand'] != '')?sanitize($_POST['brand']):'');
      $parent = ((isset($_POST['parent']) && $_POST['parent'] != '')?sanitize($_POST['parent']):'');
      $category = ((isset($_POST['child']) && $_POST['child'] != '')?sanitize($_POST['child']):'');
      $price =((isset($_POST['price']) && $_POST['price'] != '')?sanitize($_POST['price']):'');
      $description =((isset($_POST['description']) && $_POST['description'] != '')?sanitize($_POST['description']):'');
      $saved_image = '';


       if(isset($_GET['edit'])){
         $edit_id = (int)$_GET['edit'];
         $productResults = $db->query("SELECT * FROM foods WHERE id = '$edit_id'");
         $product = mysqli_fetch_assoc($productResults);
         if(isset($_GET['delete_image'])){
           $image_url = $_SERVER['DOCUMENT_ROOT'].$product['image'];
           unlink($image_url);
           $db->query("UPDATE foods SET image = '' WHERE id = '$edit_id'");
           header('Location: Foods.php?edit='.$edit_id);
         }
         $category = ((isset($_POST['child']) && $_POST['child'] != '')?sanitize($_POST['child']):$product['categories']);
         $title = ((isset($_POST['title']) && !empty($_POST['title']))?sanitize($_POST['title']):$product['title']);
         $brand = ((isset($_POST['brand']) && !empty($_POST['brand']))?sanitize($_POST['brand']):$product['brand']);
         $parentQ = $db->query("SELECT * FROM categories WHERE id = '$category'");
         $parentResult = mysqli_fetch_assoc($parentQ);
         $parent = ((isset($_POST['parent']) && !empty($_POST['parent']))?sanitize($_POST['parent']):$parentResult['parent']);
         $price = ((isset($_POST['price']) && !empty($_POST['price']))?sanitize($_POST['price']):$product['price']);
         $description = ((isset($_POST['description']) && !empty($_POST['description']))?sanitize($_POST['description']):$product['description']);
         $saved_image = (($product['image'] != '')?$product['image']:'');
         $dbPath = $saved_image;
       }

      if($_POST){
        $dbPath = '';
        $errors = array();
        $required = array('title', 'brand', 'price', 'parent', 'child');
        foreach ($required as $field) {
          if($_POST[$field] == ''){
            $errors[] = 'All Fields are required';
            break;
          }
        }

      if(!empty($_FILES)){
          $photo = $_FILES['photo'];
          $name = $photo['name'];
          $nameArray = explode('.',$name);
          $fileName = $nameArray[0];
          $fileExt = $nameArray[1];
          $mime = explode('/',$photo['type']);
          $mimeType = $mime[0];
          $mimeExt = $mime[1];
          $tmpLoc = $photo['tmp_name'];
          $fileSize = $photo['size'];
          $allowed = array('png', 'jpg', 'jpeg', 'gif');
          $uploadName =md5(microtime()).'.'.$fileExt;
          $uploadLoc = BASEURL.'img/foods/'.$uploadName;
          $dbPath = '/Project/princespark/img/foods/'.$uploadName;

          if($mimeType != 'image'){
           $errors[] = 'The file must be an image.';
        }
        if(!in_array($fileExt, $allowed)){
          $errors[] = 'The file extenstion must be a png, jpg, jpeg or gif.';
        }
        if($fileSize > 25000000){
          $errors[] = 'The file size must be under 25MB';
        }
        if($fileExt != $mimeExt && ($mimeExt == 'jpeg' && $fileExt != 'jpg')){
          $errors[] = 'File extenstion does not match the file.';
        }
        if(!empty($errors)){

        }else{
          //upload file and insert to the database
          move_uploaded_file($tmpLoc,$uploadLoc);
          $insertSQL = "INSERT INTO foods (`title`,`price`,`brand`,`categories`,`image`,`description`)
          Values ('$title', '$price', '$brand', '$category', '$dbPath','$description')";
          if(isset($_GET['edit'])){
            $insertSQL = "UPDATE foods SET title = '$title', price = '$price', brand = '$brand', categories = '$category', image = '$dbPath', description = '$description' WHERE id = '$edit_id'";
          }
          $db->query($insertSQL);
          header('Location: Foods.php');
        }
      }
    }
      ?>
      <!doctype html>
      <html>
      <head>
          <meta charset="utf-8">
          <meta http-equiv="X-UA-Compatible" content="IE=edge">
          <title>Princes Park Admin</title>
          <meta name="viewport" content="width=device-width, initial-scale=1">

          <link rel="apple-touch-icon" href="apple-icon.png">
          <link rel="shortcut icon" href="favicon.ico">

          <link rel="stylesheet" href="assets/css/normalize.css">
          <link rel="stylesheet" href="assets/css/bootstrap.min.css">
          <link rel="stylesheet" href="assets/css/font-awesome.min.css">
          <link rel="stylesheet" href="assets/css/themify-icons.css">
          <link rel="stylesheet" href="assets/css/flag-icon.min.css">
          <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
          <!-- <link rel="stylesheet" href="assets/css/bootstrap-select.less"> -->
          <link rel="stylesheet" href="assets/scss/style.css">
          <link href="assets/css/lib/vector-map/jqvmap.min.css" rel="stylesheet">
          <link rel="stylesheet" href="../css/main.css">


          <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>

          <!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/html5shiv/3.7.3/html5shiv.min.js"></script> -->

      </head>
      <body>
              <!-- Left Panel -->
          <?php
            include 'include/left_panel.php';
           ?>
      <div id="right-panel" class="right-panel">
           <!-- Header-->
           <header id="header" class="header">

               <div class="header-menu">

                   <div class="col-sm-7">
                       <a id="menuToggle" class="menutoggle pull-left"><i class="fa fa fa-tasks"></i></a>
                       <div class="header-left">
                           <button class="search-trigger"><i class="fa fa-search"></i></button>
                           <div class="form-inline">
                               <form class="search-form">
                                   <input class="form-control mr-sm-2" type="text" placeholder="Search ..." aria-label="Search">
                                   <button class="search-close" type="submit"><i class="fa fa-close"></i></button>
                               </form>
                           </div>

                           <div class="dropdown for-notification">
                             <button class="btn btn-secondary dropdown-toggle" type="button" id="notification" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                               <i class="fa fa-bell"></i>
                               <span class="count bg-danger">5</span>
                             </button>
                             <div class="dropdown-menu" aria-labelledby="notification">
                               <p class="red">You have 3 Notification</p>
                               <a class="dropdown-item media bg-flat-color-1" href="#">
                                   <i class="fa fa-check"></i>
                                   <p>Server #1 overloaded.</p>
                               </a>
                               <a class="dropdown-item media bg-flat-color-4" href="#">
                                   <i class="fa fa-info"></i>
                                   <p>Server #2 overloaded.</p>
                               </a>
                               <a class="dropdown-item media bg-flat-color-5" href="#">
                                   <i class="fa fa-warning"></i>
                                   <p>Server #3 overloaded.</p>
                               </a>
                             </div>
                           </div>

                           <div class="dropdown for-message">
                             <button class="btn btn-secondary dropdown-toggle" type="button"
                                   id="message"
                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                               <i class="ti-email"></i>
                               <span class="count bg-primary">9</span>
                             </button>
                             <div class="dropdown-menu" aria-labelledby="message">
                               <p class="red">You have 4 Mails</p>
                               <a class="dropdown-item media bg-flat-color-1" href="#">
                                   <span class="photo media-left"><img alt="avatar" src="images/avatar/1.jpg"></span>
                                   <span class="message media-body">
                                       <span class="name float-left">Jonathan Smith</span>
                                       <span class="time float-right">Just now</span>
                                           <p>Hello, this is an example msg</p>
                                   </span>
                               </a>
                               <a class="dropdown-item media bg-flat-color-4" href="#">
                                   <span class="photo media-left"><img alt="avatar" src="images/avatar/2.jpg"></span>
                                   <span class="message media-body">
                                       <span class="name float-left">Jack Sanders</span>
                                       <span class="time float-right">5 minutes ago</span>
                                           <p>Lorem ipsum dolor sit amet, consectetur</p>
                                   </span>
                               </a>
                               <a class="dropdown-item media bg-flat-color-5" href="#">
                                   <span class="photo media-left"><img alt="avatar" src="images/avatar/3.jpg"></span>
                                   <span class="message media-body">
                                       <span class="name float-left">Cheryl Wheeler</span>
                                       <span class="time float-right">10 minutes ago</span>
                                           <p>Hello, this is an example msg</p>
                                   </span>
                               </a>
                               <a class="dropdown-item media bg-flat-color-3" href="#">
                                   <span class="photo media-left"><img alt="avatar" src="images/avatar/4.jpg"></span>
                                   <span class="message media-body">
                                       <span class="name float-left">Rachel Santos</span>
                                       <span class="time float-right">15 minutes ago</span>
                                           <p>Lorem ipsum dolor sit amet, consectetur</p>
                                   </span>
                               </a>
                             </div>
                           </div>
                       </div>
                   </div>

                   <div class="col-sm-5">
                       <div class="user-area dropdown float-right">
                           <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                               <img class="user-avatar rounded-circle" src="images/admin.jpg" alt="User Avatar">
                           </a>

                           <div class="user-menu dropdown-menu">
                                   <a class="nav-link" href="#"><i class="fa fa- user"></i>My Profile</a>
                                   <a class="nav-link" href="#"><i class="fa fa -cog"></i>Settings</a>
                                   <a class="nav-link" href="#"><i class="fa fa-power -off"></i>Logout</a>
                           </div>
                       </div>

                       <div class="language-select dropdown" id="language-select">
                           <a class="dropdown-toggle" href="#" data-toggle="dropdown"  id="language" aria-haspopup="true" aria-expanded="true">
                               <i class="flag-icon flag-icon-us"></i>
                           </a>
                           <div class="dropdown-menu" aria-labelledby="language" >
                               <div class="dropdown-item">
                                   <span class="flag-icon flag-icon-fr"></span>
                               </div>
                               <div class="dropdown-item">
                                   <i class="flag-icon flag-icon-es"></i>
                               </div>
                               <div class="dropdown-item">
                                   <i class="flag-icon flag-icon-us"></i>
                               </div>
                               <div class="dropdown-item">
                                   <i class="flag-icon flag-icon-it"></i>
                               </div>
                           </div>
                       </div>

                   </div>
               </div>

           </header><!-- /header -->
           <!-- Header-->

           <div class="breadcrumbs">
               <div class="col-sm-4">
                   <div class="page-header float-left">
                       <div class="page-title">
                           <h1>Dashboard</h1>
                       </div>
                   </div>
               </div>
               <div class="col-sm-8">
                   <div class="page-header float-right">
                       <div class="page-title">
                           <ol class="breadcrumb text-right">
                               <li class="active"></li>
                           </ol>
                       </div>
                   </div>
               </div>
           </div>

             <div class="content mt-3">

               <h3 class="text-center"><?=((isset($_GET['edit']))?'Edit ':'Add A New ');?>Food</h3><br>
               <?php
               if(!empty($errors)){
                 echo display_errors($errors);
               }

              ?>
               <form action="Foods.php?<?=((isset($_GET['edit']))?'edit='.$edit_id:'add=1');?>" method="post" enctype="multipart/form-data">
                 <div class="form-group col-md-6">
                   <label for="title">Title* :</label>
                   <input type="text" name="title" class="form-control" id="title" value="<?=$title?>">
                 </div>
                 <div class="form-group col-md-6">
                   <label for="brand">Couisine* :</label>
                   <select class="form-control" id="brand" name="brand">
                     <option value=""<?=(($brand == '')?' selected':'');?>></option>
                     <?php while($b = mysqli_fetch_assoc($brandQuery)): ?>
                       <option value="<?=$b['id'];?>"<?=(($brand == $b['id'])?' selected':'');?>><?=$b['brand'];?></option>
                     <?php endwhile; ?>
                   </select>
                 </div>
                 <div class="form-group col-md-6">
                   <label for="parent">Parent Category* :</label>
                   <select class="form-control" name="parent" id="parent">
                     <option value=""<?=(($parent == '')?' selected':'');?>></option>
                     <?php while($p = mysqli_fetch_assoc($parentQuery)): ?>
                       <option value="<?=$p['id']?>"<?=(($parent == $p['id'])?' selected':'');?>><?=$p['category'];?></option>
                     <?php endwhile; ?>
                   </select>
                 </div>
                 <div class="form-group col-md-6">
                   <label for="child">Child Category* :</label>
                   <select class="form-control" name="child" id="child">
                   </select>
                 </div>
                 <div class="form-group col-md-6">
                   <label for="price">Price* :</label>
                   <input type="text" name="price" id="price" class="form-control" value="<?=$price;?>">
                 </div>
                 <div class="form-group col-md-6">
                  <?php if($saved_image != ''): ?>
                    <div class="saved-image">
                      <img src="<?=$saved_image;?>" alt="Saved Image" style="width: 200px; height: auto;"><br>
                      <a href="Foods.php?delete_image=1&edit=<?=$edit_id;?>" class="text-danger">Delete Image</a>
                    </div>
                  <?php else: ?>
                   <label for="photo">Food Photo :</label>
                   <input type="file" name="photo" id="photo" class="form-control">
                 <?php endif; ?>
                 </div>
                 <div class="form-group col-md-12">
                   <label for="description">Description :</label>
                   <textarea name="description" id="description" class="form-control" rows="6"><?=$description;?></textarea>
                 </div>
                 <div class="form-group pull-right">
                   <a href="Foods.php" class="btn btn-secondary" style="border-radius: 4px;">Cancel</a>
                   <input type="submit" value="<?=((isset($_GET['edit']))?'Edit ':'Add ');?>Food" class="btn btn-success" id="addFood" style="border-radius: 4px;">
                   <div class="clearfix"></div>
                 </div>

               </form>


             </div> <!-- .content -->


          <script src="assets/js/vendor/jquery-2.1.4.min.js"></script>
          <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"></script>
          <script src="assets/js/plugins.js"></script>
          <script src="assets/js/main.js"></script>


          <script src="assets/js/lib/chart-js/Chart.bundle.js"></script>
          <script src="assets/js/dashboard.js"></script>
          <script src="assets/js/widgets.js"></script>
          <script src="assets/js/lib/vector-map/jquery.vmap.js"></script>
          <script src="assets/js/lib/vector-map/jquery.vmap.min.js"></script>
          <script src="assets/js/lib/vector-map/jquery.vmap.sampledata.js"></script>
          <script src="assets/js/lib/vector-map/country/jquery.vmap.world.js"></script>
          <script>
              ( function ( $ ) {
                  "use strict";

                  jQuery( '#vmap' ).vectorMap( {
                      map: 'world_en',
                      backgroundColor: null,
                      color: '#ffffff',
                      hoverOpacity: 0.7,
                      selectedColor: '#1de9b6',
                      enableZoom: true,
                      showTooltip: true,
                      values: sample_data,
                      scaleColors: [ '#1de9b6', '#03a9f5' ],
                      normalizeFunction: 'polynomial'
                  } );
              } )( jQuery );
          </script>
          <script type="text/javascript">
            function get_child_options(selected){
              if(typeof selected === 'undefined'){
                var selected = '';
              }
              var parentID = jQuery('#parent').val();
              jQuery.ajax({
                url: '/Project/Admin/parsers/child_categories.php',
                type: 'POST',
                data: {parentID : parentID, selected: selected},
                success: function(data){
                  jQuery('#child').html(data);
                },
                error: function(){alert("Something went wrong with the child options.")},
              });
            }
            jQuery('select[name="parent"]').change(function(){
              get_child_options();
            });
          </script>
          <script>
             jQuery('document').ready(function(){
               get_child_options('<?=$category;?>');
             });
          </script>
      </div>
      </body>
      </html>
    <?php }else{

    $sql = "SELECT * FROM foods WHERE deleted = 0";
    $presult = $db->query($sql);

    if(isset($_GET['featured'])){
      $id = (int)$_GET['id'];
      $featured = (int)$_GET['featured'];
      $featuredSql = "UPDATE foods SET featured = '$featured' WHERE id = '$id'";
      $db->query($featuredSql);
      header('Location: Foods.php');
    }

?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Princes Park Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="apple-icon.png">
    <link rel="shortcut icon" href="favicon.ico">

    <link rel="stylesheet" href="assets/css/normalize.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/themify-icons.css">
    <link rel="stylesheet" href="assets/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/css/cs-skin-elastic.css">
    <!-- <link rel="stylesheet" href="assets/css/bootstrap-select.less"> -->
    <link rel="stylesheet" href="assets/scss/style.css">
    <link href="assets/css/lib/vector-map/jqvmap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/main.css">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>

    <!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/html5shiv/3.7.3/html5shiv.min.js"></script> -->

</head>
<body>
        <!-- Left Panel -->
    <?php
      include 'include/left_panel.php';
     ?>
<div id="right-panel" class="right-panel">
     <!-- Header-->
     <header id="header" class="header">

         <div class="header-menu">

             <div class="col-sm-7">
                 <a id="menuToggle" class="menutoggle pull-left"><i class="fa fa fa-tasks"></i></a>
                 <div class="header-left">
                     <button class="search-trigger"><i class="fa fa-search"></i></button>
                     <div class="form-inline">
                         <form class="search-form">
                             <input class="form-control mr-sm-2" type="text" placeholder="Search ..." aria-label="Search">
                             <button class="search-close" type="submit"><i class="fa fa-close"></i></button>
                         </form>
                     </div>

                     <div class="dropdown for-notification">
                       <button class="btn btn-secondary dropdown-toggle" type="button" id="notification" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                         <i class="fa fa-bell"></i>
                         <span class="count bg-danger">5</span>
                       </button>
                       <div class="dropdown-menu" aria-labelledby="notification">
                         <p class="red">You have 3 Notification</p>
                         <a class="dropdown-item media bg-flat-color-1" href="#">
                             <i class="fa fa-check"></i>
                             <p>Server #1 overloaded.</p>
                         </a>
                         <a class="dropdown-item media bg-flat-color-4" href="#">
                             <i class="fa fa-info"></i>
                             <p>Server #2 overloaded.</p>
                         </a>
                         <a class="dropdown-item media bg-flat-color-5" href="#">
                             <i class="fa fa-warning"></i>
                             <p>Server #3 overloaded.</p>
                         </a>
                       </div>
                     </div>

                     <div class="dropdown for-message">
                       <button class="btn btn-secondary dropdown-toggle" type="button"
                             id="message"
                             data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                         <i class="ti-email"></i>
                         <span class="count bg-primary">9</span>
                       </button>
                       <div class="dropdown-menu" aria-labelledby="message">
                         <p class="red">You have 4 Mails</p>
                         <a class="dropdown-item media bg-flat-color-1" href="#">
                             <span class="photo media-left"><img alt="avatar" src="images/avatar/1.jpg"></span>
                             <span class="message media-body">
                                 <span class="name float-left">Jonathan Smith</span>
                                 <span class="time float-right">Just now</span>
                                     <p>Hello, this is an example msg</p>
                             </span>
                         </a>
                         <a class="dropdown-item media bg-flat-color-4" href="#">
                             <span class="photo media-left"><img alt="avatar" src="images/avatar/2.jpg"></span>
                             <span class="message media-body">
                                 <span class="name float-left">Jack Sanders</span>
                                 <span class="time float-right">5 minutes ago</span>
                                     <p>Lorem ipsum dolor sit amet, consectetur</p>
                             </span>
                         </a>
                         <a class="dropdown-item media bg-flat-color-5" href="#">
                             <span class="photo media-left"><img alt="avatar" src="images/avatar/3.jpg"></span>
                             <span class="message media-body">
                                 <span class="name float-left">Cheryl Wheeler</span>
                                 <span class="time float-right">10 minutes ago</span>
                                     <p>Hello, this is an example msg</p>
                             </span>
                         </a>
                         <a class="dropdown-item media bg-flat-color-3" href="#">
                             <span class="photo media-left"><img alt="avatar" src="images/avatar/4.jpg"></span>
                             <span class="message media-body">
                                 <span class="name float-left">Rachel Santos</span>
                                 <span class="time float-right">15 minutes ago</span>
                                     <p>Lorem ipsum dolor sit amet, consectetur</p>
                             </span>
                         </a>
                       </div>
                     </div>
                 </div>
             </div>

             <div class="col-sm-5">
                 <div class="user-area dropdown float-right">
                     <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                         <img class="user-avatar rounded-circle" src="images/admin.jpg" alt="User Avatar">
                     </a>

                     <div class="user-menu dropdown-menu">
                             <a class="nav-link" href="#"><i class="fa fa- user"></i>My Profile</a>
                             <a class="nav-link" href="#"><i class="fa fa -cog"></i>Settings</a>
                             <a class="nav-link" href="#"><i class="fa fa-power -off"></i>Logout</a>
                     </div>
                 </div>

                 <div class="language-select dropdown" id="language-select">
                     <a class="dropdown-toggle" href="#" data-toggle="dropdown"  id="language" aria-haspopup="true" aria-expanded="true">
                         <i class="flag-icon flag-icon-us"></i>
                     </a>
                     <div class="dropdown-menu" aria-labelledby="language" >
                         <div class="dropdown-item">
                             <span class="flag-icon flag-icon-fr"></span>
                         </div>
                         <div class="dropdown-item">
                             <i class="flag-icon flag-icon-es"></i>
                         </div>
                         <div class="dropdown-item">
                             <i class="flag-icon flag-icon-us"></i>
                         </div>
                         <div class="dropdown-item">
                             <i class="flag-icon flag-icon-it"></i>
                         </div>
                     </div>
                 </div>

             </div>
         </div>

     </header><!-- /header -->
     <!-- Header-->

     <div class="breadcrumbs">
         <div class="col-sm-4">
             <div class="page-header float-left">
                 <div class="page-title">
                     <h1>Dashboard</h1>
                 </div>
             </div>
         </div>
         <div class="col-sm-8">
             <div class="page-header float-right">
                 <div class="page-title">
                     <ol class="breadcrumb text-right">
                         <li class="active"></li>
                     </ol>
                 </div>
             </div>
         </div>
     </div>

       <div class="content mt-3">

         <h3 class="text-center">Foods</h3><br>
         <a href="Foods.php?add=1" class="btn btn-success pull-right" id="add-product-btn" style="border-radius: 4px;">Add Food</a><div class="clearfix" ></div>
         <hr>
         <table class="table table-striped table-bordered">
           <thead><th></th><th>Foods</th><th>Price</th><th>Featured</th></thead>
           <tbody>
             <?php while($product = mysqli_fetch_assoc($presult)):


               ?>
               <tr>
                 <td>
                   <a href = "Foods.php?edit=<?=$product['id'];?>"><span class="fa fa-pencil"></span></a>&nbsp;&nbsp;
                   <a href = "Foods.php?delete=<?=$product['id'];?>"><span class="fa fa-close"></span></a>
                 </td>
                 <td><?=$product['title'];?></td>
                 <td><?=money($product['price']);?></td>

                 <td><a href="Foods.php?featured=<?=(($product['featured'] == 0)?'1':'0');?>&id=<?=$product['id'];?>"><span class="fa fa-<?=(($product['featured'] == 1)?'minus':'plus');?>"></span>
                </a>
                &nbsp; <?=(($product['featured'] == 1)?'Featured Product':'');?>
              </td>
               </tr>

             <?php endwhile; ?>
           </tbody>
         </table>


       </div> <!-- .content -->


    <script src="assets/js/vendor/jquery-2.1.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"></script>
    <script src="assets/js/plugins.js"></script>
    <script src="assets/js/main.js"></script>


    <script src="assets/js/lib/chart-js/Chart.bundle.js"></script>
    <script src="assets/js/dashboard.js"></script>
    <script src="assets/js/widgets.js"></script>
    <script src="assets/js/lib/vector-map/jquery.vmap.js"></script>
    <script src="assets/js/lib/vector-map/jquery.vmap.min.js"></script>
    <script src="assets/js/lib/vector-map/jquery.vmap.sampledata.js"></script>
    <script src="assets/js/lib/vector-map/country/jquery.vmap.world.js"></script>
    <script>
        ( function ( $ ) {
            "use strict";

            jQuery( '#vmap' ).vectorMap( {
                map: 'world_en',
                backgroundColor: null,
                color: '#ffffff',
                hoverOpacity: 0.7,
                selectedColor: '#1de9b6',
                enableZoom: true,
                showTooltip: true,
                values: sample_data,
                scaleColors: [ '#1de9b6', '#03a9f5' ],
                normalizeFunction: 'polynomial'
            } );
        } )( jQuery );
    </script>
</div>
</body>
</html>
   <?php } ?>
