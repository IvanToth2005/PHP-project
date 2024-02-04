<!DOCTYPE html>
<html lang="hu">
<head>
<meta charset="UTF-8">
<title>Településlista</title>
</head>
<body>

<?php
    include 'csvBeolvas.php';
    // Megye kiválasztó létrehozása
    echo '<form method="get">';
    echo '<select name="megye">';
    echo '<option value="">Válassz egy megyét: </option>';
    asort($telepulesek);

    foreach (array_keys($telepulesek) as $megye) {
        if ($megye !== 'county') { 
            echo '<option value="' . $megye . '">' . $megye . '</option>';
        }
    }
    echo '</select>';
    echo '<input type="submit" value="Listáz">';
    echo '</form>';

    // Include a kiirat.php fájlba
    include 'kiirat.php';
?>
</body>
</html>