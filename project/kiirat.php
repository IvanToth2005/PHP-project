<?php
    if (isset($_GET['megye'])) {
        $valasztottMegye = $_GET['megye'];
        if (isset($telepulesek[$valasztottMegye])) {
            echo '<h2>' . $valasztottMegye . ' megye települései:</h2>';
            echo '<div class="telepulesek">';
            foreach ($telepulesek[$valasztottMegye] as $telepules) {
                echo '<div class="telepules">';
                // Ha a település string, akkor kivágjuk az irányítószámot
                if (is_string($telepules)) {
                    $telepules = explode(', ', $telepules); // Szétválasztjuk a település nevet és az irányítószámot
                    // Ellenőrizzük, hogy van-e település név
                    if (isset($telepules[1])) {
                        echo $telepules[1]; // Kiírjuk csak a település nevet
                    } else {
                        echo $telepules[0]; // Ha nincs irányítószám, akkor csak a teljes stringet írjuk ki
                    }
                } else {
                    // Ha nem string, akkor közvetlenül kiírjuk a változót
                    if (is_array($telepules)) {
                        echo implode(', ', $telepules);
                    } else {
                        echo $telepules;
                    }
                }
                echo '</div>';
            }
            echo '</div>';
        } else {
            echo 'Nincsenek települések a kiválasztott megyében.';
        }
    }
?>