<?php
// Kapcsolódás az adatbázishoz
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "zip_codes";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kapcsolódás ellenőrzése
if ($conn->connect_error) {
    die("Kapcsolódási hiba: " . $conn->connect_error);
}

// Település nevének és módosított értékek kiolvasása a POST adatokból
$cityName = $_POST['cityName'];
$modifiedCityName = $_POST['modifiedCityName'];
$modifiedZIPCode = $_POST['modifiedZIPCode'];
$modifiedCounty = $_POST['modifiedCounty'];

// SQL parancs létrehozása és futtatása a módosítások végrehajtásához
$sql = "UPDATE cities 
        JOIN zipcodes ON cities.zip_code_id = zipcodes.zip_code_id
        JOIN counties ON cities.county_id = counties.county_id
        SET cities.city_name='$modifiedCityName', zipcodes.zip_code='$modifiedZIPCode', counties.county_name='$modifiedCounty' 
        WHERE cities.city_name='$cityName'";

if ($conn->query($sql) === TRUE) {
    // Sikeres módosítás esetén JSON válasz küldése
    $response = array("success" => true, "message" => "Település sikeresen módosítva.");
    echo json_encode($response);
} else {
    // Hiba esetén JSON válasz küldése
    $response = array("success" => false, "message" => "Hiba történt a módosítás során: " . $conn->error);
    echo json_encode($response);
}

// Adatbázis kapcsolat bezárása
$conn->close();
header('Content-Type: application/json');
?>
