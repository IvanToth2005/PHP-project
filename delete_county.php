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

// Adatok fogadása a POST kérésből
$countyName = $_POST['countyName'] ?? '';

// Ellenőrzés, hogy a POST kérés tartalmazza-e a szükséges adatokat
if (!$countyName) {
    $response = array("success" => false, "message" => "Hiányzó adat: megye név.");
    echo json_encode($response);
    exit();
}

try {
    // Megye törlése az adatbázisból
    $stmt = $conn->prepare("DELETE FROM counties WHERE county_name = :countyName");
    $stmt->bindParam(':countyName', $countyName);
    $stmt->execute();

    // Ellenőrizzük, hogy történt-e törlés
    $rowCount = $stmt->rowCount();
    if ($rowCount > 0) {
        $response = array("message" => "Sikeresen eltávolítva.");
        echo json_encode($response);
    } else {
        $response = array("message" => "Nem található ilyen vármegye az adatbázisban.");
        echo json_encode($response);
    }
} catch(PDOException $e) {
    // Hibakezelés
    $response = array("message" => "Hiba történt a vármegye törlése közben: " . $e->getMessage());
    echo json_encode($response);
}
?>