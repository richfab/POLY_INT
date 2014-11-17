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

    var validatePlaceButton = /** @type {HTMLInputElement} */(document.getElementById('validatePlaceButton'));
    var establishment_input = /** @type {HTMLInputElement} */(document.getElementById('ExperienceEstablishmentinput'));
    var establishment = /** @type {HTMLInputElement} */(document.getElementById('ExperienceEstablishment'));
    var google_place_id = /** @type {HTMLInputElement} */(document.getElementById('ExperienceGooglePlaceid'));
    var location_types = "establishment";
    
    var autocomplete = new google.maps.places.Autocomplete(establishment_input);

    //la recherche est limitée aux villes
    autocomplete.setTypes([location_types]);

    google.maps.event.addListener(autocomplete, 'place_changed', function() {

        var place = autocomplete.getPlace();

        if(place.place_id){
            google_place_id.value = place.place_id;
            establishment.value = establishment_input.value = place.name;
        } else {
            google_place_id.value = "";
        }

    });
    
    //à la validation du formulaire, on renseigne le nom de l'école à partir de l'autocomplete si il a été saisi manuellement par l'utilisateur
    validatePlaceButton.onclick = function() {
        if(google_place_id.value == "") {
            establishment.value = establishment_input.value;
        }
    }
    
    //cette fonction vide le  google place id dès que l'utilisateur appuie sur une touche (sauf entrer)
    establishment_input.onkeyup = function(evt) {
        var evt = (evt) ? evt : ((event) ? event : null);
        //si ce n'est pas la touche entrée (13), on ne fait rien
        if (evt.keyCode !== 13) {
            //si le champs input est vide et que le pays n'est pas la France, ce n'est pas une erreur
            google_place_id.value = "";
        }
    };
}

google.maps.event.addDomListener(window, 'load', initialize);