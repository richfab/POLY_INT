/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

window.fbAsyncInit = function() {
    
    //charges FB api
    FB.init({
        appId      : '230656413765727',
        status     : true, // check login status
        xfbml      : false, //do not check for social plugins
        version    : 'v2.0'
    });
    
};

(function(d, s, id){
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {return;}
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/fr_FR/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

//this function prompts user to login on fb and opens fb album upload modal
function import_fb_album(experience_id){
    
    FB.login(function(){
        
        //once logged in, open fb album upload modal
        $('#import_fbalbum_modal').modal('show');
        
        //get albums
        FB.api("/me/albums",'get',{limit:100},
        function (response) {
            console.log(response);
            //create the list of user's fb albums
            if (response && !response.error) {
                for(var i in response.data){
                    var album = response.data[i];
                    var date = new Date(album.updated_time);
                    var updated_date = date.toLocaleDateString();
                    $('#loading_fb_album_list').hide();
                    $('#fb_album_list').append('<div class="row fb_album_el" onclick="get_fb_photos(\'' + album.id + '\',' + album.count + ', $(this),' + experience_id + ')"><div class="col-xs-12"><h4>' + album.name + ' (<span class="upload_progress"></span>' + album.count +')</h4><p><small>Modifié le ' + updated_date + '</small></p></div></div>');
                }
            }
        }
                );
        
    }, {scope: 'user_photos'});
}

//this function opens fb album upload modal and automatically updates the corresponding fb album
function update_fb_album(experience_id, fbalbum_id){
    
    FB.login(function(){
        
        $('#import_fbalbum_modal').modal('show');
        
        //get albums
        FB.api("/"+fbalbum_id,'get',{limit:100},
        function (response) {
            console.log(response);
            if (response && !response.error) {
                var album = response;
                var date = new Date(album.updated_time);
                var updated_date = date.toLocaleDateString();
                $('#loading_fb_album_list').hide();
                $('#fb_album_list').append('<div class="row fb_album_el"><div class="col-xs-12"><h4>' + album.name + ' (<span class="upload_progress"></span>' + album.count +')</h4><p><small>Modifié le ' + updated_date + '</small></p></div></div>');
                var fb_album_el = $('.fb_album_el');
                //starts photo upload
                get_fb_photos(album.id, album.count, fb_album_el, experience_id);
            }
        }
                );
        
    }, {scope: 'user_photos'});
}

//this function gets the photos of the album selected by user
function get_fb_photos(album_id, album_count, fb_album_el, experience_id){
    
    $('.upload_progress').html('0/');
    
    fb_album_el.css('background-color','initial');
    fb_album_el.siblings().fadeOut();
    
    //progress bar
    $('#fbalbum_progress').css('visibility','visible');
    $('.progress-bar').attr('aria-valuemax',album_count);
    update_bar_progress(0,album_count);
    
    FB.api("/" + album_id + "/photos",'get',{limit:100},
    function(response){
        handle_response_photos(response, experience_id, album_id);
    }
            );
}

//this function uses facebook cursor to load more photos
function get_more_fb_photos(cursor, experience_id, album_id){
    FB.api(cursor,'get',{limit:100},
    function(response){
        handle_response_photos(response, experience_id, album_id);
    }
            );
}

//this function goes through the photos and calls itself is cursor is found in paging
function handle_response_photos(response, experience_id, album_id){
    console.log(response);
    if (response && !response.error) {
        for(var i in response.data){
            var photo = response.data[i];
            handle_photo(photo, experience_id, album_id);
        }
        if(response.paging.next){
            get_more_fb_photos(response.paging.next, experience_id, album_id);
        }
    }
}

//this function uploads a photo and progress
function handle_photo(photo, experience_id, album_id){
    
    $.ajax({
        type:"PUT",
        url : '../photos/add_fb_photo',
        data : {
            fb_id : photo.id,
            source : photo.source,
            picture : photo.picture,
            caption : photo.name,
            fbalbum_id : album_id,
            experience_id : experience_id
        },
        dataType : 'json',
        success : function(data) {
            
            var current_progress = parseInt($('.progress-bar').attr('aria-valuenow'))+1;
            var max_progress = $('.progress-bar').attr('aria-valuemax');
            update_bar_progress(current_progress, max_progress);
            
            //if 100%
            if(current_progress/max_progress === 1){
                $('#import_fbalbum_modal').modal('hide');
                reset_fbalbum_upload_modal();
                //reloads all galleries
                get_galleries('../photos/get_photo_gallery');
                //reloads all fbalbum import buttons
                get_fbalbum_import_buttons();
            }
        },
        error : function(data) {
            console.log('error trying to insert : '+JSON.stringify(photo));
        },
        complete : function(data) {
        }
    });
}

//current progress ex: 150
//mac progress ex: 300
function update_bar_progress(current_progress, max_progress){
    var ratio_progress = parseInt(current_progress / max_progress * 100);
    $('.upload_progress').html(current_progress+'/');
    $('.progress-bar').html(ratio_progress + '%');
    $('.progress-bar').css('width',ratio_progress + '%');
    $('.progress-bar').attr('aria-valuenow',current_progress);
}

//this function empties fb album upload modal and hides progress bar
function reset_fbalbum_upload_modal(){
    $('#fb_album_list').empty();
    $('#fbalbum_progress').css('visibility','hidden');
    update_bar_progress(0,1);
    $('#loading_fb_album_list').show();
}

function get_fbalbum_import_buttons(){
    //pour le chargement des fbalbum_import_buttons
    $( '.fbalbum_import_button' ).each(function() {
        get_fbalbum_import_button($(this));
    });
}

function get_fbalbum_import_button(fbalbum_import_button_el){
    
    var experience_id = fbalbum_import_button_el.attr('experience_id');
    
    $.ajax({
        type:"GET",
        url : "../photos/get_fbalbum_import_button/"+experience_id,
        dataType : 'html',
        success : function(data) {
            fbalbum_import_button_el.html(data);
        },
        error : function(data) {
        },
        complete : function(data) {
        }
    });
}