<?php
class account{

    private $con;
    private $errorArray = array();

    public function __construct($con){
        $this->con = $con;
    }

    public function updateDetails($fn,$ln,$em,$un){
        $this->validateFirstName($fn);
        $this->validateLastName($ln);
        $this->validateNewEmail($em,$un);

        if(empty($this->errorArray)){
            $query=$this->con->prepare("UPDATE users SET firstName=:fn,lastName=:ln,email=:em
                    WHERE username=:un");
            $query->bindValue("fn",$fn);
            $query->bindValue("ln",$ln);
            $query->bindValue("em",$em);
            $query->bindValue("un",$un);

            return $query->execute();
            
        }

    }

    public function register($fn,$ln,$un,$em,$pw){
        $this->validateFirstName($fn);
        $this->validateLastName($ln);
        $this->validateUsername($un);
        $this->validateEmail($em);
        $this->validatePassword($pw);
    
        if(empty($this->errorArray)){
            return $this->insertUserDetails($fn,$ln,$un,$em,$pw);
        }
        return false;
    }
    public function login($un,$pw){

        $query= $this->con->prepare("SELECT * FROM users WHERE username=:un AND password=:pw");
        $query->bindValue(":un",$un);
        $pw = hash("sha512",$pw);
        $query->bindValue(":pw",$pw);
        $query->execute(); 
        if($query->rowCount()==1){
            return true;
        }
        
        array_push($this->errorArray,Constants::$loginFailed);
        return false;
    }

    private function insertUserDetails($fn,$ln,$un,$em,$pw){

        
        $query=$this->con->prepare("INSERT INTO users(firstName,lastName,username,email,password)
                         VALUES(:fn,:ln,:un,:em,:pw)");
         $query->bindValue(":fn",$fn);
         $query->bindValue(":ln",$ln);
         $query->bindValue(":un",$un);
         $query->bindValue(":em",$em);
         $pw = hash("sha512",$pw);
         $query->bindValue(":pw",$pw);
        
         return $query->execute();
        }

    private function validateFirstName($fn){
            if(strlen($fn)<2 || strlen($fn) >25){
                array_push($this->errorArray,Constants::$firstNameCharacter);
            }
    }

    private function validateLastName($ln){
        if(strlen($ln)<2 || strlen($ln) >25){
            array_push($this->errorArray,Constants::$LastNameCharacter);
        }
    }

    private function validateUsername($un){
        if(strlen($un)<2 || strlen($un) >25){
            array_push($this->errorArray,Constants::$UsernameCharacter);
            return;
        }

        $query= $this->con->prepare("SELECT * FROM users WHERE username=:un");
        $query->bindValue(":un",$un);
        $query->execute();
        if($query->rowCount()!=0){
            array_push($this->errorArray,Constants::$UsernameTaken);

        }
    }

    private function validateEmail($em){
        if(!filter_var($em,FILTER_VALIDATE_EMAIL)){
            array_push($this->errorArray,Constants::$EmailInvalid);
            return;
        }
        
        $query= $this->con->prepare("SELECT * FROM users WHERE email=:em");
        $query->bindValue(":em",$em);
        $query->execute();
        if($query->rowCount()!=0){
            array_push($this->errorArray,Constants::$EmailTaken);

        }
    }

    private function validateNewEmail($em,$un){
        if(!filter_var($em,FILTER_VALIDATE_EMAIL)){
            array_push($this->errorArray,Constants::$EmailInvalid);
            return;
        }
        
        $query= $this->con->prepare("SELECT * FROM users WHERE email=:em AND username != :un");
        $query->bindValue(":em",$em);
        $query->bindValue(":un",$un);
        $query->execute();
        if($query->rowCount()!=0){
            array_push($this->errorArray,Constants::$EmailTaken);

        }
    }
    private function validatePassword($pw){
        if(strlen($pw)<8 || strlen($pw) >25){
            array_push($this->errorArray,Constants::$Passwordlength);
        }    

    }
    private function validatePasswords($pw,$pw2){
        if($pw!=$pw2){
            array_push($this->errorArray,Constants::$passwordDontMatch);
            return;
        }
        if(strlen($pw)<8 || strlen($pw) >25){
            array_push($this->errorArray,Constants::$Passwordlength);
        }    

    }

    public function getError($error){
        if(in_array($error,$this->errorArray)){
            return "<span class='errorMessage'>$error</span>";
        }
    }

    public function getFirstError(){
        if(!empty($this->errorArray)){
            return $this->errorArray[0];
        }
    }
    public function updatePassword($oldPw,$pw,$pw2,$un){
        $this->validateOldPassword($oldPw,$un);
        $this->validatePasswords($pw,$pw2);
        if(empty($this->errorArray)){
            $query=$this->con->prepare("UPDATE users SET password=:pw WHERE username=:un");
            
            $pw = hash("sha512",$pw);
            $query->bindValue("pw",$pw);
            $query->bindValue("un",$un);

            return $query->execute();
            
        }      
        return false;
    }
    public function validateOldPassword($oldPw,$un){

        $pw = hash("sha512",$oldPw);
        $query= $this->con->prepare("SELECT * FROM users WHERE username=:un AND password=:pw");
        $query->bindValue(":un",$un);
        $query->bindValue(":pw",$pw);
        $query->execute(); 
        if($query->rowCount()==0){
            array_push($this->errorAray, Constants::$PasswordIncorrect);
        }
    }
}

?>