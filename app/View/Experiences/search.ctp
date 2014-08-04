<div id="search-filters">
    
    <?php echo $this->element('filter_selects'); ?>        
        
    <div class="row" id="filter-search-inputs">
        <div class="col-sm-3" id="ExperienceInputDiv">
            <input type="text" id="ExperienceInput" location-types="(regions)" name="input" class="form-control">
        </div>
        <input type="hidden" id="CityName" name="city_name"><input type="hidden" id="CityCountryId" name="country_id">
        <input type="hidden" id="CityLat"><input type="hidden" id="CityLon"><input type="hidden" id="CityCountryName">
        <div class="col-sm-3">    
            <input type="text" name="key_word" class="form-control" placeholder="<?= __('Mots clé');?>">
        </div>
        <div class="col-sm-3">    
            <input type="text" name="user_name" class="form-control" placeholder="<?= __('Nom ou prénom');?>">
        </div>
        <div class="col-sm-3">
            <button id="validatePlaceButton" class="btn btn-blue form-control" onclick="new_search('get_experiences_search');"><span class="glyphicon glyphicon-search"></span> <?= __('Rechercher');?></button>
        </div>
        <input type="hidden" name="date_min" id="date_min">
        <input type="hidden" name="date_max" id="date_max">
    </div>
</div>
    
<div id="list-search" class="experience-list">
    
</div>


<script type="text/javascript">
    
    $( function() {
        $( '.select-dropdown' ).each(function() {
            $( this ).dropdown( {
                gutter : 5,
                stack : false,
                slidingIn : 50
            });
        });
        //on lance la recherche au chargement
        update_selects_from_filter(createFilterFromCookies());
        get_experiences('get_experiences_search', createFilterFromCookies());

        //fonction qui valide la soumission du formulaire lors de l'appui sur la touche entrée
        function pressEnter(evt) {
            var evt = (evt) ? evt : ((event) ? event : null);
            var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
            if ((evt.keyCode == 13) && (node.type == "text")) {
                if(evt.srcElement.name !== 'input'){ //si le champs n'est pas celui du lieu
                    new_search('get_experiences_search');
                }
            }
        }
        document.onkeypress = pressEnter;
    });
    
</script>