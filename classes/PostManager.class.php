<?php
//händelt alle Interaktionen mit Posts
class PostManager
{
    private $Posts;


    //DB Objekt und UserStatus wird übergeben
    function FetchPosts($DB, $status)
    {

        $this->Posts = $DB->getPosts($status);
        return $this->Posts;
    }

    //alle Posts im Array werden mit der Komponente unten angezeigt
    function display($DB, $Posts, $CurrentUser)
    {

        foreach ($Posts as $Post) {
            $commentnr = $DB->commentCount($Post->PostID);
            include "C:/xampp/htdocs/WEB_SS2020/WP/components/post.comp.php";
        }
    }

    function handleNewPost($DB, $CurrentUser)
    {   
        $nM = new NotificationHandler();
        $nM->initAlerts();

        if (
            isset($_POST["Titel"]) &&
            isset($_POST["Textarea1"])
        ) {

            $validator = new Validator();

            $Title = $validator->validate_string($_POST["Titel"]);
            $Text = $validator->validate_string($_POST["Textarea1"]);

            $TagsSelected = array();
            $Tags = $DB->allTags();
            //Vergleich des Arrays mit allen Tags mit den im POST array gespeicherten Tags
            foreach ($Tags as $Tag) {
                if (isset($_POST[$Tag[0]])) {
                    array_push($TagsSelected, $Tag);
                }
            }
            

            //speichert Adresse und Bildernamen in diese Variable
            $ImageUpload = $this->handleImgUpload($CurrentUser);
            if ($ImageUpload == 0) {
                $ImageUpload = ["localhost/WEB_SS2020/WP/ressources/pics/Default_img_thumbnail.png", "Default_img_thumbnail.png"];
            }

            $_PostID = NUll;
            $_Bildadresse = $ImageUpload[0];
            $_Bildname = $ImageUpload[1];
            $_Titel = $Title;
            $_Inhalt = $Text;
            $_Sichtbarkeit = (isset($_POST["checkPrivate"])) ? 0 : 1;
            $_FKUserID = $CurrentUser->UserID;
            $NewPost = new Post($_PostID, "Fisch", $_Bildadresse, $_Bildname, $_Titel, $_Inhalt, $_Sichtbarkeit, $_FKUserID, $TagsSelected, 1234, 0, 0);


            $DB->insertPost($NewPost);
            header("Refresh:0; url=//" . DIR_BASE ."index.php");
            $nM->pushNotification("Post successfully created!","success");
        }
    }

    function handleImgUpload($CurrentUser)
    {

        $nM = new NotificationHandler();
        $nM->initAlerts();

        if (!($_FILES["fileUpload"]["name"] == "")) {
            echo ("isset fileupload");
            $targetDir = $CurrentUser->RootFolder . "/" . basename($_FILES["fileUpload"]["name"]);
            $target_file = DIR_ROOT . "/WEB_SS2020/WP/UsersRoot/" . $CurrentUser->UserName . "/" . basename($_FILES["fileUpload"]["name"]);
            $fileName = basename($_FILES["fileUpload"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            //check image type: 
            if (
                $imageFileType != "jpg" &&
                $imageFileType != "png" &&
                $imageFileType != "jpeg" &&
                $imageFileType != "gif"
            ) {
                echo "Only JPG, JPEG, PNG & GIF files are allowed.";
                return 0;
            }

            //upload to User directory
            if (move_uploaded_file($_FILES["fileUpload"]["tmp_name"], $target_file)) {

                $ImgProcessor = new ImageProcessor();
                $ImgProcessor->createThumbnail($target_file);
                return [$targetDir, $fileName];
            } else {
                $nM->pushNotification("file upload error: " . $_FILES["fileUpload"]["error"], "danger");
                return 0;
            }
        }
        $nM->pushNotification("no image was selected!", "primary");
        return 0;
    }

    function handleNewComment($DB, $PostID, $CurrentUserID, $URLPath)
    {

        if (!empty($_POST["Comment_text"])) {

            $validator = new Validator();
            $text = $validator->validate_string($_POST["Comment_text"]);
            $CreatedAt = Null;
            $NewComment = new Comment($text, $CurrentUserID, $PostID, $CreatedAt);

            $DB->insertComment($NewComment);
            header("Refresh:0; url=$URLPath");
        }
    }
    function handleSearch($DB, $status)
    {
        $nM = new NotificationHandler();
        $nM->initAlerts();


        if (!empty($_GET)) 
        {

            if (isset($_GET['filter'])) {

                if ($_GET["filter"] == "t_ASC") {//time ascending = älteste zuerst
                    $col = "CreatedAt";
                    $order = "ASC";
                    
                } elseif ($_GET["filter"] == "t_DESC") {// time descending = neueste zuerst
                    $col = "CreatedAt";
                    $order = "DESC";
                    
                } elseif ($_GET["filter"] == "likes") { //meiste Likes zuerst
                    $col = "Likes";
                    $order = "DESC";
                    
                } elseif ($_GET["filter"] == "dislikes") {// meiste Dislikes zuerst
                    $col = "Dislikes";
                    $order = "DESC";
                    
                }
                $Posts = $DB->getPosts($status, $col, $order);

                
                return $Posts;
            }
            
            $Tags = $DB->allTags();
            $selectedTags = array();
            
            if (isset($_GET["string"])) {
                $string = $_GET["string"];
            } else {
                $string = "";
            }
            $tagsSelected = false;


            foreach ($Tags as $Tag) {

                if (array_key_exists($Tag[0], $_GET)) {
                    
                    array_push($selectedTags, $Tag[0]);
                }
            }
            if (!empty($selectedTags)) {
                $tagsSelected = true;
            }

            // no longer needed because of DB search Query adaptions
            // if (empty($selectedTags)) {
            //     foreach ($Tags as $Tag) {
            //         array_push($selectedTags, $Tag[0]);
            //     }
            // }

            if ($filteredPosts = $DB->searchPosts($string, $selectedTags, $status,$tagsSelected)) {
                
                return $filteredPosts;
            } else {
                //generate error message filter problem 


                $nM->pushNotification("No posts match your search!", "warning");
            }
        } 
        else 
        {   //default if no search param or filter was selected
            $filteredPosts = $DB->searchPosts("",[],$status,false);
            return $filteredPosts;
            
        }
    }
}
