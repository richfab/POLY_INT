<div id="world-map" style="width: 100%; height: 600px; postion:relative; bottom:20px; top:100px;"></div>
                
<div id="controller">
    <div id="control-map">
        <select id="cd-dropdown" class="">
            <option value="-1" selected>Spécialité</option>
            <option value="1">Informatique</option>
            <option value="2">Electronique</option>
            <option value="3">Matériaux</option>
        </select>
        <select id="cd-dropdown2" class="">
            <option value="-1" selected>Motif</option>
            <option value="1">Semestre</option>
            <option value="2">Stage</option>
            <option value="3">Travail</option>
        </select>
        <select id="cd-dropdown3" class="">
            <option value="-1" selected>Ecole</option>
            <option value="1">Nantes</option>
            <option value="2">Lille</option>
            <option value="3">Lyon</option>
        </select>
        <select id="cd-dropdown4" class="last">
            <option value="-1" selected>Période</option>
            <option value="1">Moins de 3 ans</option>
            <option value="2">Moins de 2 ans</option>
            <option value="3">Aujourd'hui</option>
            <option value="4">A venir</option>
        </select>
    </div>
    <div id="list-map" style="display:none">
        <ul id="ul-map" class="list-unstyled">
            
        </ul>
    </div>
</div>
<input type="text" id="data_input" value='{}' style="display:none">
<script type="text/javascript">
            
$( function() {

    $( '#cd-dropdown' ).dropdown( {
        gutter : 5,
        stack : false,
        slidingIn : 100
    } );

    $( '#cd-dropdown2' ).dropdown( {
        gutter : 5,
        stack : false,
        slidingIn : 100
    } );

    $( '#cd-dropdown3' ).dropdown( {
        gutter : 5,
        stack : false,
        slidingIn : 100
    } );

    $( '#cd-dropdown4' ).dropdown( {
        gutter : 5,
        stack : false,
        slidingIn : 100
    } );

});

</script>