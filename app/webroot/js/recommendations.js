function add_recommendation(save_button){
    
    save_button.attr('disabled',true);
    
    var recommendationInput = save_button.parent().parent().find('.RecommendationContent');
    
    var recommendation = {};
    recommendation.content = recommendationInput.val();
    recommendation.experience_id = recommendationInput.attr('experience_id');
    recommendation.recommendationtype_id = recommendationInput.attr('recommendationtype_id');
    
    console.log(recommendation);
    send_to_db(recommendation, save_button);
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
            save_button.attr('disabled',false);
            closeAddRecommendationForm();
            //TODO a ajaxer
            location.reload();
        }
    });
}

function closeAddRecommendationForm(){
    $('.addRecommendationForm').slideUp('fast');
    $( '.recommendationtype-icon-selectable' ).removeClass('selected');
}