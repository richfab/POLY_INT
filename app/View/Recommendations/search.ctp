<?php 
//    define current city for user
    if($experience){
        $city_name = $experience['City']['name'];
        $country_id = $experience['City']['country_id'];
        $city_name_and_country_id = $city_name . ", " . $country_id;
    } else {
        $city_name = $country_id = $city_name_and_country_id = "";
    }
?>

<div id="search-filters">
    <div class="row" id="filter-search-inputs">
        <div class="col-sm-5" id="ExperienceInputDiv">
            <input type="text" id="ExperienceInput" location-types="(regions)" name="input" class="form-control" value="<?= $city_name_and_country_id;?>">
        </div>
        <input type="hidden" id="CityName" name="city_name" value="<?= $city_name;?>">
        <input type="hidden" id="CityCountryId" name="country_id" value="<?= $country_id;?>">
        <input type="hidden" id="CityLat"><input type="hidden" id="CityLon"><input type="hidden" id="CityCountryName">
        <!--utile pour le nombre de resultats a afficher-->
        <input type="hidden" name="offset" id="offset" value="0">
        <div class="col-sm-5" id="Recommendationtypes">
            <div class="row">
            <?php foreach ($recommendationtypes as $recommendationtype) : ?>
                <div class="col-xs-2">
                    <span class="glyphicon glyphicon-<?= $recommendationtype['Recommendationtype']['icon'];?> recommendationtype-icon selected" recommendationtype_id="<?= $recommendationtype['Recommendationtype']['id'];?>" data-toggle="tooltip" title="<?= __($recommendationtype['Recommendationtype']['name']); ?>"></span>
                </div>
            <?php endforeach;?>
            </div>
        </div>
        <div class="col-sm-2">
            <button class="btn btn-blue form-control" onclick="new_search();"><span class="glyphicon glyphicon-search"></span> <?= __('Rechercher');?></button>
        </div>
    </div>
</div>

<div id="recommendation-list">
    
</div>
    
    
<script type="text/javascript">
    
    $( function() {
        
        //pour le changement d'etat des boutons de categorie de recommendation
        $( '.recommendationtype-icon' ).each(function() {
            $(this).on('click',function(){
                //si toutes les catgeories sont déjà cochées, on ne coche que celle qui a été cliquée
                if($( '#Recommendationtypes .recommendationtype-icon' ).size() === $( '#Recommendationtypes .selected' ).size()){
                    $( '.recommendationtype-icon' ).removeClass('selected');
                    $(this).addClass('selected');
                }
                else{
                    $(this).toggleClass('selected');
                }
            });
        });
        
        //on lance la recherche au chargement
        get_recommendations();
        
        //fonction qui valide la soumission du formulaire lors de l'appui sur la touche entrée
        function pressEnter(evt) {
            var evt = (evt) ? evt : ((event) ? event : null);
            var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
            if ((evt.keyCode == 13) && (node.type == "text")) {
                if(evt.srcElement.name !== 'input'){ //si le champs n'est pas celui du lieu
                    new_search();
                }
            }
        }
        document.onkeypress = pressEnter;
    });
    
</script>