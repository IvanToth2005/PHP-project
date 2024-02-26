<?php
// Adatbázis kapcsolat létrehozása
$con = mysqli_connect("localhost", "root", "", "zip_codes");
if (!$con) {
    die("Connection Error");
}

// Lekérdezés végrehajtása és válasz generálása
$query = $_POST['query'];
$result = mysqli_query($con, $query);

$cities = array();
while ($row = mysqli_fetch_assoc($result)) {
    $cities[] = $row['city_name']; // Módosítás: városok lekérdezése a 'city_name' oszlopból
}

echo json_encode($cities);
mysqli_close($con);
?>
