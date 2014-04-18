/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var geocoder;

function echarlemagne(){
    
    geocoder = new google.maps.Geocoder();
    
    $.getJSON( "../app/webroot/json/echarlemagneNantes.json", function( data ) {
        $.each(data, function(i, item) {
            experience_to_geocode(data[i]);
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
            console.log('error trying to geocode : '+address);
        }
    });
}

function send_to_db(_data){
    
    $.ajax({
        type:"PUT",
        url : 'add_experience_ajax',
        data : _data,
        dataType : 'json',
        success : function(data) {
            console.log('inserted : '+_data);
        },
        error : function(data) {
            console.log('error trying to insert : '+_data);
        },
        complete : function(data) {
            alert('done');
        }
    });
    
}