<?php

$con = mysqli_connect("localhost", "root", "", "zip_codes");
if (!$con) { 
    die("Connection Error"); 
}

if (isset($_POST['search'])) {
    $searchTerm = $_POST['search'];
    
    // Keresés az adatbázisban irányítószámra vagy helység nevére
    $query = "SELECT City, Zip_Code, County FROM zip_codes WHERE Zip_Code LIKE '%$searchTerm%' OR City LIKE '%$searchTerm%'";
    $result = mysqli_query($con, $query);
    
    $data = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    
    echo json_encode($data);
}
?>