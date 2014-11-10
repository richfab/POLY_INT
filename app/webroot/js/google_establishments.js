function get_establishments_from_bd() {

    var min = 140;
    var max = min + 10;

    $.ajax({
        type:"GET",
        url : 'get_establishments.json',
        dataType : 'json',
        success : function(data) {

            for(var i in data) {
                //si l'expérience n'est pas dans les expériences pour lesquelles google place a associé un mauvais lieu
                if(i<max && i>=min) {
                    get_google_establishment(data[i]);
                }
            }

        }
    });

}

function get_google_establishment(experience) {

    var location = new google.maps.LatLng(experience.City.lat,experience.City.lon);

    var map = new google.maps.Map(document.getElementById('map'), {
        center: location,
        zoom: 15
    });

    var request = {
        location: location,
        radius: 100000,//100km
        types: ['establishment'],
        key: 'AIzaSyBylSniElzhlZm_FLt9wCkRW0vMdAPA',
        query: experience.Experience.establishment,
        language: 'fr'
    };

    var service = new google.maps.places.PlacesService(map);
    service.textSearch(request, function(results, status) {
        return callback(experience, results, status);
    });
}

function callback(experience, results, status) {

    if (status == google.maps.places.PlacesServiceStatus.OK) {

        var first_result = results[0];

        var establishment = {
            google_place_id: first_result.place_id,
            name: first_result.name,
            city_id: experience.City.id,
            formatted_address: first_result.formatted_address
        }
        var color = 'black';

    } else {

        var establishment = {
            google_place_id: 'NORESULT',
            name: null,
            city_id: 1,
            formatted_address: null
        }
        var color = 'red';
    }

    var response = confirm(experience.Experience.id + '- ' + experience.Experience.establishment + ' (' + experience.City.name + ',' + experience.City.country_id + ') --> ' + establishment.name + ' (' + establishment.formatted_address + ')');

    if(response == false){
        establishment = {
            google_place_id: 'NORESULT',
            name: null,
            city_id: 1,
            formatted_address: null
        };
        color = 'red';
    }

    save_establishment(establishment, experience);

    console.log('%c' + experience.Experience.id + '- ' + experience.Experience.establishment + ' (' + experience.City.name + ',' + experience.City.country_id + ') --> ' + establishment.name + ' (' + establishment.formatted_address + ')', 'color:' + color);

}

function save_establishment(establishment, experience) {

    $.ajax({
        type:"POST",
        url : '../establishments/add_establishment_ajax',
        data : establishment,
        dataType : 'json',
        success : function(data) {
            //            console.log('inserted or retrieved : '+JSON.stringify(establishment));
            experience.Experience.establishment_id = data.establishment_id;
            save_experience_establishment(experience);
        },
        error : function(data) {
            console.log('error trying to insert or retrieve : '+JSON.stringify(establishment));
        }
    });

}

function save_experience_establishment(experience) {
    $.ajax({
        type:"POST",
        url : 'save_experience_establishment_ajax',
        data : experience,
        dataType : 'json',
        success : function(data) {
            //            console.log('updated : '+JSON.stringify(experience));
        },
        error : function(data) {
            console.log('error trying to insert : '+JSON.stringify(experience));
        }
    });
}

get_establishments_from_bd();