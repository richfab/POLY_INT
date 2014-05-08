//recuperer les experiences dans la base de donnees pour l'affichage de la liste
function get_recommendations(){
    
    var filter = get_filter_params();
    
    start_logo_fly();
    
    $.ajax({
        type:"POST",
        url : 'get_recommendations',
        data : filter,
        dataType : 'html',
        success : function(data) {
            $('#recommendation-list').append(data);
        },
        error : function(data) {
            //alert("Une erreur est survenue, veuillez réessayer dans quelques instants.");
        },
        complete : function(data) {
            stop_logo_fly();
            
            //incremente le offset pour le 'plus' de la liste des resultats
            $('input[name=offset]').val($('input[name=offset]').val()*1+20);
            
            //pour les tooltips
            if (!Modernizr.touch) {
                $('.recommendationtype-icon').tooltip(); 
            }
            //pour le readmore
            $('.recommendation-text').readmore();
        }
    });
}

//réinitialise le offset, vide la liste des experiences et lance la recherche d'experiences
function new_search(filter){
    //on vide la liste des resultats
    $('#recommendation-list').empty();
    
    //on remet le offset a 0
    $('input[name=offset]').val(0);
    if(filter){
        filter.offset = "0";
    }
    
    //on lance la recherche
    get_recommendations(filter);
}

//recupre les parametres de filtres et le offset
function get_filter_params(){
    
    var filter = {};
    
    var recommendationtypelist = [0];
    $('.recommendationtype-icon').each(function(){
        if($(this).hasClass('selected')){
            recommendationtypelist.push($(this).attr('recommendationtype_id'));
        }
    });
    var recommendationtypes = {"recommendationtypes":recommendationtypelist};
    
    if($('input[name=city_name]').length !== 0 && $('input[name=city_name]').val() !== ''){
        var city_name = $.parseJSON('{"city_name":"'+$('input[name=city_name]').val()+'"}');
    }
    if($('input[name=country_id]').length !== 0 && $('input[name=country_id]').val() !== ''){
        var country_id = $.parseJSON('{"country_id":"'+$('input[name=country_id]').val()+'"}');
    }
    if($('input[name=offset]').length !== 0){
        var offset = $.parseJSON('{"offset":"'+$('input[name=offset]').val()+'"}');
    }
    
    $.extend(filter,city_name,country_id,offset,recommendationtypes);
    console.log(filter);
    return filter;
}