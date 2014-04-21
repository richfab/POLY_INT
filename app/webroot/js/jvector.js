var _data;
var min_zoom_marker = 3;
var selected_region = {};

$( document ).ready(function() {
    init_map();
    get_map_init();
});

function init_map(){
    //si le navigateur est internet explorer
    if (navigator.appName == "msie"){
        document.getElementById('world-map').style.height = document.body.offsetHeight - $('#offset').height() - 20;
    } else {
        document.getElementById('world-map').style.height = window.innerHeight - $('#top-bar').height() - $('#footer').height() - 1 +'px';
    }
    
    //fichier json de donnees vide pour l'initialisation de la carte
    _data = {"countries":{},"cities":{"id":[],"coords":[],"names":[],"experienceNumbers":[]}};
    
    $('#world-map').vectorMap({    
        map: 'world_mill_en',
        zoomMax : 20,
        backgroundColor: '#C1DAE0',
        series: {
            regions: [{
                    scale: ['#3498db', '#3498dc'],
                    attribute: 'fill',
                    values: _data.countries
                }]
        },
        markerStyle: {
            initial: {
                fill: 'white',
                stroke: '#505050',
                "fill-opacity": 1,
                "stroke-width": 1,
                "stroke-opacity": 1,
                r: 5
            }
        },
        markers: _data.cities.coords,
        onMarkerLabelShow: function(event, label, index){
            label.html(
                    '<b>'+_data.cities.names[index]+'</b></br>'+ effectif_to_experience(_data.cities.experienceNumbers[index])
                    );
        },
        onRegionLabelShow: function(event, label, code){
            if(_data.countries){
                var label_content = '<b>'+label.html()+'</b></br>'+ effectif_to_experience(_data.countries[code]);
            }
            else{
                var label_content = '<b>'+label.html()+'</b></br>'+ effectif_to_experience(0);
            }
            label.html(label_content);
        },
        onRegionClick: function(event, code){
            $('#world-map').vectorMap('set', 'focus', code);
            
            //on recupere les params initiaux
            var filter = get_filter_params();
            //on ajoute le parametre country_id
            var country_json = $.parseJSON('{"country_id":"'+code+'"}');
            //on join les trois tableaux de parametres
            $.extend(filter,country_json);
            
            $('#list-map').slideUp(100, function(){
                new_search('get_experiences_map',filter);
            });
            
            selected_region.type = 'country_id';
            selected_region.id = code;
        },
        onMarkerClick: function(event, code){
            //on recupere les params initiaux
            var filter = get_filter_params();
            //on ajoute le parametre city_id
            var city_id = $.parseJSON('{"city_id":"'+_data.cities.id[code]+'"}'); 
            //on join les trois tableaux de parametres
            $.extend(filter,city_id);
            
            $('#list-map').slideUp(100, function(){
                new_search('get_experiences_map',filter);
            });
            
            selected_region.type = 'city_id';
            selected_region.id = _data.cities.id[code];
        },
        onViewportChange: function(event, scale){
            //on cache le div de la liste
            var mapObject = $('#world-map').vectorMap('get', 'mapObject');
            var limit;
            if(mapObject.baseScale>=1){
                limit = min_zoom_marker/mapObject.baseScale;
            }
            else{
                limit = min_zoom_marker*mapObject.baseScale;
            }
            if(scale>=limit){
                //on affiche les marqueurs
                if(_data.cities){
                    mapObject.addMarkers(_data.cities.coords);
                }
            }
            else{
                //on cache les marqueurs et la liste
                mapObject.removeAllMarkers();
                $('#list-map').slideUp(100);
                selected_region = {};
            }
        }
    });
}

//fonction qui met a jour les couleurs et les marqueurs de la carte a partir d'un objet json
function update_map(data){
    
    _data = data;
    
    var mapObject = $('#world-map').vectorMap('get', 'mapObject');
    
    mapObject.series.regions[0].clear();
    if(_data.countries){
        mapObject.series.regions[0].setValues(_data.countries);
    }
    
    mapObject.removeAllMarkers();
    if(_data.cities){
        mapObject.addMarkers(_data.cities.coords);
    }
    
    if(mapObject.scale<min_zoom_marker){
        mapObject.removeAllMarkers();
    }
}

//fonction qui ajoute ' experience' ou ' experiences' a un nombre d'experience
function effectif_to_experience(experienceNumber){
    if(experienceNumber===undefined || experienceNumber===0){
        return ('Aucune expérience');
    }
    else if(experienceNumber>1){
        return (experienceNumber + ' expériences');
    }
    else{
        return (experienceNumber + ' expérience');
    }
}