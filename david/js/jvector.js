var JSON = {"countries":{"AU":"1","CA":"2","CN":"1","DE":"1","ES":"1","GB":"1","IN":"1","MX":"1","NL":"1","PT":"1","TG":"1","US":"3"},"cities":{"id":["12","8","9","1","6","13","7","5","15","14","4","16","2","10","11"],"coords":[["-33.8674869","151.20699020000006"],["45.5086699","-73.55399249999999"],["46.8032826","-71.242796"],["39.90403","116.40752599999996"],["52.52000659999999","13.404953999999975"],["40.4167754","-3.7037901999999576"],["51.508515","-0.12548719999995228"],["30.3164945","78.03219179999996"],["19.4326077","-99.13320799999997"],["52.3702157","4.895167899999933"],["38.7222524","-9.139336599999979"],["6.2333333","1.4833333000000266"],["39.441667","-78.13333290000003"],["39.952335","-75.16378900000001"],["42.3584308","-71.0597732"]],"names":["Sydney","Montréal","Québec","Pékin","Berlin","Madrid","Londres","Dehra Dun","Mexico","Amsterdam","Lisbonne","Togoville","Shanghai","Philadelphie","Boston"],"experienceNumbers":["1","1","1","1","1","1","1","1","1","1","1","1","1","1","1"]}};

$( document ).ready(function() {
    init_map(JSON);
});

function init_map(JSON){
    //si le navigateur est internet explorer
    if (navigator.appName == "msie"){
        document.getElementById('world-map').style.height = document.body.offsetHeight - $('#offset').height() - 20;
    } else {
        document.getElementById('world-map').style.height = window.innerHeight - $('#top-bar').height() - $('#footer').height() - 1 +'px';
    }
    
    $('#world-map').vectorMap({    
        map: 'world_mill_en',
        zoomMax : 20,
        backgroundColor: '#C1DAE0',
        series: {
            regions: [{
                    scale: ['#3498db', '#3498dc'],
                    attribute: 'fill',
                    values: JSON.countries
                }]
        },
        markerStyle: {
            initial: {
                fill: 'white',
                stroke: '#505050',
                "fill-opacity": 1,
                "stroke-width": 1,
                "stroke-opacity": 1,
                r: 6
            }
        },
        markers: JSON.cities.coords,
        onMarkerLabelShow: function(event, label, index){
            label.html(
                    '<b>'+JSON.cities.names[index]+'</b></br>'+ effectif_to_experience(JSON.cities.experienceNumbers[index])
                    );
        },
        onRegionLabelShow: function(event, label, code){
            label.html(
                    '<b>'+label.html()+'</b></br>'+ effectif_to_experience(JSON.countries[code])
                    );
        },
        onRegionClick: function(event, code){
            $('#world-map').vectorMap('set', 'focus', code);
            $('#list-map').slideUp();
            $('#list-map').slideDown();
//            if(code==="CA"){
//                var mapObject = $('#world-map').vectorMap('get', 'mapObject');
//                mapObject.addMarkers(JSON.cities.coords);
//            }
            console.log(code);
        },
        onMarkerClick: function(event, code){
            $('#list-map').slideUp();
            $('#list-map').slideDown();
            console.log(JSON.cities.id[code]);
        },
        onViewportChange: function(event, scale){
            //on cache le div de la liste
            var mapObject = $('#world-map').vectorMap('get', 'mapObject');
            if(scale>=3.3){
                //on affiche les marqueurs
                mapObject.addMarkers(JSON.cities.coords);
            }
            else{
                //on cache les marqueurs et la liste
                mapObject.removeAllMarkers();
                $('#list-map').slideUp();
            }
        }
    });
}

function update_map(){
    JSON = {"countries":{"AU":80,"FR":10,"IN":4,"ES":1,"MX":3},"cities":{"coords":[[40.4167754,-3.7037902],[37.7749295,-122.4194155],[40.7143528,-74.00597309]],"names":["Madrid","San Fransisco","New York"],"experienceNumbers":[2,4,1],"id":[0,1,2]}};
    
    var mapObject = $('#world-map').vectorMap('get', 'mapObject');
    
    mapObject.series.regions[0].clear();
    mapObject.series.regions[0].setValues(JSON.countries);
    
    mapObject.removeAllMarkers();
    mapObject.addMarkers(JSON.cities.coords);
    
    if(mapObject.scale<4.5){
        mapObject.removeAllMarkers();
    }
}

//fonction qui ajoute ' experience' ou ' experiences' a un nombre d'experience
function effectif_to_experience(experienceNumber){
    if(experienceNumber===undefined){
        return ('Aucune expérience');
    }
    else if(experienceNumber>1){
        return (experienceNumber + ' expériences');
    }
    else{
        return (experienceNumber + ' expérience');
    }
}