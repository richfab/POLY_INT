<div id="world-map" style="width: 100%; height: 600px; postion:relative; bottom:20px; top:100px;"></div>

<div id="controller">
    <div id="filter-map">
        <?php echo $this->element('filter_selects'); ?>
    </div>
    <input type="hidden" name="date_min" id="date_min">
    <input type="hidden" name="date_max" id="date_max">
</div>

<div id="numberOfExperiences" class="well hidden-xs">
    <div><span id="numberOfExperiencesSpan">0</span> expériences 
        <div id="addExperienceFromMap">
            <?= $this->Html->link('<span class="glyphicon glyphicon-plus"></span>', array('action' => 'info'),
                array('escape' => false)); ?>
        </div>
    </div>
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

        init_map();
        update_selects_from_filter(createFilterFromCookies());
        get_map();

        //au click sur une option on lance la recherche
        $(".dropdown-li").click(function() {
            get_map();
        });

        //au click d'une option on referme la liste
        $(".dropdown").click(function(){
            $('#list-map').slideUp(100);
        });
    });

</script>