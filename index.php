<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Magyarország települései projectmunka</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="index.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
<h1><b>Magyarország települései projectmunka</b></h1>
<div class="container">
    <div class="card mt-5">
        <div class="card-body">
            <form id="countyForm" class="county-form">
                <div class="form-group">
                    <label for="countySelect">Válasszon vármegyét:</label>
                    <select id="countySelect" name="county" class="form-control">
                        <option value="">Válasszon megyét...</option>
                        <?php
                            try {
                                $conn = new PDO("mysql:host=localhost;dbname=zip_codes", "root", "");
                                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                $stmt = $conn->query("SELECT DISTINCT county_name FROM Counties");
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    // Kihagyjuk az üres sort, ha a megye neve nem üres
                                    if (!empty($row['county_name'])) {
                                        echo '<option value="' . $row['county_name'] . '">' . $row['county_name'] . '</option>';
                                    }
                                }
                            } catch(PDOException $e) {
                                echo "Hiba: " . $e->getMessage();
                            }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="searchCity">Keresés városra:</label>
                    <input type="text" id="searchCity" class="form-control" placeholder="Írja be a város nevét, vagy irányítószámát...">
                    <br>
                    <button type="button" id="searchCityBtn" class="btn btn-info mt-2">Keresés</button>
                </div>
                <div id="searchResult">
                    <div id="postalCodeResult"></div>
                    <div id="countyNameResult"></div>
                    <div id="cityNameResult"></div>
                </div>
                <!-- Vármegye hozzáadása -->
                <div class="add-county-section">
                    <h2>Vármegye hozzáadása</h2>
                    <div class="form-group">
                        <label for="countyAdd">Vármegye neve:</label>
                        <input type="text" class="form-control" id="countyAdd" placeholder="Vármegye neve">
                    </div>
                    <button type="button" class="btn btn-primary" id="addCountyBtn">Vármegye hozzáadása</button>
                    <div class="form-group">
                    <br>
                    <label for="countySelect2">Válasszon vármegyét:</label>
                    <select id="countySelect2" name="county" class="form-control">
                        <option value="">Válasszon megyét...</option>
                        <?php
                            try {
                                $conn = new PDO("mysql:host=localhost;dbname=zip_codes", "root", "");
                                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                $stmt = $conn->query("SELECT DISTINCT county_name FROM Counties");
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    // Kihagyjuk az üres sort, ha a megye neve nem üres
                                    if (!empty($row['county_name'])) {
                                        echo '<option value="' . $row['county_name'] . '">' . $row['county_name'] . '</option>';
                                    }
                                }
                            } catch(PDOException $e) {
                                echo "Hiba: " . $e->getMessage();
                            }
                        ?>
                    </select>
                </div>            
                    <button type="button" class="btn btn-danger" id="DeleteCountyBtn">Vármegye törlése</button>
                    <div class="form-group" id="cityNameFormGroup" style="display:none;">
                    <br>
                    <br>
                    <h2>Település hozzáadása</h2>
                    <label for="cityName" id="cityNameLabel">Település neve:</label>
                    <input type="text" id="cityName" name="cityName" class="form-control" placeholder="Település neve">
                    <br>
                    <label for="cityZIP" id="cityZIPLabel">Település irányítószám:</label>
                    <input type="text" id="cityZIP" name="cityZIP" class="form-control" placeholder="Irányítószám">
                </div>
                <button type="button" id="addCityBtn" class="btn btn-info mt-2" style="display:none;">Hozzáadás</button>
                </div>
            </form>

            <button id="importBtn" class="btn btn-success">Adatbázis importálása</button>
            <button id="exportBtn" class="btn btn-success">Adatbázis exportálása</button>

            <!-- Megye címer -->
            <div id="countyImageContainer"></div>
            <!-- ABC gombok -->
            <br>
            <br>
            <div id="alphabetButtons" style="text-align: center; display: none; margin-right: 50px;"></div>
            <!-- Települések listája -->
            <div id="cityList"></div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="index.js"></script>
<script src="gombok.js"></script>
<script src="database.js"></script>


</body>
</html>
