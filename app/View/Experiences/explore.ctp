<div id="world-map" style="width: 100%; height: 600px; postion:relative; bottom:20px; top:100px;"></div>
                
<div id="controller">
    <div class="control-map hidden-xs" id="filter-map">
        <?php echo $this->element('filter-selects'); ?>
    </div>
    <input type="hidden" name="date_min" id="date_min">
    <input type="hidden" name="date_max" id="date_max">
</div>
<div id="list-map" class="experience-list" style="display:none">

</div>
<?= $this->Html->image('loader.GIF', array('alt' => 'loader','id'=>'loader-map','height'=>'40px'));?>
<script type="text/javascript">
            
$( function() {

    $( '.select-dropdown' ).each(function() {
        $(this).dropdown( {
            gutter : 5,
            stack : false,
            slidingIn : 100
        });
    });
    
    //au click sur une option on lance la recherche
    $( '.cd-dropdown li' ).click(function() {
        get_map();
    });
    
    $( '.cd-dropdown > span' ).click(function(){
        $('.experience-list').slideUp(300);
//        $(this).html('<span>coucou</span>');
    });

});

</script>