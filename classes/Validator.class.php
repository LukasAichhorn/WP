<?php
//diese Klasse enthält verschiedene Funktionen zur Validierung von Userinput
class Validator{





function validate_string($varName){      

        
        $varName =htmlspecialchars($varName);
        $varName = trim($varName);
        return $varName;
}

function validate_Password($PW){      

    $PW  =  hash("sha256",$PW);
            return $PW;
        
    }

function validate_Email($email){

        $email = trim(htmlspecialchars($email));
            $email = filter_var($email, FILTER_VALIDATE_EMAIL);
        if($email == false){
            return false;
        }
        return $email;
    }
function compareHashes($pw,$pwRep){
    if($pw == $pwRep){
        return TRUE;
    }
    else{
        return FALSE;
    }
}

    
}
?>
