function add_recommendations(save_button){
    
    $(save_button).attr('disabled',true);
    
    var collection = $(save_button).siblings('.input-group').children('.recommendation-content');
    
    collection.each(function(index){
        var recommendation = {};
        recommendation.content = $(this).val();
        recommendation.id = $(this).attr('recommendation_id');
        recommendation.experience_id = $(this).attr('experience_id');
        recommendation.recommendationtype_id = $(this).attr('recommendationtype_id');
        
        console.log(recommendation);
        
        var islast = (collection.size()-1 === index) ? true : false;
        send_to_db(recommendation, save_button, islast);
    });
}

function send_to_db(recommendation, save_button, islast){
    
    $.ajax({
        type:"PUT",
        url : '../recommendations/add_recommendation',
        data : recommendation,
        dataType : 'json',
        success : function(data) {
        },
        error : function(data) {
            console.log('error trying to insert : '+JSON.stringify(recommendation));
        },
        complete : function(data) {
            if(islast){
                $(save_button).attr('disabled',false);
            }
        }
    });
    
}