<?php

    /*
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

    */


    if (isset($_GET['megye'])) {
        $valasztottMegye = $_GET['megye'];
        
        // Ellenőrizzük, hogy van-e ilyen megye és vannak-e települések
        if (isset($telepulesek[$valasztottMegye])) {
            
            // Ellenőrizzük, hogy van-e kép a megyéhez
            $kepUtvonal = 'pictures/' . strtolower($valasztottMegye) . '.jpg';
            if (file_exists($kepUtvonal)) {
                echo '<h2>' . $valasztottMegye . ' megye települései:</h2>'; 
                // Kép megjelenítése
                echo '<img src="' . $kepUtvonal . '" alt="' . $valasztottMegye . '" class = "picture">';          
                echo '<div class="telepulesek">';
                
                // Az irányítószámok, települések és képek egy ciklusban
                foreach ($iranyitoszamok[$valasztottMegye] as $key => $iranyitoszam){
                    echo '<div class="telepules">';
                    
                    
                    
                    // Irányítószám és település megjelenítése
                    echo $iranyitoszam . ' - ' . $telepulesek[$valasztottMegye][$key];
                    
                    echo '</div>';
                }
                
                echo '</div>';
            } else {
                echo 'A kiválasztott megyéhez nincs rendelkezésre álló kép.';
            }
            
        } else {
            echo 'Nincsenek települések a kiválasztott megyében.';
        }
    }
?>