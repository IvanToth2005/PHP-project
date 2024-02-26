document.getElementById("importBtn").addEventListener("click", function() {
    // AJAX kérést indítasz a setupDatabase.php fájlra
    $.ajax({
        url: "setupDatabase.php",
        success: function(response) {
            // Sikeres válasz esetén promptban jelenítsd meg az üzenetet
            alert("Az adatbázis sikeresen importálva.");
            location.reload();
        },
        error: function(xhr, status, error) {
            // Hiba esetén kiírhatod a konzolra a hibaüzenetet
            console.error("Hiba történt az adatbázis importálása közben:", status, error);
        }
    });
});

document.getElementById("exportBtn").addEventListener("click", function() {
    // Kiválasztott megye nevének lekérése
    var selectedCounty = $("#countySelect").val();

    // Ellenőrzés, hogy van-e kiválasztva megye
    if (!selectedCounty) {
        alert("Kérem az adatbázis exportálásához válasszon ki egy megyét!");
        return;
    }

    // AJAX kérést indítasz az exportData.php fájlra
    $.ajax({
        url: "exportData.php",
        type: "POST",
        data: { county: selectedCounty }, // Kiválasztott megye továbbítása POST kérésként
        success: function(response) {
            // Sikeres válasz esetén promptban jelenítsd meg az üzenetet
            alert("Az adatbázis sikeresen exportálva.");
        },
        error: function(xhr, status, error) {
            // Hiba esetén kiírhatod a konzolra a hibaüzenetet
            console.error("Hiba történt az adatok exportálása közben:", status, error);
        }
    });
});
