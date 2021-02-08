<?php 
    include_once "header.php"; 
    include_once "leftColumn.php"; 
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
            var s = document.getElementById("savedAt");
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

<body id="settings" oninput="init();">
    <div class="navobsbysection">          
    </div>

    <div class="workarea">
        <form method="POST">
            <div class="workarea-row">
                <a class="tableTitle">&nbsp;&nbsp;&nbsp;&nbsp;Instellingen</a>
            </div>

            <div class="workarea-row">
                <div class="theader">
                    <a id="savedAt">
                        Instellingen zijn nog niet veranderd.
                    </a>
                </div>
            </div>

            <input type="hidden" id="terreinId" name="terreinId" value="<?php echo $terreinId;?>">
            <div class="white">
                Terreinnaam
                <input type="text" class="inputText" id="terreinNaam" name="terreinNaam"
                    maxlength="75" size="51" value ="<?php echo $terreinNaam?>">
            </div>
            <div class="white">
                Contact email adres
                <input type="text" class="inputText" id="contactEma" name="contactEma"
                    maxlength="75" size="44" value ="<?php echo $contactEma?>">
            </div>
           
        </form>
    </div>
  </div>

</body>

</html>