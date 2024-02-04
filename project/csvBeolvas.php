<?php
    // Adatok betöltése a CSV fájlból és feldolgozása
    $csvFile = fopen('zip_codes.csv', 'r');
    $telepulesek = [];

    // A fejléc eltávolítása
    fgetcsv($csvFile);

    while (($row = fgetcsv($csvFile)) !== false) {
        // Ellenőrizzük, hogy a sor tartalmazza-e az elvárt számú mezőt (3 mező: megye, irányítószám, település)
        if (count($row) >= 3) {
            $megye = $row[0]; // Megye neve a 0. indexen
            $iranyitoszam = $row[1]; // Irányítószám a 1. indexen
            $telepules = $row[2]; // Település neve a 2. indexen
            
            // Ha a megye üres, akkor 'Budapest' értéket állítunk be
            if (empty($megye)) {
                $megye = 'Budapest';
            }
            
            // Megye alapján tároljuk az adatokat
            if (!isset($telepulesek[$megye])) {
                $telepulesek[$megye] = [];
            }
            
            // Az irányítószámot kivágjuk, csak a település nevét tároljuk el
            $telepulesek[$megye][] = $telepules;
        }
    }
    fclose($csvFile);

