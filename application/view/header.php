<!DOCTYPE html>

<html>
 <head>
     <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
     <link rel="stylesheet" type="text/css" href="<?php echo WEBROOT; ?>/css/StyleSheet.css">
     <link rel="stylesheet" type="text/css" href="<?php echo WEBROOT; ?>/css/popup.css">
     <title>De Survivalrun Audit log</title>
 </head>

 <body>
    <div id="main">
        <div id="header">
        <?php
            $userid = $_SESSION['user_id'];
            //get username
            $userName = $this->mod_header->getUserName($userid, $this->mysqli);

            //get aantal openstaande berichten voor deze gebruiker
            $aantal = $this->mod_header->getNrOpenMessages($userid, $this->db);

            //bij groter dan 0
            //haal oudste bericht op
            if($aantal>0){
                $message = $this->mod_header->getOldestMessage($userid, $this->db);
                $messageid = $message['Messageid'];
            }
            
            $db = $this->db;
            //enable knop
            //bij drukken op knop
            //bericht wegschrijven als gelezen
            function addMessageRead($userid, $messageid, $db){                
                $today = date("Y-m-d");
                $query = "INSERT INTO TblMessagesRead (Message_id, User_id, Datum) VALUES
                    ('$messageid','$userid','$today' )";
                $STH = $db->prepare($query);
                $STH->execute();
                //refresh current page
                //$host = $_SERVER['HTTP_HOST'];
                //$url = $_SERVER['REQUEST_URI'];
                //echo "<meta http-equiv=\"refresh\" content=\"0;URL=".$url."\">";
            }
            
            
        ?>
            <div id="headerleft">
                <img align ="left" src="<?php echo WEBROOT; ?>/img/logo.gif" alt="" width="152" height="50">
            </div>
            <div id="headermid">
                <?php
                    if(1==1){
                        if($this->url[0] <> 'Beheer' && $this->url[0] <> 'Login'  && $this->url[0] <> 'Logboeken'){
                ?>
                    Terrein: 
                    <a><b>
                        <?php echo $_SESSION['Terreinnaam'];?>
                        </b>
                    </a>
                    <br>
                        <?php } ?>
            </div>
            

            <div id="headerright">
                <div class="cudWidget">
                	<a href="<?php echo WEBROOT; ?>/docs/help.html">
                       <img src="<?php echo WEBROOT; ?>/img/information.png" width="40" height="40"> 
                    </a>
                </div>
                
                <!-- show messages read button for all users except beheerder  -->
                <?php
                if ($userName!= 'beheerder@eland.nl' &&
                    $userName!= 'beheerder@beheer.nl'){
                ?>
                    <div class="cudWidget">
                    <!-- laat juiste afbeelding zien met aantal niet gelezen berichten -->
                    <?php
                        switch (true){
                            case ($aantal==1):
                                echo '<a href="#popup">
                                       <img src="'.WEBROOT.'/img/messages1.png" width="40" height="40">
                                     </a> '; 
                               break;
                            case ($aantal==2):
                                echo '<a href="#popup">
                                       <img src="'.WEBROOT.'/img/messages2.png" width="40" height="40">
                                     </a> '; 
                                break;
                            case ($aantal>2):
                                echo '<a href="#popup">
                                       <img src="'.WEBROOT.'/img/messages3.png" width="40" height="40"> 
                                     </a> '; 
                                break;
                            default:
                                echo '<img src="'.WEBROOT.'/img/messages0.png" alt="bericht" 
                               width="40" height="40">';
                        }
                    ?>	
                    </div>              
                <?php 
                }
                ?>

                <div class="cudWidget dropdown">
                   <a><img src="<?php echo WEBROOT; ?>/img/user.png" width="40" height="40"> </a>
                   <div class="account-dropbox">
                      <a>Ingelogd: <?php echo $userName; ?></a>
                      <a href="<?php echo WEBROOT; ?>/Logout">
                        <?php
                        //uiloggen onderdeel van php vanwege controle op ingelogd zijn
                        echo "Uitloggen";
                        }
                        ?>
                      </a>
                   </div>
                </div>
                
            </div>
        </div>
    </div>
    
    
    <!-- modal dialogue -->
	<div id="popup" class="overlay">
		<div class="popup">
			<header class="container popup_header">
			   <a class="close" href="">&times;</a>
			   <h2>Nieuwsberichten</h2>
			</header>

			<div class="container popup_body">
			   <a style="float:right;">
			     <?php echo $message['Datum']; ?>
			   </a>
			   <h3><?php echo $message['Titel']; ?></h3>
			   <p> <?php echo $message['Bericht']; ?></p>
			</div>

			<footer class="container popup_footer">
               <form name="form1" method="post" 
                     action="<?php $url = isset($_GET['url']) ? $_GET['url'] : NULL;
                                   echo WEBROOT.'/'.$url;
                            ?>">
	              <button class="btnRead" type="submit" name="MessageRead" >
                  Gelezen
                  </button>
                  <?php
                    // Check if read button is clicked => start function
                    if(isset($_POST['MessageRead'])){
                       addMessageRead($userid, $messageid, $db);
                    }
                  ?>
		        </form>
            </footer>
		</div>
    </div>
    
    
 </body>
</html> 