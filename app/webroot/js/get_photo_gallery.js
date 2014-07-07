/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function get_galleries(get_photo_gallery_url){
    //pour le chargement des galleries
    $( '.photo_gallery' ).each(function() {
        get_gallery($(this), get_photo_gallery_url);
    });
}

function get_gallery(photo_gallery_el, get_photo_gallery_url){
    
    var experience_id = photo_gallery_el.attr('experience_id');
    
    $.ajax({
        type:"GET",
        url : get_photo_gallery_url+'/'+experience_id,
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