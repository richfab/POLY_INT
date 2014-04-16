<div id="search-filters">
    <div id="controller">
        <div class="control-map row" id="filter-search">
            
            <?php echo $this->element('filter_selects'); ?>
            
        </div>
    </div>
    <div class="row" id="filter-search-inputs">
        <div class="col-sm-3" id="ExperienceInputDiv">
            <input type="text" id="ExperienceInput" location-types="(regions)" name="input" class="form-control">
        </div>
        <input type="hidden" id="CityName" name="city_name"><input type="hidden" id="CityCountryId" name="country_id">
        <input type="hidden" id="CityLat"><input type="hidden" id="CityLon"><input type="hidden" id="CityCountryName">
        <div class="col-sm-3">    
            <input type="text" name="key_word" class="form-control" placeholder="Mots clé">
        </div>
        <div class="col-sm-3">    
            <input type="text" name="user_name" class="form-control" placeholder="Nom ou prénom">
        </div>
        <div class="col-sm-2">
            <button class="btn btn-blue form-control" onclick="search_button();"><span class="glyphicon glyphicon-search"></span> Rechercher</button>
        </div>
        <input type="hidden" name="date_min" id="date_min">
        <input type="hidden" name="date_max" id="date_max">
        <input type="hidden" name="result_limit" id="result_limit" value="0">
    </div>
</div>
    
<div id="list-search" class="experience-list">
    <ul id="ul-map">
        
    </ul>
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
        get_experiences_search();

        //fonction qui valide la soumission du formulaire lors de l'appui sur la touche entrée
        function pressEnter(evt) {
            var evt = (evt) ? evt : ((event) ? event : null);
            var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
            if ((evt.keyCode == 13) && (node.type == "text")) {
                if(evt.srcElement.name !== 'input'){ //si le champs n'est pas celui du lieu
                    search_button();
                }
            }
        }
        document.onkeypress = pressEnter;
    });
    
</script>