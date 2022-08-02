<?php
   require_once("includes/config.php");
   require_once("includes/classes/FormSanitizer.php");
   require_once("includes/classes/constants.php");
   require_once("includes/classes/account.php");
   
   $account = new account($con);

   if(isset($_POST["submitbutton"])){
        
   
        $username=  FormSanitizer::sanitizeFormUsername($_POST["Username"]);
        $Password=  FormSanitizer::sanitizeFormPassword($_POST["Password"]);

       $success= $account->login($username,$Password,);
    
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
                <h3>Sign In </h3>
                <span>to continue to The Origin </span>
            </div>
            <form method="POST">
            <?php echo $account->getError(Constants::$loginFailed);?>

                <input type="text" name="Username" placeholder="Username" value="<?php getInputValue("username");?>" required>
                <input type="text" name="Password"placeholder="Password" required>
                <input type="submit" name="submitbutton"placeholder="SUBMIT">
            </form>
            <a href="register1.php" class="signInmessage">Need an account? Sign up here! </a>
        </div>

    </div>

    </body>
</html>