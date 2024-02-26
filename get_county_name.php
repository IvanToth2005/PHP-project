
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Adatbázis kapcsolódás
    $conn = new PDO("mysql:host=localhost;dbname=zip_codes", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Város nevének lekérése
    $cityName = $_POST["cityName"];

    // Megye nevének lekérdezése
    $stmt = $conn->prepare("SELECT county_name FROM Counties WHERE county_id IN (SELECT county_id FROM Cities WHERE city_name = :cityName)");
    $stmt->bindParam(':cityName', $cityName, PDO::PARAM_STR);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $countyName = ($result !== false) ? $result['county_name'] : 'N/A';

    // Eredmény JSON formátumban küldése
    echo json_encode(["countyName" => $countyName]);
}
?>
