<?php
// Adatbázis kapcsolat létrehozása
$con = mysqli_connect("localhost", "root", "", "zip_codes");
if (!$con) {
    die("Connection Error");
}

// Ellenőrizzük, hogy a POST kéréssel érkezett-e a szükséges adatok
if (isset($_POST['county']) && isset($_POST['cityName'])) {
    $selectedCounty = $_POST['county'];
    $cityName = $_POST['cityName'];

    // Település hozzáadása az adatbázishoz
    $query = "INSERT INTO zip_codes (County, City) VALUES ('$selectedCounty', '$cityName')";
    $result = mysqli_query($con, $query);

    if ($result) {
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Hiba a település hozzáadása közben.'));
    }
} else {
    echo json_encode(array('success' => false, 'message' => 'Hiányzó adatok.'));
}
