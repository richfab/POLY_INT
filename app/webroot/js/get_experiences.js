//variable globales
//l'objet json qui contient toutes les infos de la base de données recuperees par la requete xhtmlrequest
var resultat_json;

////////////*FONCTIONS DE RECUPERATION DES DONNEES*////////////
//fonction permettant de recuperer les infos dans la base de donnees pour l'affichage de la carte
function get_map(data){
    
    //TODO on affiche le loader

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
                resultat_json = data;
                console.log(resultat_json);
        },
        error : function(data) {
                alert("Une erreur est survenue, veuillez réessayer dans quelques instants.");
        },
        complete : function(data) {
            //TODO on cache le loader
        }
    });
}