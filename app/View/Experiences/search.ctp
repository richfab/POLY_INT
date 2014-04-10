<div id="controller">
    <div class="control-map" id="filter-search">
        <select id="department_id" class="select-dropdown">
            <option value="-1" selected>Spécialité</option>
            <?php foreach ($departments as $key => $department):?>
                <option value="<?= $key; ?>"><?= $department; ?></option>
            <?php endforeach;?>
        </select>
        <select id="motive_id" class="select-dropdown">
            <option value="-1" selected>Motif</option>
            <?php foreach ($motives as $key => $motive):?>
                <option value="<?= $key; ?>"><?= $motive; ?></option>
            <?php endforeach;?>
        </select>
        <select id="school_id" class="select-dropdown">
            <option value="-1" selected>Ecole</option>
            <?php foreach ($schools as $key => $school):?>
                <option value="<?= $key; ?>"><?= $school; ?></option>
            <?php endforeach;?>
        </select>
        <select id="period_id" class="select-dropdown last">
            <option value="-1" selected>Période</option>
            <option value="1">< 5 ans</option>
            <option value="2">< 1 an</option>
            <option value="3">Maintenant</option>
            <option value="4">A venir</option>
        </select>
        <input type="text" name="key_word" class="form-control" style="width: initial;display: inline-block" placeholder="Mot clé">
        <button class="btn btn-blue" onclick="get_experiences_search();">Rechercher</button>
    </div>
</div>

<script type="text/javascript">
            
$( function() {

    $( '.select-dropdown' ).each(function() {
        $( this ).dropdown( {
            gutter : 5,
            stack : false,
            slidingIn : 100
        });
    });

});

</script>

<div id="list-search" class="experience-list">
    <ul id="ul-map">

    </ul>
</div>