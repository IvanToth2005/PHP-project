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
                            cityList += '<div class="city">' + city + '</div>' + '<div class="delete-container"><button class="btn btn-danger deleteBtn" data-city="' + city + '">Törlés</button></div>'; 
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
        
        $.ajax({
            url: 'hozzaad.php', // PHP fájl, amely a települést hozzáadja az adatbázishoz
            method: 'POST',
            data: {county: selectedCounty, cityName: cityName}, // Megadott megye és településnév
            dataType: 'json',
            success: function(response){
                if(response.success) {
                    console.log('Település sikeresen hozzáadva: ' + cityName);
                    $('#countySelect').trigger('change'); // Újra betöltjük a településeket
                    $('#cityName').val(''); // Ürítjük a település nevet input mezőt
                } else {
                    console.error('Hiba történt a település hozzáadása közben: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX hiba:', status, error);
            }
        });
    });
});
