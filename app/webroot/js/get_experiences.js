////////////*FONCTIONS DE RECUPERATION DES DONNEES*////////////
//fonction permettant de recuperer les infos dans la base de donnees pour l'affichage de la carte
function get_map_init(){
    fetch_map_values('{}');
}

function get_map(){
    var filter = get_filter_params();
    fetch_map_values(filter);
    //TODO actualiser la liste si le pays est selectionne
}

function fetch_map_values(filter){
    
    //TODO on affiche le loader
    
    //si les parametres de filtre sont vides, on charge les infos de la carte par défaut
    if($.isEmptyObject(filter)){
        var url = 'get_map_init';
    }
    else{
        var url = 'get_map';
    }
    
    $.ajax({
        type:"POST",
        url : url,
        data : filter,
        dataType : 'json',
        success : function(data) {
            update_map(data);
        },
        error : function(data) {
            alert("Une erreur est survenue, veuillez réessayer dans quelques instants.");
        },
        complete : function(data) {
            //TODO on cache le loader
        }
    });
}

//fonction permettant de recuperer les experiences dans la base de donnees pour l'affichage de la liste sur la carte
function get_experiences(filter){
    
    //TODO on affiche le loader
    
    $.ajax({
        type:"POST",
        url : 'get_experiences',
        data : filter,
        dataType : 'html',
        success : function(data) {
            $('.experience-list').html(data);
            $('.experience-list').slideDown(300);
        },
        error : function(data) {
            alert("Une erreur est survenue, veuillez réessayer dans quelques instants.");
        },
        complete : function(data) {
            //TODO on cache le loader
        }
    });
}

function get_experiences_search(){
    //on recupere les params initiaux
    var filter = get_filter_params();
    //on ajoute le parametre de vue a rendre
    var view_to_render = $.parseJSON('{"view_to_render":"get_experiences_search"}');
    //on join les trois tableaux de parametres
    $.extend(filter,view_to_render);
    
    get_experiences(filter);
}

function get_filter_params(){
    
    var filter = {};
    
    if($('input[name=department_id]').val() !== '-1'){
        var deparment_id = $.parseJSON('{"department_id":"'+$('input[name=department_id]').val()+'"}');
    }
    if($('input[name=motive_id]').val() !== '-1'){
        var motive_id = $.parseJSON('{"motive_id":"'+$('input[name=motive_id]').val()+'"}');
    }   
    if($('input[name=school_id]').val() !== '-1'){
        var school_id = $.parseJSON('{"school_id":"'+$('input[name=school_id]').val()+'"}');
    }
    if($('input[name=key_word]').length !== 0 && $('input[name=key_word]').val() !== ''){
        var key_word = $.parseJSON('{"key_word":"'+$('input[name=key_word]').val()+'"}');
    }
    if($('input[name=city_name]').length !== 0 && $('input[name=city_name]').val() !== ''){
        var city_name = $.parseJSON('{"city_name":"'+$('input[name=city_name]').val()+'"}');
    }
    if($('input[name=country_id]').length !== 0 && $('input[name=country_id]').val() !== ''){
        var country_id = $.parseJSON('{"country_id":"'+$('input[name=country_id]').val()+'"}');
    }
    
    $.extend(filter,deparment_id,motive_id,school_id,key_word,city_name,country_id);
    
    return filter;
}