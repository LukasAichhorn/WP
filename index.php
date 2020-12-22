<?php
// all includes and requires
include "Dir-Config.php";
require "classes/DBManager.class.php";
require "classes/Post.class.php";
require "classes/User.class.php";
require "classes/UserManager.class.php";
require "classes/validator.class.php";
require "classes/PostManager.class.php";
?>



<?php
 
//instntiate DB Object
$DB = new DBManager();
$DB->ConnectDB();
//instantiate UserManager
$UserManager = new UserManager();
$UserManager->startSession();
$status = $UserManager->checkStatus();
//get Current user abject from Session
$CurrentUser = $UserManager->getUser();
//Create PostManager and fetch all posts
$PostManager = new PostManager();
$Posts = $PostManager->fetchPosts($DB,$status);
$Tags=$DB->allTags();
?>


<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Bree+Serif&family=Open+Sans&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
  <title>GoellHorn</title>
</head>

<body>
  
    <!-- +++++++++++++++++++++++++++++++  Navigation +++++++++++++++++++++++++++++++++++ -->
    <?php include "components/navigationBar.comp.php"?>
    <!-- +++++++++++++++++++++++++++++++  Main 3 colum layout +++++++++++++++++++++++++++++++++++ -->
    <div class="container-fluid"> 
    
      

      <div class="row login-BG" style="background-image: url(./ressources/pics/bermuda-fatal-error-1.png)">
        <div class="col-2">

        </div>
        
        <div class="col">
          <div class="mt-5 mb-5 headline-info">
          <h1 class="headline">Welcome to GoellHorn!</h1>
          <?php if(isset($CurrentUser)){
            echo("<p>Your are logged in as $CurrentUser->UserName</p>");
          }
          else{
            echo("<p>Your are not logged in and only see 'public' posts!</p>");
          }
          ?>    
          </div> 

          <?php include "components/search.comp.php"?>

          <div class="mt-5">
          
          </div>
          



        </div>

        <div class="col-2">

        </div>
      </div>
      <div class="row">
      <div class="col">

      </div>

      <div class="col-6 p-4 minusTop">     
      
      <?php $PostManager->display($DB,$Posts);?>
      
      
       
            
         
        </div>
        

      

      <div class="col">
    </div>

  














    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js" integrity="sha384-q2kxQ16AaE6UbzuKqyBE9/u/KzioAlnx2maXQHiDX9d4/zp8Ok3f+M7DPm+Ib6IU" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-pQQkAEnwaBkjpqZ8RU1fF1AKtTcHJwFl3pblpTlHXybJjHpMYo79HY3hIi4NKxyj" crossorigin="anonymous"></script>
    -->
</body>

</html>