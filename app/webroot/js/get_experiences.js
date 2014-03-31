////////////*FONCTIONS DE RECUPERATION DES DONNEES*////////////
//fonction permettant de recuperer les infos dans la base de donnees pour l'affichage de la carte
function get_map(data){
    
    //TODO supprimer get data
    data = $.parseJSON($("#data_input").val());
    
    //TODO on affiche le loader
    
    //si les parametres de filtre sont vides, on charge les infos de la carte par défaut
    if($.isEmptyObject(data)){
        var url = 'get_map_init';
    }
    else{
        var url = 'get_map';
    }

    $.ajax({
        type:"POST",
        url : url,
        data : data,
        dataType : 'json',
        success : function(data) {
                console.log(data);
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
function get_experiences(data){
    
    //TODO on affiche le loader
    
    //TODO supprimer get data
    data = $.parseJSON($("#data_input").val());

    $.ajax({
        type:"POST",
        url : 'get_experiences',
        data : data,
        dataType : 'html',
        success : function(data) {
                $('#experience_list').html(data);
        },
        error : function(data) {
                alert("Une erreur est survenue, veuillez réessayer dans quelques instants.");
        },
        complete : function(data) {
            //TODO on cache le loader
        }
    });
}