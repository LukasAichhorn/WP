<?php 
require "../classes/DBManager.class.php";
require "../classes/NotificationHandler.class.php";
$DB= new DBManager();
$DB->ConnectDB();
$action = $_GET["action"];
$PostID = $_GET["PostId"];

if($action == 0){
    //like
    $DB->likePost($PostID);
}
else{
    $DB->dislikePost($PostID);
}

header("Refresh:0; url=" . $_SERVER["HTTP_REFERER"]);

?>