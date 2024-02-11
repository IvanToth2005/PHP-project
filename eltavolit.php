<?php
// Adatbázis kapcsolat létrehozása
$con = mysqli_connect("localhost", "root", "", "zip_codes");
if (!$con) { 
    die("Connection Error"); 
}

// Ellenőrizd, hogy érkezett-e településnév a POST kéréssel
if (isset($_POST['cityName'])) {
    $cityName = $_POST['cityName'];

    // Távolítsd el a megadott települést az adatbázisból
    $query = "DELETE FROM zip_codes WHERE City = '$cityName'";
    $result = mysqli_query($con, $query);

    // Ellenőrizd, hogy a törlés sikeres volt-e, és küldj vissza választ
    if ($result) {
        echo json_encode(array('success' => true, 'message' => 'A település sikeresen eltávolítva az adatbázisból.'));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Hiba történt a település eltávolítása közben: ' . mysqli_error($con)));
    }
} else {
    // Ha nem érkezett településnév a POST kéréssel, küldj vissza hibaüzenetet
    echo json_encode(array('success' => false, 'message' => 'Hiányzó településnév a kérésben.'));
}

// Adatbázis kapcsolat bezárása
mysqli_close($con);
?>
