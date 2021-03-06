////////////*FONCTIONS DE RECUPERATION DES DONNEES*////////////

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
    start_logo_fly();


    $.ajax({
        type:"POST",
        url : 'get_map',
        data : filter,
        dataType : 'json',
        success : function(data) {
            update_map(data);
            update_number_of_experiences(data);
        },
        error : function(data) {
            //alert("Une erreur est survenue, veuillez réessayer dans quelques instants.");
        },
        complete : function(data) {
            //on cache le loader du milieu de la carte
            stop_logo_fly();
        }
    });
}

function update_number_of_experiences(data){

    var total = 0;

    if(data.cities){
        var experienceNumbers = data.cities.experienceNumbers;
        for(var i in experienceNumbers){
            total += parseInt(experienceNumbers[i]);
        }
    }

    $({someValue: 0}).animate({someValue: total}, {
        duration: 1000,
        easing:'swing', // can be anything
        step: function() { // called on every step
            // Update the element's text with rounded-up value:
            $('#numberOfExperiencesSpan').text(Math.ceil(this.someValue));
        }
    });

}

//recuperer les experiences dans la base de donnees pour l'affichage de la liste
function get_experiences(_view_to_render, _filter, offset){

    if(!offset){
        offset = 0;
    }

    var filter = get_filter_params();
    filter.offset = offset;

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

    start_logo_fly();

    $.ajax({
        type:"POST",
        url : 'get_experiences',
        data : filter,
        dataType : 'html',
        success : function(data) {
            $('.experience-list').append(data);
            open_list_experiences();
        },
        error : function(data) {
            //alert("Une erreur est survenue, veuillez réessayer dans quelques instants.");
        },
        complete : function(data) {
            // on cache le loader
            stop_logo_fly();
        }
    });
    console.log(filter);
}

//ouvre la liste des experiences ou un modal selon la taille de l'écran
function open_list_experiences(){
    if ($(window).width() < 768) {
        // do something for small screens
        $('#list-map-modal').modal('show');
    }
    else{
        $('#list-map').slideDown(300);
    }
}

//réinitialise le offset, vide la liste des experiences et lance la recherche d'experiences
function new_search(view_to_render,filter){
    //on vide la liste des resultats
    $('.experience-list').empty();

    //on remet le offset a 0
    if(filter){
        filter.offset = "0";
    }

    //on lance la recherche
    get_experiences(view_to_render,filter);
}

//recupere les parametres de filtres
function get_filter_params(){

    var filter = {};

    if($('#department_id').attr('value') !== '0'){
        var deparment_id = $.parseJSON('{"department_id":"'+$('#department_id').attr('value')+'"}');
    }
    if($('#motive_id').attr('value') !== '0'){
        var motive_id = $.parseJSON('{"motive_id":"'+$('#motive_id').attr('value')+'"}');
    }   
    if($('#school_id').attr('value') !== '0'){
        var school_id = $.parseJSON('{"school_id":"'+$('#school_id').attr('value')+'"}');
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
    if($('#period_id').attr('date-min').length !== 0 && $('#period_id').attr('date-min') !== ''){
        var date_min = $.parseJSON('{"date_min":"'+$('#period_id').attr('date-min')+'"}');
    }
    if($('#period_id').attr('date-max').length !== 0 && $('#period_id').attr('date-max') !== ''){
        var date_max = $.parseJSON('{"date_max":"'+$('#period_id').attr('date-max')+'"}');
    }
    if($('#period_id').attr('value') !== '0'){
        var period_id = $.parseJSON('{"period_id":"'+$('#period_id').attr('value')+'"}');
    }

    $.extend(filter,deparment_id,motive_id,school_id,key_word,city_name,country_id,user_name,date_min,date_max,period_id);

    var filter_cookie = {};
    filter_cookie["department_id"] = $('#department_id').attr('value');
    filter_cookie["motive_id"] = $('#motive_id').attr('value');
    filter_cookie["school_id"] = $('#school_id').attr('value');
    filter_cookie["period_id"] = $('#period_id').attr('value');
    createCookiesFromFilter(filter_cookie);

    return filter;
}

function update_selects_from_filter(filter){

//    //if no period was selected, define now as default
//    if(filter.period_id == undefined){
//        $('#period_id > [value=3]').click();
//    }
//    else{
        $('#department_id > [value='+ filter.department_id +']').click();
        $('#motive_id > [value='+ filter.motive_id +']').click();
        $('#school_id > [value='+ filter.school_id +']').click();
        $('#period_id > [value='+ filter.period_id +']').click();
//    }
}