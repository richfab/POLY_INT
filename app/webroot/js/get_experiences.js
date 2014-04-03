////////////*FONCTIONS DE RECUPERATION DES DONNEES*////////////
//fonction permettant de recuperer les infos dans la base de donnees pour l'affichage de la carte
function get_map_init(){
    fetch_map_values('{}');
}

function get_map(){
    var filter = get_filter_params();
    fetch_map_values(filter);
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
                console.log(url+"-->"+data);
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

//fonction permettant de recuperer les experiences dans la base de donnees pour l'affichage de la liste
function get_experiences(filter){
    
    //TODO on affiche le loader

    $.ajax({
        type:"POST",
        url : 'get_experiences',
        data : filter,
        dataType : 'html',
        success : function(data) {
                $('#list-map').html(data);
        },
        error : function(data) {
                alert("Une erreur est survenue, veuillez réessayer dans quelques instants.");
        },
        complete : function(data) {
            //TODO on cache le loader
        }
    });
}

function get_filter_params(){
    return $.parseJSON($("#data_input").val());
}