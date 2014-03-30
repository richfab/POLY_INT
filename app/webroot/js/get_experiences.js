////////////*FONCTIONS DE RECUPERATION DES DONNEES*////////////
//fonction permettant de recuperer les infos dans la base de donnees pour l'affichage de la carte
function get_map(data){
    
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
                //une fois pret, on parse tous les resulats
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