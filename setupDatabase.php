<?php
$servername = "localhost";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host=$servername", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Adatbázis létrehozása
    $sql = "CREATE DATABASE IF NOT EXISTS zip_codes";
    $conn->exec($sql);
    //echo "Adatbázis létrehozva sikeresen!<br>";

    // Adatbázis kiválasztása
    $conn->exec("USE zip_codes");

    // Counties tábla létrehozása
    $sql = "CREATE TABLE IF NOT EXISTS Counties (
        county_id INT AUTO_INCREMENT PRIMARY KEY,
        county_name VARCHAR(255) NOT NULL
    )";
    $conn->exec($sql);
    //echo "Counties tábla létrehozva sikeresen!<br>";

    // ZIPCodes tábla létrehozása
    $sql = "CREATE TABLE IF NOT EXISTS ZIPCodes (
        zip_code_id INT AUTO_INCREMENT PRIMARY KEY,
        zip_code VARCHAR(10) NOT NULL
    )";
    $conn->exec($sql);
    //echo "ZIPCodes tábla létrehozva sikeresen!<br>";

    // Cities tábla létrehozása
    $sql = "CREATE TABLE IF NOT EXISTS Cities (
        city_id INT AUTO_INCREMENT PRIMARY KEY,
        city_name VARCHAR(255) NOT NULL,
        county_id INT,
        zip_code_id INT,
        FOREIGN KEY (county_id) REFERENCES Counties(county_id),
        FOREIGN KEY (zip_code_id) REFERENCES ZIPCodes(zip_code_id)
    )";
    $conn->exec($sql);
    //echo "Cities tábla létrehozva sikeresen!<br>";

    // Adatok feltöltése az adatbázisba a CSV fájlból
    $csvFile = 'zip_codes.csv'; // A CSV fájl elérési útja
    $csvData = file_get_contents($csvFile);
    $lines = explode(PHP_EOL, $csvData);
    $insertData = [];
    foreach ($lines as $line) {
        $insertData[] = explode(',', $line);
    }

    // Adatok beszúrása az adatbázisba
    foreach ($insertData as $data) {
        // Ellenőrizzük, hogy a tömb tartalmaz-e legalább három elemet
        if (count($data) >= 3) {
            $countyName = $data[0]; // Megye neve
            $zipCode = $data[1]; // Irányítószám
            $cityName = $data[2]; // Település neve

            // Megye beszúrása Counties táblába
            $stmt = $conn->prepare("INSERT INTO Counties (county_name) VALUES (:county_name)");
            $stmt->bindParam(':county_name', $countyName);
            $stmt->execute();

            // Irányítószám beszúrása ZIPCodes táblába
            $stmt = $conn->prepare("INSERT INTO ZIPCodes (zip_code) VALUES (:zip_code)");
            $stmt->bindParam(':zip_code', $zipCode);
            $stmt->execute();

            // Település beszúrása Cities táblába
            $countyId = $conn->lastInsertId(); // Megye azonosítója
            $zipCodeId = $conn->lastInsertId(); // Irányítószám azonosítója
            $stmt = $conn->prepare("INSERT INTO Cities (city_name, county_id, zip_code_id) VALUES (:city_name, :county_id, :zip_code_id)");
            $stmt->bindParam(':city_name', $cityName);
            $stmt->bindParam(':county_id', $countyId);
            $stmt->bindParam(':zip_code_id', $zipCodeId);
            $stmt->execute();
        } 
    }

    //echo "Adatok feltöltve sikeresen!<br>";
} catch(PDOException $e) {
    echo "Hiba: " . $e->getMessage();
}

$conn = null;
