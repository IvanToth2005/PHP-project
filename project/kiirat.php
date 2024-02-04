<?php
    if (isset($_GET['megye'])) {
        $valasztottMegye = $_GET['megye'];
        if (isset($telepulesek[$valasztottMegye])) {
            echo '<h2>' . $valasztottMegye . ' megye települései:</h2>';
            echo '<div class="telepulesek">';
            foreach ($telepulesek[$valasztottMegye] as $telepules) {
                echo '<div class="telepules">';
                echo $telepules; // Csak a település nevét írjuk ki
                echo '</div>';
            }
            echo '</div>';
        } else {
            echo 'Nincsenek települések a kiválasztott megyében.';
        }
    }
?>