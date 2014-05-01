<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div id="search-filters">
    <div class="row" id="filter-search-inputs">
        <div class="col-sm-5" id="ExperienceInputDiv">
            <input type="text" id="ExperienceInput" location-types="(regions)" name="input" class="form-control">
        </div>
        <input type="hidden" id="CityName" name="city_name"><input type="hidden" id="CityCountryId" name="country_id">
        <input type="hidden" id="CityLat"><input type="hidden" id="CityLon"><input type="hidden" id="CityCountryName">
        <!--utile pour le nombre de resultats a afficher-->
        <input type="hidden" name="offset" id="offset" value="0">
        <div class="col-sm-5" id="Recommendationtypes">
            <?php foreach ($recommendationtypes as $recommendationtype) : ?>
                <span class="glyphicon glyphicon-<?= $recommendationtype['Recommendationtype']['icon'];?> recommendationtype-icon selected" recommendationtype_id="<?= $recommendationtype['Recommendationtype']['id'];?>" data-toggle="tooltip" title="<?= $recommendationtype['Recommendationtype']['name']; ?>"></span>
            <?php endforeach;?>
        </div>
        <div class="col-sm-2">
            <button class="btn btn-blue form-control" onclick="new_search();"><span class="glyphicon glyphicon-search"></span> Rechercher</button>
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
                if($( '#Recommendationtypes > .recommendationtype-icon' ).size() === $( '#Recommendationtypes > .selected' ).size()){
                    $( '.recommendationtype-icon' ).removeClass('selected');
                    $(this).addClass('selected');
                }
                else{
                    $(this).toggleClass('selected');
                }
            });
        });
        
        //pour les tooltips
        $('.recommendationtype-icon').tooltip();
        
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