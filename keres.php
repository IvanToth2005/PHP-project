<?php

// Adatbázis kapcsolódás
$conn = new PDO("mysql:host=localhost;dbname=zip_codes", "root", "");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$searchText = $_POST['searchText'];
$response = [];

// Ellenőrzés, hogy a keresett szöveg szám-e vagy szöveg
if (is_numeric($searchText)) {
    // Irányítószám keresése a zipcodes táblában
    $stmt = $conn->prepare("SELECT zip_code FROM zipcode WHERE city_zip = ?");
    $stmt->execute([$searchText]);
    $city = $stmt->fetchColumn();
} else {
    // Városnév keresése a cities táblában
    $stmt = $conn->prepare("SELECT city_name FROM Cities WHERE city_name = ?");
    $stmt->execute([$searchText]);
    $city = $stmt->fetchColumn();
}

// Ellenőrzés, hogy van-e város a megadott keresés alapján
if ($city) {
    $response['city'] = $city;
} else {
    $response['error'] = 'Nincs ilyen város az adatbázisban.';
}

// JSON válasz elküldése
header('Content-Type: application/json');
echo json_encode($response);
