<?php

/**
The collection of obstacles are devided into section.
Using this page you can maintain Sections.

copyright: 2013-2017 Gerko Weening

*/

include_once "../inc/base.php";
include_once "../inc/functions.php";
include_once "../inc/queries.php";

sec_session_start(); 
include_once "../common/header.php"; 

//secure login
if(login_check($mysqli) == true) { 

include_once "../common/leftColumn.php";
$terreinId = $_SESSION['Terreinid']; //sessions terreinid
$tbl_name="TblSections"; // Table name

$savedAt = "instellingen niet opgeslagen.";
$terreinNaam = getTerreinnaam($db,$terreinId);
$contactEma = getUserEmail($db,$terreinId);

if($_SERVER["REQUEST_METHOD"]=="POST"){
  $terreinId = $_POST["terreinId"];
  $terreinNaam = $_POST["terreinNaam"];
  $contactEma = $_POST["contactEma"];
  // Assume an entry in the DB table exists for this user.                        
  
  $sql = "UPDATE TblTerrein SET Terreinnaam = '".$terreinNaam."' ";    
  $sql.= "WHERE Id = $terreinId";  
                         
  $STH = $db->prepare($sql);
  $STH->execute();

  $sql = "Select User_id from TblTerreinUsers ";   
  $sql.= "WHERE Terrein_id = $terreinId";
  $STH = $db->prepare($sql);
  $STH->execute();
  $row = $STH->fetch();
  $id = htmlentities($row['User_id']);

  $sql = "UPDATE TblUsers SET ema = '".$contactEma."' ";    
  $sql.= "WHERE Id = $id";  
                         
  $STH = $db->prepare($sql);
  $STH->execute();

  exit;                                                   
}

//close connection
$db = null;

?>

<html>
<head>
  <script type="text/javascript">
    function init(){
      setSavedAt('aan het opslaan...');
      window.setTimeout(autoSave,3000);
    }

    function autoSave(){
            
      var terreinNaam = document.getElementById("terreinNaam").value;
      var terreinId = document.getElementById("terreinId").value;
      var contactEma = document.getElementById("contactEma").value;

      var params = "terreinNaam="+terreinNaam;
      params += "&terreinId="+terreinId;
      params += "&contactEma="+contactEma;

      var http = getHTTPObject();
      http.onreadystatechange = function(){
          if(http.readyState==4){
            if(http.status==200){
              //alert(http.responseText);
            }else{
              msg = document.getElementById("msg");
              msg.innerHTML = "<span onclick='this.style.display=\"none\";'>"+http.responseText+" (<u>close</u>)</span>";
            }
          }
      };
      http.open("POST", window.location.href, true);
      http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      http.setRequestHeader("Content-length", params.length);
      http.setRequestHeader("Connection", "close");
      http.send(params);

      //html element bijwerken
      //var d = new Date();
      //var hh = addZero(d.getHours());
      //var mm = addZero(d.getMinutes());
      //var ss = addZero(d.getSeconds());
      //var time = hh+':'+mm+':'+ss
      var str = "Instellingen zijn opgeslagen.";
      setSavedAt(str);
    }

    //cross-browser xmlHTTP getter
    function getHTTPObject() { 
      var xmlhttp; 

      if (!xmlhttp && typeof XMLHttpRequest != 'undefined') { 
        try {   
            xmlhttp = new XMLHttpRequest(); 
        } catch (e) { 
            xmlhttp = false; 
        } 
      } 
      
      return xmlhttp;
    }

    function setSavedAt(str){
      var s= document.getElementById("savedAt");
      s.innerText = str;
    }

    function addZero(i) {
      if (i < 10) {
        i = "0" + i;
      }
      return i;
    }
  </script>
</head>

<body id="sections" oninput="init();">
  <div id="LeftColumn2">      
  </div>

  <div id="RightColumn">
    <div class="theader">
      <a id="savedAt">
        Instellingen zijn nog niet veranderd.
      </a>
    </div>
    <form method="POST">
      <a class="tableTitle">&nbsp;&nbsp;&nbsp;&nbsp;Instellingen</a>
      <input type="hidden" id="terreinId" name="terreinId" value="<?php echo $terreinId;?>">
      <div class="white">
        Terreinnaam
        <input type="text" class="inputText" id="terreinNaam" name="terreinNaam"
              maxlength="75" size="50" value ="<?php echo $terreinNaam?>">
      </div>
      <div class="white">
        Contact email adres
        <input type="text" class="inputText" id="contactEma" name="contactEma"
              maxlength="75" size="50" value ="<?php echo $contactEma?>">
      </div>
    </form>
  </div>

</body>

</html>
<?php
} else { ?>
<br>
U bent niet geautoriseerd voor toegang tot deze pagina. <a href="../index.php">Inloggen</a> alstublieft.
<?php
}
?>


