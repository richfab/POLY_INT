/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

window.fbAsyncInit = function() {
    
    FB.init({
        appId      : '230656413765727',
        status     : true, // check login status
        xfbml      : false, //do not check for social plugins
        version    : 'v2.0'
    });
    
    FB.login(function(){

        //get albums
        FB.api("/me/albums",'get',{limit:100},
            function (response) {
                if (response && !response.error) {
                    for(var i in response.data){
                        var album = response.data[i];
                        $('#albums').append('<a href="#" onclick="get_photos(\'' + album.id + '\',' + album.count + ')">' + album.name + ' (' + album.count +')</a></br>');
                    }
                }
            }
        );

    }, {scope: 'user_photos'});
};
            
(function(d, s, id){
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {return;}
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/fr_FR/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

function get_photos(album_id, album_count){
    
    $('#photos').empty();
    $('#total').html(album_count);
    $('#current').html(0);
    
    $('.progress-bar').attr('aria-valuemax',album_count);
    $('.progress-bar').html('0%');
    $('.progress-bar').css('width','0%');
    $('.progress-bar').attr('aria-valuenow','0');
    
    FB.api("/" + album_id + "/photos",'get',{limit:100},
        function(response){
            handle_response_photos(response);
        }
    );
}

function get_more_photos(cursor){
    FB.api(cursor,'get',{limit:100},
        function(response){
            handle_response_photos(response);
        }
    );
}

function handle_response_photos(response){
    console.log(response);
    if (response && !response.error) {
        for(var i in response.data){
            var photo = response.data[i];
            handle_photo(photo);
        }
        if(response.paging.next){
            get_more_photos(response.paging.next);
        }
    }
}

function handle_photo(photo){
    
    $.ajax({
        type:"PUT",
        url : '../photos/add_fb_photo',
        data : {
            fb_id : photo.id,
            source : photo.source,
            caption : photo.name,
            experience_id : 24
        },
        dataType : 'json',
        success : function(data) {
            $('#photos').append('<img src="' + photo.picture + '"></img>');
            $('#current').html(parseInt($('#current').html())+1);
            
            var current_progress = parseInt($('.progress-bar').attr('aria-valuenow'))+1;
            var percentage_progress = parseInt(current_progress / $('.progress-bar').attr('aria-valuemax') * 100) + '%';
            $('.progress-bar').html(percentage_progress);
            $('.progress-bar').css('width',percentage_progress);
            $('.progress-bar').attr('aria-valuenow',current_progress);
        },
        error : function(data) {
            console.log('error trying to insert : '+JSON.stringify(photo));
        },
        complete : function(data) {
        }
    });
}