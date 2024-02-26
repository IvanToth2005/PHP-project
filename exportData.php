<?php
try {
    $conn = new PDO("mysql:host=localhost;dbname=zip_codes", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
    if(isset($_POST['county'])) {
        $countyName = $_POST['county'];
 
        $sql = "SELECT counties.county_name, cities.city_name, zipcodes.zip_code
                FROM counties
                INNER JOIN cities ON counties.county_id = cities.county_id
                INNER JOIN zipCodes ON cities.zip_code_id = zipcodes.zip_code_id
                WHERE counties.county_name = :countyName";
 
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':countyName', $countyName);
        $stmt->execute();
 
        if($stmt->rowCount() === 0) {
            echo "Hiba: Nincs adat a kiválasztott megyében.";
        } else {
            $filename = $countyName . ".csv";
            $file = fopen($filename, "w");
 
            fputcsv($file, array('Megye', 'Város', 'Irányítószám'));
 
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                fputcsv($file, $row);
            }
 
            fclose($file);
            echo "Adatok sikeresen exportálva: " . $filename;
        }
    } else {
        echo "Kérem az adatbázis exportálásához válasszon ki egy megyét!";
    }
} catch(PDOException $e) {
    echo "Hiba történt az adatok exportálása közben: " . $e->getMessage();
}
?>
 