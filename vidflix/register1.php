<?php
require_once("includes/config.php");
require_once("includes/classes/FormSanitizer.php");
require_once("includes/classes/constants.php");
require_once("includes/classes/account.php");

    $account = new account($con);

    if(isset($_POST["submitbutton"])){
        $firstName=  FormSanitizer::sanitizeFormString($_POST["FirstName"]);
        $lastName=  FormSanitizer::sanitizeFormString($_POST["LastName"]);
        $username=  FormSanitizer::sanitizeFormUsername($_POST["Username"]);
        $Email=  FormSanitizer::sanitizeFormEmail($_POST["Email"]);
        $Password=  FormSanitizer::sanitizeFormPassword($_POST["Password"]);

       $success= $account->register($firstName,$lastName,$username,$Email,$Password,);
    
       if($success){
        $_SESSION["userLoggedIn"]=$username;
        header("Location:index1.php");
       }
    }
    function getInputValue($name){
        if(isset($_POST[$name])){
            echo $_POST[$name];
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Welcome to The Origin</title>
        <link rel="stylesheet" type="text/css" href="assets/style/style.css" />   
    </head>
    <body>
     
    <div class="signInContainer">

        <div class="column">
            <div class="header">
                <img src="assets/images/origin.png" title="logo" alt="site logo"/>
                <h3>Sign Up </h3>
                <span>to continue to The Origin </span>
            </div>
            <form method="POST">

                <?php echo $account->getError(Constants::$firstNameCharacter);?>
               
                <input type="text" name="FirstName" placeholder="First name" value="<?php getInputValue("firstName");?>" required>
               
                <?php echo $account->getError(Constants::$LastNameCharacter);?>

                <input type="text" name="LastName" placeholder="Last name" value="<?php getInputValue("lastName");?>" required>
               
                <?php echo $account->getError(Constants::$UsernameCharacter);?>
                <?php echo $account->getError(Constants::$UsernameTaken);?>

                <input type="text" name="Username" placeholder="Username" value="<?php getInputValue("username");?>" required>
               
                <?php echo $account->getError(Constants::$EmailInvalid);?>
                <?php echo $account->getError(Constants::$EmailTaken);?>
               
                <input type="text" name="Email" placeholder="Email" value="<?php getInputValue("email");?>" required>
                
                <?php echo $account->getError(Constants::$Passwordlength);?>
               
                <input type="text" name="Password"placeholder="Password"  required>
               
                <input type="submit" name="submitbutton"placeholder="SUBMIT">
            </form>
            <a href="login.php" class="signInmessage">Already have an account? Sign in here! </a>
        </div>

    </div>

    </body>
</html>