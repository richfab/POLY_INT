//fonction qui empeche la soumission du formulaire lors de l'appui sur la touche entrée (utile lors de la selection du lieu dans la liste)
function stopEnter(evt) {
    var evt = (evt) ? evt : ((event) ? event : null);
    var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
    if ((evt.keyCode == 13) && (node.type == "text")) {
        return false;
    }
}
document.onkeypress = stopEnter;

function initialize() {
    
    var input = /** @type {HTMLInputElement} */(document.getElementById('ExperienceInput'));
    var cityName = /** @type {HTMLInputElement} */(document.getElementById('CityName'));
    var cityLat = /** @type {HTMLInputElement} */(document.getElementById('CityLat'));
    var cityLon = /** @type {HTMLInputElement} */(document.getElementById('CityLon'));
    var countryName = /** @type {HTMLInputElement} */(document.getElementById('CountryName'));
    var countryCode = /** @type {HTMLInputElement} */(document.getElementById('CountryCode'));
    var autocomplete = new google.maps.places.Autocomplete(input);
    
    //la recherche est limitée aux villes
    autocomplete.setTypes(['(cities)']);
    
    google.maps.event.addListener(autocomplete, 'place_changed', function() {
        
        var place = autocomplete.getPlace();
        console.log(place);
        
        if (!place.geometry) {
            // empty cityLat and cityLon fields
            cityName.value = '';
            cityLat.value = '';
            cityLon.value = '';
            countryName.value = '';
            countryCode.value = '';
            return;
        }
        
        // If the place has a geometry, then populate the citylat citylon fields
        if (place.geometry) {
            
            var cityLong_name = place.address_components[0].long_name;
            
            for(var i in place.address_components){
                if(place.address_components[i].types[0]==="country" && place.address_components[i].types[1]==="political"){
                    var countryLong_name = place.address_components[i].long_name;
                    var countryShort_name = place.address_components[i].short_name;
                }
            }
            
            cityLat.value = place.geometry.location.lat();
            cityLon.value = place.geometry.location.lng();
            
            cityName.value = cityLong_name;
            countryName.value = countryLong_name;
            countryCode.value = countryShort_name;
        }
    });
    
    //cette fonction permet d'empecher l'utilisateur de modifier a la main le lieu en vidant les champs citylat, citylon et input eu moment ou l'utilisateur clique dans le champs input pour le modifier
    input.onfocus = function() {
        cityName.value = '';
  	cityLat.value = '';
        cityLon.value = '';
        countryName.value = '';
        countryCode.value = '';
        input.value = '';
    };
}

google.maps.event.addDomListener(window, 'load', initialize);