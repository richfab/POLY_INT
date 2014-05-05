//tableau de compteurs pour l'affichage temporisé du logo de chargement (loader) utile pour la carte
var timeoutID = [];

////////////*FONCTIONS DE RECUPERATION DES DONNEES*////////////

//recupere les infos dans la base de donnees pour l'affichage de la carte au chargement de la page explore
function get_map_init(){
    fetch_map_values('{}');
}

//recupere les infos dans la base de donnees pour l'affichage de la carte au changement de filtres
function get_map(){
    var filter = get_filter_params();
    fetch_map_values(filter);
    
    //si une region (ville ou pays) est selectionnee on raffraichit aussi la liste correspondante
    if(selected_region.type){
        //on ajoute le parametre country_id
        var country_or_city_json = $.parseJSON('{"'+selected_region.type+'":"'+selected_region.id+'"}');
        //on join les trois tableaux de parametres
        $.extend(filter,country_or_city_json);
        new_search('get_experiences_map',filter);
    }
}

//recupere les infos dans la bdd a partir des paramêtres de filtre
function fetch_map_values(filter){
    
    //on affiche le loader au milieu de la carte
    show_loader_map();
    
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
            //alert("Une erreur est survenue, veuillez réessayer dans quelques instants.");
        },
        complete : function(data) {
            //on cache le loader du milieu de la carte
            hide_loader_map();
        }
    });
}

//recuperer les experiences dans la base de donnees pour l'affichage de la liste
function get_experiences(_view_to_render, _filter){
    
    //on recupere les params initiaux
    var filter = get_filter_params();
    
    //si une region (ville ou pays) est selectionnee on raffraichit aussi la liste correspondante
    if(_view_to_render === 'get_experiences_map' && selected_region.type){
        //on ajoute le parametre country_id
        var country_or_city_json = $.parseJSON('{"'+selected_region.type+'":"'+selected_region.id+'"}');
        //on join les deux tableaux de parametres
        $.extend(filter,country_or_city_json);
    }
    
    //on ajoute le parametre de vue a rendre
    var view_to_render = $.parseJSON('{"view_to_render":"'+_view_to_render+'"}');
    //on join les trois tableaux de parametres
    $.extend(filter,_filter,view_to_render);
    
    //on affiche le loader pour la page search
    $('#list-search').append('<div class="loader-list"><img height="40px" src="/explorer/img/loader.GIF"/></div>');
    //on affiche le loader au milieu de la carte
    show_loader_map();
    
    $.ajax({
        type:"POST",
        url : 'get_experiences',
        data : filter,
        dataType : 'html',
        success : function(data) {
            $('.experience-list').append(data);
            $('#list-map').slideDown(300);
        },
        error : function(data) {
            //alert("Une erreur est survenue, veuillez réessayer dans quelques instants.");
        },
        complete : function(data) {
            // on cache le loader
            hide_loader_map();
            $('.loader-list').remove();
            
            //incremente le offset pour le 'plus' de la liste des resultats
            $('input[name=offset]').val($('input[name=offset]').val()*1+20);
        }
    });
    console.log(filter);
}

function hide_loader_map(){
    //on annule le compteur pour le cas ou la réponse à été plus rapide que le délais pour afficher le loader
    window.clearTimeout(timeoutID.pop());
    //on cache le loader
    $('#loader-map').hide();
}

//affiche un gif de chargement au milieu de la carte après un certain délais
function show_loader_map(){
    //on place le loader a droite du logo du site
    $('#loader-map').css('top',$('.navbar-brand').offset().top+14);
    $('#loader-map').css('left',$('.navbar-brand').offset().left+$('.navbar-brand').width()+20);
    //on ajoute un compteur au tableau de compteurs
    timeoutID.push(window.setTimeout(function(){
        $('#loader-map').show(); 
    }, 500));
}

//réinitialise le offset, vide la liste des experiences et lance la recherche d'experiences
function new_search(view_to_render,filter){
    //on vide la liste des resultats
    $('.experience-list').empty();
    
    //on remet le offset a 0
    $('input[name=offset]').val(0);
    if(filter){
        filter.offset = "0";
    }
    
    //on lance la recherche
    get_experiences(view_to_render,filter);
}

//recupre les parametres de filtres et le offset
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
    if($('input[name=user_name]').length !== 0 && $('input[name=user_name]').val() !== ''){
        var user_name = $.parseJSON('{"user_name":"'+$('input[name=user_name]').val()+'"}');
    }
    if($('input[name=date_min]').length !== 0 && $('input[name=date_min]').val() !== ''){
        var date_min = $.parseJSON('{"date_min":"'+$('input[name=date_min]').val()+'"}');
    }
    if($('input[name=date_max]').length !== 0 && $('input[name=date_max]').val() !== ''){
        var date_max = $.parseJSON('{"date_max":"'+$('input[name=date_max]').val()+'"}');
    }
    if($('input[name=offset]').length !== 0){
        var offset = $.parseJSON('{"offset":"'+$('input[name=offset]').val()+'"}');
    }
    
    $.extend(filter,deparment_id,motive_id,school_id,key_word,city_name,country_id,user_name,date_min,date_max,offset);
    
    return filter;
}