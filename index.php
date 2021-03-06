<?php
// all includes and requires
include "Dir-Config.php";
require "classes/DBManager.class.php";
require "classes/Post.class.php";
require "classes/User.class.php";
require "classes/UserManager.class.php";
require "classes/validator.class.php";
require "classes/PostManager.class.php";
require "classes/NotificationHandler.class.php";
?>



<?php

//instntiate DB Object
$DB = new DBManager();
$DB->ConnectDB();



//instantiate UserManager
$UserManager = new UserManager();
$UserManager->startSession();

$Notifications = new NotificationHandler();
$Notifications->initAlerts();

$status = $UserManager->checkStatus();
//get Current user object from Session
$CurrentUser = $UserManager->getUser();
//Create PostManager and fetch all posts
$PostManager = new PostManager();
$filteredPosts = $PostManager->handleSearch($DB, $status);

/*if ($filteredPosts == 0) {
  $filteredPosts = $PostManager->FetchPosts($DB,$status);
}*/
// handle additional filters:



$Tags = $DB->allTags();








?>


<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
 

 <!-- lightbox  CSS -->
 




  <!-- Bootstrap CSS -->
 
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Bree+Serif&family=Open+Sans&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="ressources\plugins\lightbox2-2.11.3\dist\css\lightbox.min.css">
  <link rel="stylesheet" href="style.css">
  <title>GoellHorn</title>
</head>

<body>

  <!-- +++++++++++++++++++++++++++++++  Navigation +++++++++++++++++++++++++++++++++++ -->
  <?php include "components/navigationBar.comp.php" ?>
  <!-- +++++++++++++++++++++++++++++++  Main 3 colum layout +++++++++++++++++++++++++++++++++++ -->
  <div class="container-fluid">


    <div class="alertContainer">
      <?php
      $Notifications->display();

      ?>
    </div>

  </div>


  <div class="row index-BG" >
    <div class="col-xl-2 col-lg-2  col-md-1 col-1 col-sm-1">

    </div>

    <div class="col">
      <div class="mt-5 mb-5 headline-info">
        <h1 class="headline">Willkommen auf GoellHorn!</h1>
        <?php if (isset($CurrentUser)) {
          echo ("<p class='headline-text' >Sie sind eingeloggt als $CurrentUser->UserName</p>");
        } else {
          echo ("<p class='headline-text' >Sie sind nicht eingeloggt und sehen nur öffentliche Beiträge</p>");
        }
        ?>
      </div>

      <?php include "components/search.comp.php" ?>

      <div class="mt-5">

      </div>




    </div>

    <div class="col-xl-2 col-lg-2  col-md-1 col-1 col-sm-1">

    </div>
  </div>
  <div class="row">
    <div class="col">

    </div>

    <div class="col-xl-6 col-lg-10 p-4 minusTop">
      <div class="d-flex sort-container mb-3">
        <?php
        //diese funktionalität ist notwendig um den derzeit ausgewählten Sortiermodus zu deaktivieren
        $l = $d = $ta = FALSE;//likes, dislikes, time ascending
        $td = true;// time descending
        if (isset($_GET["filter"])) {
          $td = false;
          

          switch ($_GET["filter"]) {
            case 'likes':
              $l = True;
              break;
            case 'dislikes':
              $d = True;
              break;
            case 't_ASC':
              $ta = True;
              break;
            case 't_DESC':
              $td = True;
              break;
            default:
              # code...
              break;
          }
        }

        ?>

        <form class="btn-sort"  action="//<?php echo (DIR_SERVERROOT .  $_SERVER['REQUEST_URI']); ?>">
          <input type="hidden" name="filter" value="likes" />

          <button type="submit" <?php  echo( ($l==True) ? "disabled ":"" );?> class="btn btn-sm btn-sm-my btn-outline-primary">

            <?php echo ("meiste Likes") ?></button>
        </form>
        <form class="btn-sort" action="//<?php echo (DIR_SERVERROOT .  $_SERVER['REQUEST_URI']); ?>">
          <input type="hidden" name="filter" value="dislikes" />

          <button type="submit" <?php echo( ($d==True) ? "disabled ":""); ?> class="btn btn-sm btn-sm-my btn-outline-primary">

            <?php echo ("meiste Dislikes") ?></button>
        </form>

        <form class="btn-sort" action="//<?php echo (DIR_SERVERROOT .  $_SERVER['REQUEST_URI']); ?>">
          <input type="hidden" name="filter" value="t_DESC" />

          <button type="submit" <?php echo( ($td==True) ? "disabled":"") ; ?> class="btn btn-sm btn-sm-my btn-outline-primary">

            <?php echo ("Neueste zuerst") ?></button>
        </form>
        <form class="btn-sort" action="//<?php echo (DIR_SERVERROOT .  $_SERVER['REQUEST_URI']); ?>">
          <input type="hidden" name="filter" value="t_ASC" />

          <button type="submit" <?php echo( ($ta==True) ? "disabled ":"" ); ?> class="btn btn-sm btn-sm-my btn-outline-primary">

            <?php echo ("Älteste zuerst") ?></button>
        </form>


      </div>


      <?php if(!empty($filteredPosts)){
      $PostManager->display($DB, $filteredPosts, $CurrentUser); }?>





    </div>




    <div class="col">
    </div>

















    <?php require "components/JS_Imports.comp.php" ?>
  
</body>

</html>