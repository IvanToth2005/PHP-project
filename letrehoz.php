<?php
class DatabaseSetup {
    private $con;

    public function __construct($host, $user, $password, $dbName) {
        $this->con = new mysqli($host, $user, $password);

        if ($this->con->connect_error) {
            die("Connection failed: " . $this->con->connect_error);
        }

        // Create database if not exists
        $this->createDatabase($dbName);

        // Select database
        $this->con->select_db($dbName);
    }

    private function createDatabase($dbName) {
        $sql = "CREATE DATABASE IF NOT EXISTS $dbName";

        if (!$this->con->query($sql)) {
            die("Database creation error: " . $this->con->error);
        }
    }

    public function createTables() {
        // Create Counties table
        $sql_create_counties_table = "CREATE TABLE IF NOT EXISTS Counties (
            id INT AUTO_INCREMENT PRIMARY KEY,
            county_name VARCHAR(255) NOT NULL
        )";

        if (!$this->con->query($sql_create_counties_table)) {
            die("Counties table creation error: " . $this->con->error);
        }

        // Create Cities table
        $sql_create_cities_table = "CREATE TABLE IF NOT EXISTS Cities (
            id INT AUTO_INCREMENT PRIMARY KEY,
            city_name VARCHAR(255) NOT NULL,
            county_id INT,
            FOREIGN KEY (county_id) REFERENCES Counties(id)
        )";

        if (!$this->con->query($sql_create_cities_table)) {
            die("Cities table creation error: " . $this->con->error);
        }

        // Create ZipCodes table
        $sql_create_zipcodes_table = "CREATE TABLE IF NOT EXISTS ZipCodes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            zip_code VARCHAR(255) NOT NULL,
            city_id INT,
            FOREIGN KEY (city_id) REFERENCES Cities(id)
        )";

        if (!$this->con->query($sql_create_zipcodes_table)) {
            die("ZipCodes table creation error: " . $this->con->error);
        }

        // Insert data into Counties and Cities tables from CSV file
        $this->insertDataFromCSV();
    }

    private function insertDataFromCSV() {
        $csv_file = 'zip_codes.csv';

        if (($handle = fopen($csv_file, "r")) !== FALSE) {
            fgetcsv($handle); // Skip header row

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Adatok beolvasása
                $county_name = mysqli_real_escape_string($this->con, $data[0]); // Első oszlop: County
                $zip_code = mysqli_real_escape_string($this->con, $data[1]); // Második oszlop: Zip code
                $city_name = mysqli_real_escape_string($this->con, $data[2]); // Harmadik oszlop: City

                // Beszúrás a Counties táblába, ha még nem létezik
                $sql_insert_county = "INSERT IGNORE INTO Counties (county_name) VALUES ('$county_name')";

                if (!$this->con->query($sql_insert_county)) {
                    die("Error inserting data into Counties table: " . $this->con->error);
                }

                // Beszúrás a Cities táblába
                $sql_insert_city = "INSERT INTO Cities (city_name, county_id) SELECT '$city_name', id FROM Counties WHERE county_name = '$county_name'";

                if (!$this->con->query($sql_insert_city)) {
                    die("Error inserting data into Cities table: " . $this->con->error);
                }

                // Beszúrás a ZipCodes táblába
                $sql_insert_zipcode = "INSERT INTO ZipCodes (zip_code, city_id) SELECT '$zip_code', id FROM Cities WHERE city_name = '$city_name'";

                if (!$this->con->query($sql_insert_zipcode)) {
                    die("Error inserting data into ZipCodes table: " . $this->con->error);
                }
            }

            fclose($handle);
        }
    }

    public function closeConnection() {
        $this->con->close();
    }
}

// Create instance of DatabaseSetup
$databaseSetup = new DatabaseSetup('localhost', 'root', '', 'zip_codes');

// Create tables and insert data from CSV file
$databaseSetup->createTables();

// Close database connection
$databaseSetup->closeConnection();
?>
