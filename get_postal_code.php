
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Adatbázis kapcsolódás
    $conn = new PDO("mysql:host=localhost;dbname=zip_codes", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Város nevének lekérése
    $cityName = $_POST["cityName"];

    // Irányítószám lekérdezése
    $stmt = $conn->prepare("SELECT zip_code FROM zipcodes WHERE zip_code_id IN (SELECT zip_code_id FROM cities WHERE city_name = :cityName)");
    $stmt->bindParam(':cityName', $cityName, PDO::PARAM_STR);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $postalCode = ($result !== false) ? $result['zip_code'] : 'N/A';

    // Eredmény JSON formátumban küldése
    echo json_encode(["postalCode" => $postalCode]);
}
?>
