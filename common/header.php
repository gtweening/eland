<!DOCTYPE html>

<html>
 <head>
     <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
     <link rel="stylesheet" type="text/css" href="../css/StyleSheet.css">
     <title>De Survivalrun Audit log</title>
 </head>

 <body>
    <div id="main">
        <div id="header">
            <div id="headerleft">
                    <img align ="left" src="../img/logo.gif" alt="" width="152" height="50">
            </div>
            <div id="headermid">
                <?php
                    if (isset($_SESSION['username'])){
                        if (isset($_SESSION['Terreinnaam'])) {
                ?>
                    Terrein: 
                    <a><b>
                        <?php echo $_SESSION['Terreinnaam'];} 
                        ?>
                    </b></a>
                <br>
            </div>
            <div id="headerright">
                Ingelogd: <?php echo $_SESSION['username']; ?>
                <br>
                <?php
                    //uiloggen onderdeel van php vanwege controle op ingelogd zijn
                    echo "<a href='../inc/logout.php'>uitloggen</a>";
                    }
                ?>				

             </div>
        </div>
    </div>
 
 </body>
</html> 