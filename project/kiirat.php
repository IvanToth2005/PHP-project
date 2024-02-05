<?php
    if (isset($_GET['megye'])) {
        $valasztottMegye = $_GET['megye'];
        if (isset($telepulesek[$valasztottMegye])) {
            echo '<h2>' . $valasztottMegye . ' megye települései:</h2>';           
            echo '<div class="telepulesek">';
            
            // Az irányítószámok és települések egy ciklusban
            foreach ($iranyitoszamok[$valasztottMegye] as $key => $iranyitoszam){
                echo '<div class="telepules">';
                echo $iranyitoszam . ' - ' . $telepulesek[$valasztottMegye][$key];
                echo '</div>';
            }
            
            echo '</div>';
        } else {
            echo 'Nincsenek települések a kiválasztott megyében.';
        }
    }
?>