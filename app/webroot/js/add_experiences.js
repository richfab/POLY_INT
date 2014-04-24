/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var geocoder;

function echarlemagne(){
    
    var count = 0;
    geocoder = new google.maps.Geocoder();
    
    $.getJSON( "../app/webroot/json/echarlemagneNantes.json", function( data ) {
        $.each(data, function(i, item) {
            data[i].motive_id = "1"; //stage
            data[i].school_id = "1"; //nantes
            data[i].typenotification_id = "1"; //jamais
            data[i].comment = ""; //sans commentaire
            if(!data[i].description){
                data[i].description = "";
            }
            //si le pays n'est pas la France
            if(data[i].country_name !== "FRANCE"){
                setTimeout(function(){
                    experience_to_geocode(data[i]);
                },2000*count);
                count++;
            }
        });
    });
    
}

function experience_to_geocode(experience){
    
    var address = experience.city_name+', '+experience.country_name;
    
    geocoder.geocode( { 'address': address}, function(results, status) {
        
        var city = {};
        
        if (status === google.maps.GeocoderStatus.OK) {
            
            experience.city_lat = results[0].geometry.location.lat();
            experience.city_lon = results[0].geometry.location.lng();
            
            for(var i in results[0].address_components){
                if(results[0].address_components[i].types[0]==="country" && results[0].address_components[i].types[1]==="political"){
                    experience.country_id = results[0].address_components[i].short_name;
                }
            }
            send_to_db(experience);
        }
        else{
            console.log('error trying to geocode : '+JSON.stringify(experience));
        }
    });
}

function send_to_db(experience){
    
    $.ajax({
        type:"PUT",
        url : 'add_experience_ajax',
        data : experience,
        dataType : 'json',
        success : function(data) {
            console.log('inserted : '+JSON.stringify(experience));
        },
        error : function(data) {
            console.log('error trying to insert : '+JSON.stringify(experience));
        },
        complete : function(data) {
        }
    });
    
}