<div id="controller">
    <div class="control-map" id="filter-search">
        <select id="department_id" class="select-dropdown">
            <option value="-1" selected>Spécialité</option>
            <option value="0" option-title="Spécialité">Toutes</option>
            <?php foreach ($departments as $key => $department):?>
            <option value="<?= $key; ?>"><?= $department; ?></option>
            <?php endforeach;?>
        </select>
        <select id="motive_id" class="select-dropdown">
            <option value="-1" selected>Motif</option>
            <option value="0" option-title="Motif">Tous</option>
            <?php foreach ($motives as $key => $motive):?>
            <option value="<?= $key; ?>"><?= $motive; ?></option>
            <?php endforeach;?>
        </select>
        <select id="school_id" class="select-dropdown">
            <option value="-1" selected>École</option>
            <option value="0" option-title="École">Toutes</option>
            <?php foreach ($schools as $key => $school):?>
            <option value="<?= $key; ?>"><?= $school; ?></option>
            <?php endforeach;?>
        </select>
        <select id="period_id" class="select-dropdown last">
            <option value="-1" selected>Période</option>
            <option value="0" option-title="Période">Toutes</option>
            <option value="1">< 5 ans</option>
            <option value="2">< 1 an</option>
            <option value="3">Maintenant</option>
            <option value="4">A venir</option>
        </select>
    </div>
</div>
<input type="text" id="ExperienceInput" location-types="(regions)" name="input" class="form-control" style="width: initial;display: inline-block">
<input type="text" id="CityName" name="city_name" hidden="true"><input type="text" id="CityCountryId" name="country_id" hidden="true">
<input type="text" id="CityLat" hidden="true"><input type="text" id="CityLon" hidden="true"><input type="text" id="CityCountryName" hidden="true">
<input type="text" name="key_word" class="form-control" style="width: initial;display: inline-block" placeholder="Mots clé">
<button class="btn btn-blue" onclick="get_experiences_search();">Rechercher</button>
<script type="text/javascript">
    
    $( function() {
        $( '.select-dropdown' ).each(function() {
            $( this ).dropdown( {
                gutter : 5,
                stack : false,
                slidingIn : 100
            });
        });
        //on lance la recherche au chargement
        get_experiences_search();
    });
    
</script>
    
<div id="list-search" class="experience-list">
    <ul id="ul-map">
        
    </ul>
</div>