<?php
// Kapcsolódás az adatbázishoz
try {
    $conn = new PDO("mysql:host=localhost;dbname=zip_codes", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // A POST kérésből kinyerjük a vármegye nevét
    $countyName = $_POST['countyName'];

    // Beszúrjuk az új vármegyét az adatbázisba
    $stmt = $conn->prepare("INSERT INTO Counties (county_name) VALUES (:countyName)");
    $stmt->bindParam(':countyName', $countyName);
    $stmt->execute();

    // Sikeres válasz küldése
    echo "Sikeresen hozzáadva: " . $countyName;
} catch(PDOException $e) {
    // Hibás válasz küldése, ha hiba történt
    echo "Hiba történt: " . $e->getMessage();
}
