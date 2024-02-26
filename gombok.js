
$(document).ready(function() {
    // ABC gombok létrehozása
    for (let charCode = 65; charCode <= 90; charCode++) {
        const letter = String.fromCharCode(charCode);
        $('#alphabetButtons').append('<button type="button" class="btn btn-default" onclick="filterCitiesByCounty(\'' + letter + '\')">' + letter + '</button>');
    }

    // Egyéb inicializációk és eseménykezelők...

    // Megyék kiválasztásának eseménykezelő
    $('#countySelect').change(function() {
        // Ha van kiválasztott megye, megjelenítjük az ABC gombokat és a hozzáadás gombot
        if ($(this).val()) {
            $('#alphabetButtons, #addCityBtn').show();
        } else {
            // Ha nincs kiválasztott megye, elrejtjük az ABC gombokat és a hozzáadás gombot
            $('#alphabetButtons, #addCityBtn').hide();
        }
        filterCitiesByCounty();
    });
});

function filterCitiesByCounty(letter) {
const selectedCounty = $('#countySelect').val();
// Az adatbázisból városok lekérése a kiválasztott megye és betű alapján
const query = "SELECT city_name FROM Cities WHERE county_id IN (SELECT county_id FROM Counties WHERE county_name = '" + selectedCounty + "') AND city_name LIKE '" + letter + "%'";
$.ajax({
    type: "POST",
    url: "get_cities.php", // Az elérési út az adatok PHP-fájlba küldéséhez és lekéréséhez
    data: { query: query },
    success: function(response) {
        displayCities(JSON.parse(response));
    }
});
}

function displayCities(cities) {
// Városok listájának megjelenítése az ABC gombok után
const citiesHTML = '<h4>Városok:</h4>' +
                   '<div class="btn-group" role="group" aria-label="City buttons">' +
                   cities.map(city => '<button type="button" class="btn btn-info" onclick="selectCity(\'' + city + '\')">' + city + '</button>').join('') +
                   '</div>';
$('#cityList').html(citiesHTML);
}

function selectCity(city) {
// Kiválasztott városok megjelenítése
$('#selectedCities').append('<p>' + city + '</p>');
}
