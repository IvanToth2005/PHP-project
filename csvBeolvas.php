<?php
// Adatbázis kapcsolat létrehozása PDO-val
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "zip_codes";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Hiba: " . $e->getMessage();
}

// Ha a kiválasztott megye értéke érkezik a POST kéréssel
if (isset($_POST['county'])) {
    $selectedCounty = $_POST['county'];
    
    // Megye településeinek lekérdezése az adatbázisból
    $stmt = $conn->prepare("SELECT zipcodes.zip_code, cities.city_name 
                            FROM cities 
                            INNER JOIN zipcodes ON zipcodes.zip_code_id = cities.zip_code_id 
                            WHERE cities.county_id IN (SELECT county_id FROM counties WHERE county_name = :county_name)");
    $stmt->bindParam(':county_name', $selectedCounty);
    $stmt->execute();
    $cities = $stmt->fetchAll(PDO::FETCH_ASSOC); // FETCH_ASSOC módosítás hozzáadása
    
    // Megye címéről elérési út lekérdezése
    $countyImage = "pictures/{$selectedCounty}.jpg"; // feltételezve, hogy a címerek JPG formátumban vannak a pictures mappában
    
    // Adatok és megye címerének JSON formátumba alakítása és visszaküldése
    echo json_encode(array('cities' => $cities, 'countyImage' => $countyImage));
}

if (isset($_POST['county']) && isset($_POST['cityName'])) {
    $selectedCounty = $_POST['county'];
    $cityName = $_POST['cityName'];
    
    // Település hozzáadása az adatbázishoz
    $stmt = $conn->prepare("INSERT INTO cities (city_name, county_id) VALUES (:city_name, (SELECT county_id FROM counties WHERE county_name = :county_name))");
    $stmt->bindParam(':city_name', $cityName);
    $stmt->bindParam(':county_name', $selectedCounty);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Hiba a település hozzáadása közben.'));
    }
}
?>
