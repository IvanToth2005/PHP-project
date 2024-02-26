<?php
// Adatbázis kapcsolat létrehozása
try {
    $conn = new PDO("mysql:host=localhost;dbname=zip_codes", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // Hibakezelés, ha nem sikerült kapcsolódni az adatbázishoz
    $response = array("success" => false, "message" => "Adatbázis kapcsolódási hiba: " . $e->getMessage());
    echo json_encode($response);
    exit(); // Kilépés a scriptből
}

// Ellenőrizzük, hogy a POST kérés tartalmazza-e a szükséges adatokat
if(isset($_POST['county'], $_POST['cityName'], $_POST['cityZIP'])) {
    $county = $_POST['county'];
    $cityName = $_POST['cityName'];
    $cityZIP = $_POST['cityZIP'];

    try {
        // Ellenőrizzük, hogy van-e már ilyen megye az adatbázisban
        $stmt = $conn->prepare("SELECT county_id FROM counties WHERE county_name = :county");
        $stmt->bindParam(':county', $county);
        $stmt->execute();
        $countyRow = $stmt->fetch(PDO::FETCH_ASSOC);

        // Ha még nincs ilyen megye az adatbázisban, adjuk hozzá
        if (!$countyRow) {
            $stmt = $conn->prepare("INSERT INTO counties (county_name) VALUES (:county)");
            $stmt->bindParam(':county', $county);
            $stmt->execute();
            // Frissítsük a $countyRow változót, hogy tartalmazza az újonnan beszúrt megye ID-t
            $countyRow = array("county_id" => $conn->lastInsertId());
        }

        // Ha van eredmény, hozzáadjuk a várost az adatbázishoz
        if ($countyRow) {
            $countyId = $countyRow['county_id'];

            // Irányítószám hozzáadása az adatbázishoz
            $stmt = $conn->prepare("INSERT INTO zipcodes (zip_code) VALUES (:cityZIP)");
            $stmt->bindParam(':cityZIP', $cityZIP);
            $stmt->execute();

            // Város hozzáadása a cities táblához
            $stmt = $conn->prepare("INSERT INTO cities (city_name, county_id, zip_code_id) VALUES (:cityName, :countyId, LAST_INSERT_ID())");
            $stmt->bindParam(':cityName', $cityName);
            $stmt->bindParam(':countyId', $countyId);
            $stmt->execute();

            // Sikeres hozzáadás esetén ürítsük az input mezőket
            $response = array("success" => true, "message" => "A város sikeresen hozzá lett adva az adatbázishoz.");
            echo json_encode($response);
        } else {
            // Ha a megye nem található az adatbázisban, hibát jelzünk vissza
            $response = array("success" => false, "message" => "Nem található ilyen megye az adatbázisban.");
            echo json_encode($response);
        }
    } catch(PDOException $e) {
        // Hibakezelés
        $response = array("success" => false, "message" => "Hiba történt az adatbázishoz való hozzáadás közben: " . $e->getMessage());
        echo json_encode($response);
    }
} else {
    // Ha valamelyik adat hiányzik a POST kérésben, hibát jelzünk vissza
    $response = array("success" => false, "message" => "Hiányzó adatok: megye, városnév vagy irányítószám.");
    echo json_encode($response);
}
?>
