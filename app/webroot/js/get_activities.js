//recuperer les activites dans la base de donnees pour l'affichage de la liste
function get_activities(offset){
    
    start_logo_fly();
    
    if(!offset){
        offset = 0;
    }
    
    $.ajax({
        type:"POST",
        url : 'activities/get_activities',
        data : {
            offset: offset
        },
        dataType : 'html',
        success : function(data) {
            $('#activities').append(data);
        },
        complete : function(data) {
            stop_logo_fly();
            
            //pour les tooltips
            if (!Modernizr.touch) {
                $('.recommendationtype-icon').tooltip(); 
            }
            //pour le readmore
            $('.recommendation-text').readmore();
        }
    });
}