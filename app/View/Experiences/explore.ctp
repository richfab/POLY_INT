<div id="world-map" style="width: 100%; height: 600px; postion:relative; bottom:20px; top:100px;"></div>
    
<div id="controller">
    <div class="control-map" id="filter-map">
        <?php echo $this->element('filter_selects'); ?>
    </div>
    <input type="hidden" name="date_min" id="date_min">
    <input type="hidden" name="date_max" id="date_max">
</div>

<!-- experience lists and modals -->
<div id="list-map" style="display:none">
    <ul class="list-unstyled experience-list"></ul>
</div>
    
<div id="list-map-modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Expériences</h4>
            </div>
            <div class="modal-body" style="padding: 0px;">
                <ul class="list-unstyled experience-list"></ul>
            </div>
        </div>
    </div>
</div>
<!-- /experience lists and modals -->

<script type="text/javascript">
    
    $( function() {
        //definition du style des filtres
        if ($(window).width() < 768) {
            // do for small screens
            var slidingIn = 1;
        }
        else{
            slidingIn = 50;
        }
        
        $( '.select-dropdown' ).each(function() {
            $(this).dropdown( {
                gutter : 5,
                stack : false,
                slidingIn : slidingIn
            });
        });
        
        //au click sur une option on lance la recherche
        $( '.cd-dropdown li' ).click(function() {
            get_map();
        });
        
        //au click d'une option on referme la liste
        $( '.cd-dropdown > span' ).click(function(){
            $('#list-map').slideUp(100);
        });
        
    });
    
</script>