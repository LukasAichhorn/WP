<?php
class DBManager
{
    private $DB_LOCATION = "localhost";
    private $DB_USER = "root";
    private $DB_PW = "hl03VAPfORGedk0J";
    private $DB_NAME = "goellhornDB";
    private $DB;



    function ConnectDB()
    {

        $this->DB = new mysqli($this->DB_LOCATION, $this->DB_USER, $this->DB_PW, $this->DB_NAME);
        $DB = $this->DB;
        if ($DB->connect_errno) {
            echo "Failed to connect to MySQL: " . $DB->connect_error;
        } else {
            echo $DB->host_info . " Connection success!\n";
        }
    }



    //checking if pw of username matches the passwort stored in the database
    function validateUser($username, $passwort)
    {
        $DB = $this->DB;
        $pw = $this->HashPW($passwort);
        
        
        $ID = 0;

        //$qury = "SELECT UserID FROM goellhorndb.user WHERE Username = '" . $username . "' AND Passwort = '" . $pw . "';";

        if (!($stmt = $DB->prepare("SELECT UserID FROM goellhorndb.user WHERE Username = ? AND Passwort = ?"))) {
            echo "Prepare failed: (" . $DB->errno . ") " . $DB->error;
        }
        if (!$stmt->bind_param("ss", $username, $pw)) {
            echo "Binding Username failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }
        $stmt->bind_result($ID);

        $stmt->fetch();
        //returns corresponding ID if username and pw match, else returns null
        return $ID;

        
        
    }

    function insertUser($validUser)
    { //input is an object called User with valid data
        
        $DB = $this->DB;
        if (!($stmt = $DB->prepare("INSERT INTO goellhorndb.user(Username,Passwort,Anrede,Vorname,Nachname) VALUES (?,?,?,?,?)"))) {
            echo "Prepare failed: (" . $DB->errno . ") " . $DB->error;
        }

        if (!$stmt->bind_param("sssss", $validUser->UserName, $validUser->UserPW, $validUser->Anrede, $validUser->Vorname, $validUser->Nachname)) {
            echo "Binding Username failed: (" . $stmt->errno . ") " . $stmt->error;
        }


        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }
    }

    //finding data of UserID then creating and returning User Object with corresponding values
    function getUser($ID){
        if($ID != 0){
        //$values = array();
        $DB = $this->DB;
        $stmt = "SELECT * FROM goellhorndb.user WHERE UserID = '" . $ID . "';";

        $result = $DB->query($stmt);
        $values = $result->fetch_row();
        
        //var_dump($values); ERROR LOG
        $newUser = new User($values[0], $values[1], $values[2], $values[3], $values[4], $values[5], $values[6], $values[7], $values[8]);

        return $newUser;
        }
        else{
            echo "NOT A VALID ID!!";
        }

        
        
        
    }



    //checking if Username exists in our database returning corresponding bool, input:username as string
    function checkifUserExists($input)
    {
        $DB = $this->DB;
        $arr = array();

        $qury = "SELECT Username FROM goellhorndb.user WHERE Username = '" . $input . "';";

        $result = $DB->query($qury);
        $arr = $result->fetch_row();

        if (!empty($arr)) {
            echo "Succses!";
            //var_dump($arr); ERROR LOG
            return True;
        } else {
            var_dump($arr);
            echo "Fail!";
            return False;
        }
    }




    //function QueryForUser(){}

    function HashPW($PW)
    {
        $PW = hash("sha256", $PW);
        return $PW;
    }


    //fnc for getting assoc. array of posts to depict, output depends on loginstatus of user (public posts or all posts)
    function getPosts($status){
        $DB = $this->DB;
        if($status == 0){//no prepared statemnt needed because user input has no influence on query
            $stmt = "SELECT * FROM goellhorndb.post WHERE Sichtbarkeit = 1";
            $result = mysqli_query($DB, $stmt);
            $posts = array();
            if (mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_assoc($result)){
                    $posts[] = $row;
                }
            }
            
            //print_r($posts);
            return $posts;
            

            
        }
        else{
            $stmt = "SELECT * FROM goellhorndb.post";
            $result = mysqli_query($DB, $stmt);
            $posts = array();
            if (mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_assoc($result)){
                    $posts[] = $row;
                }
            }
            
            //print_r($posts);
            return $posts;

        }
    }

    function insertPost($validPost, $currentUser)
    { //input is an object of class: Post and the currentUser object
        
        $DB = $this->DB;
        if (!($stmt = $DB->prepare("INSERT INTO goellhorndb.post(Bildadresse,Bildname,Titel,Inhalt,Sichtbarkeit,FK_UserID) VALUES (?,?,?,?,?,?)"))) {
            echo "Prepare failed: (" . $DB->errno . ") " . $DB->error;
        }

        if (!$stmt->bind_param("ssssii", $validPost->Bildadresse, $validPost->Bildname, $validPost->Titel, $validPost->Inhalt, $validPost->Sichtbarkeit, $currentUser->UserID)) {
            echo "Binding Username failed: (" . $stmt->errno . ") " . $stmt->error;
        }


        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }
    }

    
    
}
