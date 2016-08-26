<!DOCTYPE html>

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" type="text/css" href="../css/StyleSheet.css">
        <title>The ObstacleRun Audit log</title>
    </head>
    <body>
        <div id="main">
            <div id="header">
                <img align ="left" src="../img/logo.jpg" alt="Logo" width="152" height="50">
                    <?php
						if (isset($_SESSION['username'])){
							echo "Ingelogd: ";
							echo $_SESSION['username'];
						}
					?>
					<br>
                <a href="../inc/logout.php">uitloggen</a>
            </div>
        </div>
    
    </body>
</html> 

