<div id="world-map" style="width: 100%; height: 600px; postion:relative; bottom:20px; top:100px;"></div>
                
<div id="controller">
    <div class="control-map" id="filter-map">
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
    </div>
    <div id="list-map" class="experience-list" style="display:none">
        
    </div>
</div>
<input type="text" id="data_input" value='{}' style="display:none">
<script type="text/javascript">
            
$( function() {

    $( '.select-dropdown' ).each(function() {
        $(this).dropdown( {
            gutter : 5,
            stack : false,
            slidingIn : 100
        });
    });
    
    $( '.cd-dropdown li' ).click(function() {
        get_map();
    });
    
    $( '.cd-dropdown > span' ).click(function(){
        $('.experience-list').slideUp(300);
//        $(this).html('<span>coucou</span>');
    });

});

</script>