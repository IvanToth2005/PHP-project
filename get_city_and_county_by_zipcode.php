<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Adatbázis kapcsolódás
    $conn = new PDO("mysql:host=localhost;dbname=zip_codes", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Irányítószám lekérdezése
    $zipCode = $_POST["zipCode"];
    $stmt = $conn->prepare("SELECT city_name, county_name FROM Cities
                            INNER JOIN Counties ON Cities.county_id = Counties.county_id 
                            WHERE Cities.zip_code_id IN (SELECT zip_code_id FROM zipcodes WHERE zip_code = :zipCode)");
    $stmt->bindParam(':zipCode', $zipCode, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        echo json_encode(["cityName" => $result['city_name'], "countyName" => $result['county_name']]);
    } else {
        echo json_encode(["error" => "Nincs találat az adott irányítószámhoz."]);
    }
}
?>
