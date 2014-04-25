function add_recommendation(save_button){
    
    $(save_button).attr('disabled',true);
    
    var recommendation = {};
    recommendation.content = $(save_button).siblings('.recommendation-content').val();
    recommendation.experience_id = $(save_button).siblings('.recommendation-experience_id').val();
    recommendation.recommendationtype_id = $(save_button).siblings('.recommendation-recommendationtype_id').val();
    
    console.log(recommendation);
    if(recommendation.content !== ''){
        send_to_db(recommendation, save_button);
    }
    else{
        $(save_button).attr('disabled',false);
    }
}

function send_to_db(recommendation, save_button){
    
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
            $(save_button).attr('disabled',false);
        }
    });
    
}