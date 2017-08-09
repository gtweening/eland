<?php

/**
maintenance login

copyright: 2013 Gerko Weening
*/

include_once "inc/base.php";
include_once "inc/functions.php";

sec_session_start();
include_once "common/header.php"; 

//secure login 
if (login_check($mysqli) == true) {
    $logged = 'in';
} else {
    $logged = 'uit';
}
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="css/StyleSheet.css">
        <script type="text/JavaScript" src="js/sha512.js"></script> 
        <script type="text/JavaScript" src="js/forms.js"></script> 
    </head>
    <body>
        <?php
        if (isset($_GET['error'])) {
            echo '<p class="error">Uw inloggegevens kloppen niet, probeer het nog eens!</p>';
        }
        ?> 
        <div id="main">
        <div id="LeftColumn">
        </div>
        <div id="RightColumn">
        <div id="RColmain">
            <form action="inc/process_login_beheerder.php" method="post" name="login_form">
            <table id="materialenTable">
                <tr class="theader">
                    <th width="5%" ></th>
                    <th ><strong>Inloggen</strong></th>
                </tr>    
                <tr><td>e-mail adres</td><td><input type="text" name="email" size="35"></td></tr>
                <tr><td>Wachtwoord</td><td><input type="password" name="passwoord" id="password" size="35"></td></tr>
                <tr><td></td><td><input type="button" 
                                        value="Inloggen" 
                                        onclick="formhash(this.form, this.form.password);"></td></tr>
                <tr><td colspan="2">U bent momenteel <?php echo $logged ?>gelogd.</td></tr>
            </table>
            </form>
        </div>
        </div>
        </div>
        <div id="footer">
          <?php include "common/footer.php"; ?>
        </div>
    </body>
</html>
