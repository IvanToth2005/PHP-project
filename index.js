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
                            cityList += '<div class="city">' + city + '</div>'; 
                        });
                        cityList += '</div>'; // Záró div
                        $('#cityList').html(cityList).show(); // Települések div-einek hozzáadása a cityList div-hez és megjelenítése
                        
                        // Megye címerének megjelenítése
                        $('#countyImageContainer').html('<img src="' + response.countyImage + '">').show(); // countyImageContainer div megjelenítése
                    } else {
                        $('#cityList').hide(); 
                    }
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
            url: 'csvBeolvas.php',
            method: 'POST',
            data: {county: selectedCounty, cityName: cityName},
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
