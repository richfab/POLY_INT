/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function get_galleries(get_photo_gallery_url){
    //pour le chargement des galleries
    $( '.photo_gallery' ).each(function() {
        get_gallery($(this), get_photo_gallery_url, 8);
    });
}

function get_gallery(photo_gallery_el, get_photo_gallery_url, limit){
    
    var experience_id = photo_gallery_el.attr('experience_id');
    
    //if no limit was defined, load all photos
    if(!limit){
        limit = '';
    }
    
    //si la largeur de l'ecran est inferieure a 768px (considéré comme un mobile)
    var size = ($(window).width() < 768) ? 'S' : 'M';
    
    $.ajax({
        type:"GET",
        url : get_photo_gallery_url+'/'+experience_id+'/'+size+'/'+limit,
        dataType : 'html',
        success : function(data) {
            photo_gallery_el.html(data);
            photo_gallery_el.children('#blueimp-gallery').data('useBootstrapModal', false);
        },
        error : function(data) {
        },
        complete : function(data) {
        }
    });
}

function delete_photo(photo_gallery_el, photo_id){
    
    $.ajax({
        type:"POST",
        url : '../photos/delete',
        data : {photo_id : photo_id},
        success : function(data) {
            get_gallery(photo_gallery_el, '../photos/get_photo_gallery');
        },
        error : function(data) {
        },
        complete : function(data) {
        }
    });
}

function delete_album(experience_id){
    
    $.ajax({
        type:"POST",
        url : '../photos/delete_album',
        data : {experience_id : experience_id},
        success : function(data) {
        },
        error : function(data) {
        },
        complete : function(data) {
            get_galleries('../photos/get_photo_gallery');
            get_fbalbum_import_buttons();
        }
    });
}