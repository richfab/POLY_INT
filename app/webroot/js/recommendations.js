function add_recommendation(save_button){
    
    save_button.attr('disabled',true);
    
    var recommendationInput = save_button.parent().parent().find('.RecommendationContent');
    
	var experience_id = recommendationInput.attr('recommendation_id');
	
	var url = '../recommendations';
	if(experience_id){
		url = url + '/' + experience_id;
	}
	url = url + '.json';
	
    var recommendation = {};
    recommendation.content = recommendationInput.val();
    recommendation.experience_id = recommendationInput.attr('experience_id');
    recommendation.recommendationtype_id = recommendationInput.attr('recommendationtype_id');
    
    $.ajax({
        type:"POST",
        url : url,
        data : recommendation,
        success : function(data) {
            closeAddRecommendationForm();
            //TODO a ajaxer
            location.reload();
        },
        error : function(data) {
            console.log('error trying to insert/update : '+JSON.stringify(recommendation));
        },
        complete : function(data) {
            save_button.attr('disabled',false);
        }
    });
}

function update_recommendation(update_button,id,recommendationtype_id){
	
	var content = update_button.siblings(".recommendation-text").text();
	var addRecommendation = update_button.parents(".recommendations").siblings(".addRecommendation");
	var textarea = addRecommendation.find("textarea");
	
	addRecommendation.find("span[recommendationtype_id="+ recommendationtype_id +"]").click();
	textarea.val(content);
	textarea.attr('recommendation_id',id);
}

function delete_recommendation(id){
	
	if(confirm("Es-tu sûr de vouloir supprimer ce bon plan ?")){
	    $.ajax({
	        type:"DELETE",
	        url : '../recommendations/'+ id +'.json',
	        success : function(data) {
				location.reload();
	        }
	    });
	}
}

function closeAddRecommendationForm(){
    $('.addRecommendationForm').slideUp('fast');
	$('.addRecommendationForm').find('textarea').each(function(){
		$(this).val('');
	});
    $( '.recommendationtype-icon-selectable' ).removeClass('selected');
}