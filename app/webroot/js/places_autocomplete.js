//fonction qui empeche la soumission du formulaire lors de l'appui sur la touche entrée (utile lors de la selection du lieu dans la liste avec entrer)
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
    var location_types = $('#ExperienceInput').attr('location-types');
    var cityName = /** @type {HTMLInputElement} */(document.getElementById('CityName'));
    var cityLat = /** @type {HTMLInputElement} */(document.getElementById('CityLat'));
    var cityLon = /** @type {HTMLInputElement} */(document.getElementById('CityLon'));
    var countryName = /** @type {HTMLInputElement} */(document.getElementById('CityCountryName'));
    var countryCode = /** @type {HTMLInputElement} */(document.getElementById('CityCountryId'));
    var autocomplete = new google.maps.places.Autocomplete(input);
    
    //la recherche est limitée aux villes
    autocomplete.setTypes([location_types]);
    
    google.maps.event.addListener(autocomplete, 'place_changed', function() {
        
        var countryLong_name,countryShort_name,cityLong_name = '';
        
        var place = autocomplete.getPlace();
        
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
            
            for(var i in place.address_components){
                if(place.address_components[i].types[0]==="country" && place.address_components[i].types[1]==="political"){
                    countryLong_name = place.address_components[i].long_name;
                    countryShort_name = place.address_components[i].short_name;
                }
                if(place.address_components[i].types[place.address_components[i].types.length-2]==="locality" && place.address_components[i].types[place.address_components[i].types.length-1]==="political"){
                    cityLong_name = place.address_components[i].long_name;
                }
            }
            
            cityLat.value = place.geometry.location.lat();
            cityLon.value = place.geometry.location.lng();
            
            cityName.value = cityLong_name;
            countryName.value = countryLong_name;
            countryCode.value = countryShort_name;
            
            //le lieu a bien été selectionné dans la liste, on valide le champ
            //si ce n'est pas la France :
            if(countryCode.value !== 'FR'){
                $('#ExperienceInputDiv').removeClass('has-error');
                $('#validatePlaceButton').attr('disabled',false);
            }
            else{
                alert("Désolé, les expériences en France ne sont pas affichées sur Polytech Abroad.");
            }
        }
    });
    
    //cette fonction vide le nom de la ville, le pays, la lat et lon dès que l'utilisateur appuie sur une touche (sauf entrer)
    input.onkeyup = function(evt) {
        var evt = (evt) ? evt : ((event) ? event : null);
        //si ce n'est pas la touche entrée (13), on ne fait rien
        if (evt.keyCode !== 13) {
            //si le champs input est vide et que le pays n'est pas la France, ce n'est pas une erreur
            if($('#ExperienceInput').val() === ""){
                $('#ExperienceInputDiv').removeClass('has-error');
                $('#validatePlaceButton').attr('disabled',false);
            }
            //sinon, c'est une erreur (l'utilisateur a essayé de rentrer un lieu 'a la main' ou un lieu en France)
            else{
                $('#ExperienceInputDiv').addClass('has-error');
                $('#validatePlaceButton').attr('disabled',true);
            }
            cityName.value = '';
            cityLat.value = '';
            cityLon.value = '';
            countryName.value = '';
            countryCode.value = '';
        }
    };
}

google.maps.event.addDomListener(window, 'load', initialize);