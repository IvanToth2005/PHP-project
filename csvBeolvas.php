<?php

// Adatbázis kapcsolat létrehozása
$con = mysqli_connect("localhost", "root", "", "zip_codes");
if (!$con) { 
    die("Connection Error"); 
}

// Ha a kiválasztott megye értéke érkezik a POST kéréssel
if (isset($_POST['county'])) {
    $selectedCounty = $_POST['county'];
    
    // Megye településeinek lekérdezése az adatbázisból
    $query = "SELECT city_name FROM cities WHERE County = '$selectedCounty'";
    $result = mysqli_query($con, $query);
    
    // Adatok összegyűjtése tömbbe
    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row['City'];
    }
    
    // Megye címéről elérési út lekérdezése
    $countyImage = "pictures/{$selectedCounty}.jpg"; // feltételezve, hogy a címerek JPG formátumban vannak a pictures mappában
    
    // Adatok és megye címerének JSON formátumba alakítása és visszaküldése
    echo json_encode(array('cities' => $data, 'countyImage' => $countyImage));
}

// Ha a kiválasztott megye és település értéke érkezik a POST kéréssel
if (isset($_POST['county']) && isset($_POST['cityName'])) {
    $selectedCounty = $_POST['county'];
    $cityName = $_POST['cityName'];
    
    // Település hozzáadása az adatbázishoz

    //ez nem fog működni, mivel ez még a régi adatbázishoz volt jó
    $query = "INSERT INTO zip_codes (County, City) VALUES ('$selectedCounty', '$cityName')";
    $result = mysqli_query($con, $query);
    



    if ($result) {
        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Hiba a település hozzáadása közben.'));
    }
}

// Adatbázis kapcsolat bezárása
mysqli_close($con);
?>
