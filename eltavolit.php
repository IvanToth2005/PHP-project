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

// Ellenőrizzük, hogy a település neve érkezik-e a POST kéréssel
if (isset($_POST['cityName'])) {
    $cityName = $_POST['cityName'];

    // Település eltávolítása az adatbázisból
    $stmt = $conn->prepare("DELETE FROM Cities WHERE city_name = :city_name");
    $stmt->bindParam(':city_name', $cityName);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Nem található település a megadott névvel az adatbázisban.'));
    }
}
?>
