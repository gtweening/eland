<!DOCTYPE html>

<html>
 <head>
     <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
     <link rel="stylesheet" type="text/css" href="../css/StyleSheet.css">
     <link rel="stylesheet" type="text/css" href="../css/popup.css">
     <title>De Survivalrun Audit log</title>
 </head>

 <body>
    <div id="main">
        <div id="header">
        <?php
            include_once "../inc/base.php";

            //bepaal user_id
            $userid = $_SESSION['user_id'];

            //Get terreinnaam
            $qry2 = "Select tt.Terreinnaam 
            from TblTerreinUsers ttu, TblTerrein tt 
            where ttu.Terrein_id = tt.Id and
                    ttu.User_id = '$userid'
            ";
            $stmt2 = $mysqli->prepare($qry2);
            $stmt2->execute();    // Execute the prepared query.
            $stmt2->store_result();
            // get variables from result.
            $stmt2->bind_result($Terreinnaam);
            $stmt2->fetch();

            //get aantal openstaande berichten voor deze gebruiker
            $sqlaantal = "select count(tm.Id) as aantal 
                          from TblMessages tm 
                          where tm.Id not in (select distinct Message_id from TblMessagesRead 
                                              where User_id = ".$userid.") 
                                and tm.Gepubliceerd = 1;";
            $STH = $db->query($sqlaantal);
            $STH->setFetchMode(PDO::FETCH_ASSOC);
            $row=$STH->fetch();
            $aantal=$row['aantal'];
            
            //bij groter dan 0
            //haal oudste bericht op
            $sqloudste="select * 
                        from TblMessages tm 
                        where tm.Id not in (select Message_id from TblMessagesRead 
                                            where User_id = ".$userid.") 
                              and tm.Gepubliceerd = 1 
                        order by tm.Datum asc 
                        LIMIT 1;";
            $STH = $db->query($sqloudste);
            $STH->setFetchMode(PDO::FETCH_ASSOC);
            $row=$STH->fetch();
            $titel=$row['Titel'];
            $datum=$row['Datum'];
            $bericht=$row['Bericht'];
            $messageid=$row['Id'];
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
                <img align ="left" src="../img/logo.gif" alt="" width="152" height="50">
            </div>
            <div id="headermid">
                <?php
                    if (isset($_SESSION['username'])){
                ?>
                    Terrein: 
                    <a><b>
                        <?php echo $Terreinnaam;?>
                    </b></a>
                <br>
            </div>
            <div id="headerright">
                <div class="cudWidget">
                	<a href="../docs/help.html">
                       <img src="../img/information.png" width="40" height="40"> 
                    </a>
                </div>
                
                <!-- show messages read button for all users except beheerder  -->
                <?php
                if ($_SESSION['username']!= 'beheerder@eland.nl' &&
                    $_SESSION['username']!= 'beheerder@beheer.nl'){
                ?>
                    <div class="cudWidget">
                    <!-- laat juiste afbeelding zien met aantal niet gelezen berichten -->
                    <?php
                        switch (true){
                            case ($aantal==1):
                                echo '<a href="#popup">
                                       <img src="../img/messages1.png" width="40" height="40">
                                     </a> '; 
                               break;
                            case ($aantal==2):
                                echo '<a href="#popup">
                                       <img src="../img/messages2.png" width="40" height="40">
                                     </a> '; 
                                break;
                            case ($aantal>2):
                                echo '<a href="#popup">
                                       <img src="../img/messages3.png" width="40" height="40"> 
                                     </a> '; 
                                break;
                            default:
                                echo '<img src="../img/messages0.png" alt="bericht" 
                               width="40" height="40">';
                        }
                    ?>	
                    </div>              
                <?php 
                }
                ?>

                <div class="cudWidget dropdown">
                   <a><img src="../img/user.png" width="40" height="40"> </a>
                   <div class="account-dropbox">
                      <a>Ingelogd: <?php echo $_SESSION['username']; ?></a>
                      <a href="../inc/logout.php">
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
			   <a class="close display-topright" href="">&times;</a>
			   <h2>Nieuwsberichten</h2>
			</header>
			<div class="container">
			   <a style="float:right;">
			     <?php echo $datum; ?>
			   </a>
			   <h3><?php echo $titel; ?></h3>
			   <p> <?php echo $bericht; ?></p>
			</div>
			<footer class="container popup_footer">
               <form name="form1" method="post" 
		             action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
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