function getObstacle(e,Id,sectionname,volgnr){
                if(!e.target){
                    // alert(e.srcElement.innerHTML);
                } else {
                    var hindomschr =  e.target.innerHTML;
                    window.location.href = "obstacle.php?Id=" + Id +"&Sec="+sectionname+"&Vnr="+volgnr;
                }
}

