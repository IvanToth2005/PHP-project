$(document).ready(function(){
    // Település nevét bekérő mező és hozzáadás gomb inicializálása
    $('#cityNameFormGroup, #addCityBtn, #cityNameLabel').hide();

    // Megyék legördülő menü változásának eseménykezelője
    $('#countySelect').change(function(){
        var selectedCounty = $(this).val();
        
        // Ellenőrzés, hogy van-e választás a megyék menüben
        if(selectedCounty) {
            $('#cityNameFormGroup, #addCityBtn, #cityNameLabel').show();
            
            $.ajax({
                url: 'csvBeolvas.php',
                method: 'POST',
                data: {county: selectedCounty},
                dataType: 'json',
                success: function(response){
                    if(response.cities.length > 0){
                        var cityList = '<div class="city-list">'; // Kezdő div
                        response.cities.forEach(function(city){
                            cityList += '<div class="city">' +
                                            city.zip_code + ' - ' + city.city_name +
      
                                            '</div>'+
                                        '<div class="button-container">' +
                                        '<button class="btn btn-primary modifyBtn" data-city="' + city.city_name + '" data-zip="' + city.zip_code + '" data-county="' + selectedCounty + '">Módosít</button>&nbsp;&nbsp;&nbsp;' +
                                        '<button class="btn btn-danger deleteBtn" data-city="' + city.city_name + '">Törlés</button>' +
                                    '</div>'; 
                        });
                        cityList += '</div>'; // Záró div
                        $('#cityList').html(cityList).show(); // Települések div-einek hozzáadása a cityList div-hez és megjelenítése
                        
                        // Törlés gombok eseménykezelőjének hozzáadása
                        $('.deleteBtn').click(function(e) {
                            e.preventDefault(); // Az alapértelmezett művelet megakadályozása
                            var cityName = $(this).data('city'); // Település nevének kinyerése
                            
                            // Törlési művelet végrehajtása a cityName alapján
                            $.ajax({
                                url: 'eltavolit.php',
                                method: 'POST',
                                data: {cityName: cityName},
                                dataType: 'json',
                                success: function(response){
                                    if(response.success) {
                                        // Település sikeresen eltávolítva
                                        console.log('Település sikeresen eltávolítva: ' + cityName);
                                        // Újra betöltjük a településeket
                                        $('#countySelect').trigger('change');
                                    } else {
                                        // Hiba történt a törlés során
                                        console.error('Hiba történt a település törlésekor: ' + response.message);
                                    }
                                },
                                error: function(xhr, status, error) {
                                    // Hibakezelés AJAX hiba esetén
                                    console.error('AJAX hiba:', status, error);
                                }
                            });
                        });

                        $('.modifyBtn').click(function(e) {
                            e.preventDefault(); // Az alapértelmezett művelet megakadályozása
                            var cityName = $(this).data('city'); // Település nevének kinyerése
                            var zipCode = $(this).data('zip'); // Irányítószám kinyerése
                            var county = $(this).data('county'); // Megye kinyerése
                            var modifiedCityName = prompt('A módosított település neve:', cityName);
                            var modifiedZIPCode = prompt('A módosított település irányítószáma:', zipCode);
                            var modifiedCounty = prompt('A módosított település ebben a megyében található:', county);

                            // AJAX kérés küldése a modosit.php fájlnak
                            $.ajax({
                                url: 'modosit.php',
                                method: 'POST',
                                data: {
                                    cityName: cityName,
                                    modifiedCityName: modifiedCityName,
                                    modifiedZIPCode: modifiedZIPCode,
                                    modifiedCounty: modifiedCounty
                                },
                                dataType: 'json',
                                success: function(response){
                                    // Sikeres válasz kezelése
                                    console.log(response);
                                    
                                    // Ha sikeres a válasz, frissítsük az adatokat az oldalon
                                    $('#countySelect').trigger('change');
                                },
                                error: function(xhr, status, error) {
                                    // Hibakezelés AJAX hiba esetén
                                    console.error('AJAX hiba:', status, error);
                                }
                            });
                        });
                    } else {
                        $('#cityList').hide(); 
                    }
                    
                    // Megye címerének megjelenítése
                    $('#countyImageContainer').html('<img src="' + response.countyImage + '">').show(); // countyImageContainer div megjelenítése
                }
            });
        } else {
            // Ha nincs választás a megyék menüben, elrejtjük a település nevét bekérő mezőt, a hozzáadás gombot és a Település neve feliratot is
            $('#cityNameFormGroup, #addCityBtn, #cityNameLabel').hide();
            
            // Ha nincs választás a megyék menüben, elrejtjük a település listát és a megye címerének tartalmát is
            $('#cityList, #countyImageContainer').hide(); 
        }
    });

    // Település hozzáadás gomb eseménykezelője
    $('#addCityBtn').click(function(){
        var selectedCounty = $('#countySelect').val();
        var cityName = $('#cityName').val();
        var cityZIP = $('#cityZIP').val();
    
        // Ellenőrzés, hogy mind a városnév, mind az irányítószám mezők kitöltve vannak-e
        if (cityName && cityZIP) {
            $.ajax({
                url: 'hozzaad.php',
                method: 'POST',
                data: {county: selectedCounty, cityName: cityName, cityZIP: cityZIP},
                dataType: 'json',
                success: function(response){
                    if(response.success) {
                        console.log('Település sikeresen hozzáadva: ' + cityName);
                        $('#countySelect').trigger('change');
                        $('#cityName').val('');
                        $('#cityZIP').val('');
                    } else {
                        console.error('Hiba történt a település hozzáadása közben: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX hiba:', status, error);
                }
            });
        } else {
            // Ha valamelyik mező nincs kitöltve, jeleníts meg egy figyelmeztetést vagy tedd inaktívvá a gombot
            console.error('Mindkét mezőt ki kell tölteni!');
        }
    });

    $('#searchCityBtn').click(function() {
        
        $('#postalCodeResult').empty();
        $('#countyNameResult').empty();
        $('#cityNameResult').empty();
        
        const userInput = $('#searchCity').val();
        if ($.isNumeric(userInput)) {
            
            searchByZipCode(userInput);
        } else {
            
            searchByCityName(userInput);
        }
    });

    // Vármegye hozzáadása gomb eseménykezelője
    $('#addCountyBtn').click(function() {
        const countyName = $('#countyAdd').val(); // A beírt vármegye nevének lekérése az input mezőből
        if (countyName.trim() !== '') { // Ellenőrizzük, hogy a beírt szöveg nem üres
            $.ajax({
                type: "POST",
                url: "add_county.php", // Az elérési út a vármegye hozzáadó PHP fájlhoz
                data: { countyName: countyName }, // A vármegye nevét küldjük a szervernek
                success: function(response) {
                    alert(response); // Az AJAX válasz megjelenítése (opcionális)
                    // Töröljük az input mező tartalmát
                    $('#countyAdd').val('');
                    location.reload(); // Oldal újratöltése a friss adatokkal
                },
                error: function(xhr, status, error) {
                    console.error('AJAX hiba:', status, error); // Hibakezelés AJAX hiba esetén
                }
            });
        } else {
            alert('Kérjük, írja be a vármegye nevét!'); // Felhasználó értesítése, ha az input mező üres
        }
    });

    // Vármegye törlése
    $("#DeleteCountyBtn").click(function() {
        var countyName = $("#countySelect2").val();
        if (countyName) {
            if (confirm("Biztosan törölni szeretné ezt a megyét?")) {
                $.ajax({
                    url: "delete_county.php",
                    type: "POST",
                    data: { countyName: countyName },
                    success: function(response) {
                        alert("Vármegye sikeresen törölve"); // Módosított üzenet
                        location.reload(); // Oldal újratöltése a friss adatokkal
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX hiba:', status, error); // Hibakezelés AJAX hiba esetén
                        alert("Hiba történt a megye törlése közben.");
                    }
                });
            }
        } else {
            alert("Kérjük, válasszon ki egy megyét a törléshez.");
        }
    });

    // Megye kiválasztásának eseménykezelője
    $('#countySelect2').change(function() {
        var selectedCounty = $(this).val();
        if (selectedCounty) {
            $('.add-county-section').show();
        } else {
            $('.add-county-section').hide();
        }
    });

});

function searchByZipCode(zipCode) {
    $('#postalCodeResult').empty();
    $('#countyNameResult').empty();
    $('#cityNameResult').empty();
    
    $.ajax({
        type: "POST",
        url: "get_city_and_county_by_zipcode.php",
        data: { zipCode: zipCode },
        success: function(response) {
            const data = JSON.parse(response);
            $('#postalCodeResult').html('<p>Irányítószám: ' + zipCode + '</p>');
            $('#countyNameResult').html('<p>Megye: ' + data.countyName + '</p>');
            $('#cityNameResult').html('<p>Város: ' + data.cityName + '</p>');
            
            // Módosító gomb hozzáadása
            $('#cityNameResult').append('<button class="btn btn-primary modifyResultBtn" data-city="' + data.cityName + '" data-zip="' + zipCode + '">Módosít</button>');
            
            // Törlő gomb hozzáadása
            $('#cityNameResult').append('<button class="btn btn-danger deleteResultBtn" data-city="' + data.cityName + '" data-zip="' + zipCode + '">Törlés</button>');
            
            // Módosító gomb eseménykezelője
            $('.modifyResultBtn').click(function(){
                var cityName = $(this).data('city');
                var postalCode = $(this).data('postal');
                var county = $(this).data('county');
                var modifiedCityName = prompt('A módosított város neve:', cityName);
                var modifiedPostalCode = prompt('A módosított irányítószám:', postalCode);
                var modifiedCounty = prompt('A módosított megye:', county);
            
                // AJAX kérés a módosításhoz
                $.ajax({
                    type: "POST",
                    url: "modify_city.php",
                    data: { 
                        cityName: cityName, 
                        modifiedCityName: modifiedCityName, 
                        postalCode: postalCode, 
                        modifiedPostalCode: modifiedPostalCode,
                        county: county,
                        modifiedCounty: modifiedCounty
                    },
                    success: function(response) {
                        // Sikeres válasz esetén
                        alert(response); // Vagy más visszajelzés
                    },
                    error: function(xhr, status, error) {
                        // Hibakezelés
                        console.error('AJAX hiba:', status, error);
                    }
                });
            });
            
            
            // Törlő gomb eseménykezelője
            $('.deleteResultBtn').click(function(){
                var cityName = $(this).data('city');
                var zipCode = $(this).data('zip');
                
                // AJAX kérés a törléshez
                $.ajax({
                    type: "POST",
                    url: "delete_city.php",
                    data: { cityName: cityName, zipCode: zipCode },
                    success: function(response) {
                        // Sikeres válasz esetén
                        alert(response); // Vagy más visszajelzés
                    },
                    error: function(xhr, status, error) {
                        // Hibakezelés
                        console.error('AJAX hiba:', status, error);
                    }
                });
            });
        }
    });
}

function searchByCityName(cityName) {
    $('#postalCodeResult').empty();
    $('#countyNameResult').empty();
    $('#cityNameResult').empty();
    $.ajax({
        type: "POST",
        url: "get_postal_code.php",
        data: { cityName: cityName },
        success: function(response) {
            const postalCode = JSON.parse(response).postalCode;
            $('#postalCodeResult').html('<p>Irányítószám: ' + postalCode + '</p>');
            $.ajax({
                type: "POST",
                url: "get_county_name.php",
                data: { cityName: cityName },
                success: function(response) {
                    const countyName = JSON.parse(response).countyName;
                    $('#countyNameResult').html('<p>Megye: ' + countyName + '</p>');
                    $('#cityNameResult').html('<p>Város: ' + cityName + '</p>');
                    
                    // Módosító gomb hozzáadása
                    $('#cityNameResult').append('<button class="btn btn-primary modifyResultBtn" data-city="' + cityName + '" data-postal="' + postalCode + '">Módosít</button>');
                    
                    // Törlő gomb hozzáadása
                    $('#cityNameResult').append('<button class="btn btn-danger deleteResultBtn" data-city="' + cityName + '">Törlés</button>');
                    
                    $('.modifyResultBtn').click(function(){
                        var cityName = $(this).data('city');
                        var postalCode = $(this).data('postal');
                        var county = $(this).data('county');
                        var modifiedCityName = prompt('A módosított város neve:', cityName);
                        var modifiedPostalCode = prompt('A módosított irányítószám:', postalCode);
                        var modifiedCounty = prompt('A módosított megye:', county);
                    
                        // AJAX kérés a módosításhoz
                        $.ajax({
                            type: "POST",
                            url: "modify_city.php",
                            data: { 
                                cityName: cityName, 
                                modifiedCityName: modifiedCityName, 
                                postalCode: postalCode, 
                                modifiedPostalCode: modifiedPostalCode,
                                county: county,
                                modifiedCounty: modifiedCounty
                            },
                            success: function(response) {
                                // Sikeres válasz esetén
                                alert(response); // Vagy más visszajelzés
                            },
                            error: function(xhr, status, error) {
                                // Hibakezelés
                                console.error('AJAX hiba:', status, error);
                            }
                        });
                    });
                    
                    // Törlő gomb eseménykezelője
                    $('.deleteResultBtn').click(function(){
                        var cityName = $(this).data('city');
                        
                        // AJAX kérés a törléshez
                        $.ajax({
                            type: "POST",
                            url: "delete_city.php",
                            data: { cityName: cityName },
                            success: function(response) {
                                // Sikeres válasz esetén
                                alert(response); // Vagy más visszajelzés
                            },
                            error: function(xhr, status, error) {
                                // Hibakezelés
                                console.error('AJAX hiba:', status, error);
                            }
                        });
                    });
                }
            });

        }
    });
}


