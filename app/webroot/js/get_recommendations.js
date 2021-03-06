//recuperer les experiences dans la base de donnees pour l'affichage de la liste
function get_recommendations(offset){

    start_logo_fly();

    if(!offset){
        offset = 0;
    }

    var filter = get_filter_params();
    filter.offset = offset;

    $.ajax({
        type:"POST",
        url : 'get_recommendations',
        data : filter,
        dataType : 'html',
        success : function(data) {
            $('#recommendation-list').append(data);
            masonry_init();
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
//            $('.recommendation-text').readmore();
        }
    });
}

function masonry_init(){
    var $container = $('.recommendation-masonry');
    $container.masonry({
        itemSelector: '.recommendation-item',
        gutter: 0
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

    $.extend(filter,city_name,country_id,recommendationtypes);
    
    return filter;
}