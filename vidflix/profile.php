<?php
require_once("includes/header.php");
require_once("includes/classes/account.php");
require_once("includes/classes/FormSanitizer.php");
require_once("includes/classes/Constants.php");

$detailsMessage="";
$PasswordMessage="";

if(isset($_POST["saveDetails"])){
    $account=new account($con);
    $firstName=FormSanitizer::sanitizeFormString($_POST["firstName"]);
    $lastName=FormSanitizer::sanitizeFormString($_POST["lastName"]);
    $email=FormSanitizer::sanitizeFormEmail($_POST["email"]);

    if($account->updateDetails($firstName,$lastName,$email,$userLoggedIn)){
        $detailsMessage="<div class='alertSuccess'>
                    Details updated successfully!
                    </div>";
    }
    else{
      $errorMessage=$account->getFirstError();
      $detailsMessage="<div class='alertSuccess'>
      $errorMessage
      </div>";

    }
}

if(isset($_POST["savePassword"])){
    $account=new account($con);
    $oldPassword=FormSanitizer::sanitizeFormPassword($_POST["oldPassword"]);
    $newPassword=FormSanitizer::sanitizeFormPassword($_POST["newPassword"]);
    $newPassword2=FormSanitizer::sanitizeFormPassword($_POST["newPassword2"]);

    if($account->updatePassword($oldPassword,$newPassword,$newPassword2,$userLoggedIn)){
        $PasswordMessage="<div class='alertSuccess'>
                    Password updated successfully!
                    </div>";
    }
    else{
      $errorMessage=$account->getFirstError();
      $PasswordMessage="<div class='alertSuccess'>
      $errorMessage
      </div>";

    }
}
?>
<div class="settingContainer column">
    <div class="formSection">
        <form method="POST">
            <h2>User details</h2>
            
            <?php
            $user=new User($con,$userLoggedIn);
            $firstName=isset($_POST["firstName"])? $_POST["firstName"] : $user->getFirstName();
            $lastName=isset($_POST["lastName"])? $_POST["lastName"] : $user->getLastName();
            $email=isset($_POST["email"])? $_POST["email"] : $user->getEmail();
            
            ?>

            <input type="text" name="firstName" placeholder="First name" value="<?php echo $firstName;?>">
            <input type="text" name="lastName" placeholder="Last name"  value="<?php echo $lastName;?>">
            <input type="email" name="email" placeholder="Email"  value="<?php echo $email;?>">
            
            <div class="message">
                <?php echo $detailsMessage;?>
                </div>
                <input type="submit" name="saveDetails" value="Save">
        </form>
    </div>

    <div class="formSection">
        <form method="POST">
            <h2>Update password</h2>
            <input type="password" name="oldPassword" placeholder="Old password">
            <input type="password" name="newPassword" placeholder="New password">
            <input type="password" name="newPassword2" placeholder="Confirm password">
      
            <div class="message">
                <?php echo $PasswordMessage;?>
                </div>
      
            <input type="submit" name="savePassword" value="Save">
        </form>
    </div>

    <div class="formSection">
        <h2>Subscription</h2>
        <?php
        if($user->getIsSubscribed()){
            echo "<h3>You are subscribed! Go to PayPal to cancel</h3>";
        }
        else {
            echo"<a href='billing.php'>Subscribe to The Origin</a>";
        }
        ?>
    </div>

</div>