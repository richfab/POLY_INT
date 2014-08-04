<?php

/* 
 * Filter select element
 */

?>
<div class="row">
    <div class="col-sm-3 dropdown-wrap">
        <div class="dropdown">
            <button class="btn btn-default dropdown-toggle filter-button" type="button" data-toggle="dropdown">
                <span class="dropdown-title pull-left" option-title="<?= __('Spécialité');?>"><?= __('Spécialité');?></span>
                <span class="glyphicon glyphicon-chevron-down small dropdown-chevron"></span>
            </button>
            <ul id="department_id" class="dropdown-menu" role="menu" value="0">
                <li role="presentation" value="0" class="dropdown-li"><a role="menuitem" href="#"><?= __('Toutes');?></a></li>
        <?php foreach ($departments as $key => $department):?>
                <li role="presentation" value="<?= $key; ?>" class="dropdown-li"><a role="menuitem" href="#"><?= $department; ?></a></li>
        <?php endforeach;?>
            </ul>
        </div>
    </div>
        
    <div class="col-sm-3 dropdown-wrap">
        <div class="dropdown">
            <button class="btn btn-default dropdown-toggle filter-button" type="button" data-toggle="dropdown">
                <span class="dropdown-title pull-left" option-title="<?= __('Motif');?>"><?= __('Motif');?></span>
                <span class="glyphicon glyphicon-chevron-down small dropdown-chevron"></span>
            </button>
            <ul id="motive_id" class="dropdown-menu" role="menu" value="0">
                <li role="presentation" value="0" class="dropdown-li"><a role="menuitem" href="#"><?= __('Tous');?></a></li>
        <?php foreach ($motives as $key => $motive):?>
                <li role="presentation" value="<?= $key; ?>" class="dropdown-li"><a role="menuitem" href="#"><?= __($motive); ?></a></li>
        <?php endforeach;?>
            </ul>
        </div>
    </div>
        
    <div class="col-sm-3 dropdown-wrap">
        <div class="dropdown">
            <button class="btn btn-default dropdown-toggle filter-button" type="button" data-toggle="dropdown">
                <span class="dropdown-title pull-left" option-title="<?= __('École');?>"><?= __('École');?></span>
                <span class="glyphicon glyphicon-chevron-down small dropdown-chevron"></span>
            </button>
            <ul id="school_id" class="dropdown-menu" role="menu" value="0">
                <li role="presentation" value="0" class="dropdown-li"><a role="menuitem" href="#"><?= __('Toutes');?></a></li>
        <?php foreach ($schools as $key => $school):?>
                <li role="presentation" value="<?= $key; ?>" class="dropdown-li"><a role="menuitem" href="#"><?= $school; ?></a></li>
        <?php endforeach;?>
            </ul>
        </div>
    </div>
        
    <div class="col-sm-3 dropdown-wrap">
        <div class="dropdown">
            <button class="btn btn-default dropdown-toggle filter-button" type="button" data-toggle="dropdown">
                <span class="dropdown-title pull-left" option-title="<?= __('Période');?>"><?= __('Période');?></span>
                <span class="glyphicon glyphicon-chevron-down small dropdown-chevron"></span>
            </button>
            <ul id="period_id" class="dropdown-menu pull-right" role="menu" date-min="" date-max="">
                <li role="presentation" value="0" date-min="" date-max="" class="dropdown-li"><a role="menuitem" href="#"><?= __('Toutes');?></a></li>
                <li role="presentation" value="1" date-min="<?= date_sub(date_create('now'),date_interval_create_from_date_string('3 years'))->format('Y-m-d');?>" date-max="<?= date('Y-m-d');?>" class="dropdown-li"><a role="menuitem" href="#"><?= __('Depuis');?> <?= date('Y')-3;?></a></li>
                <li role="presentation" value="2" date-min="<?= date_sub(date_create('now'),date_interval_create_from_date_string('1 year'))->format('Y-m-d');?>" date-max="<?= date('Y-m-d');?>" class="dropdown-li"><a role="menuitem" href="#"><?= __('Depuis');?> <?= date('Y')-1;?></a></li>
                <li role="presentation" value="3" date-min="<?= date('Y-m-d');?>" date-max="<?= date('Y-m-d');?>" class="dropdown-li"><a role="menuitem" href="#"><?= __('Maintenant');?></a></li>
                <li role="presentation" value="4" date-min="<?= date('Y-m-d');?>" date-max="" class="dropdown-li"><a role="menuitem" href="#"><?= __('A venir');?></a></li>
            </ul>
        </div>
    </div>
</div>
    
<script type="text/javascript">
    
    $( function() {
        
        $(".dropdown-li").each(function(index) {
            $(this).on("click", function(){
                //pour les selects school department et motive
                var value = $(this).val();
                $(this).parent().attr('value',value);
                
                //pour le titre du dropdown
                var dropdown_title = $(this).parents(".dropdown").find(".dropdown-title");
                if(value !== 0){
                    var title = $(this).children('a').text();
                    dropdown_title.parent().addClass('selected');
                }
                else{
                    var title = dropdown_title.attr('option-title');
                    dropdown_title.parent().removeClass('selected');
                }
                dropdown_title.text(title);
                
                //pour le select period
                var date_min = $(this).attr('date-min');
                var date_max = $(this).attr('date-max');
                $(this).parent().attr('date-min',date_min);
                $(this).parent().attr('date-max',date_max);
            });
        });
        
        $('.dropdown').on('show.bs.dropdown', function () {
            var dropdown_chevron = $(this).find('.dropdown-chevron');
            dropdown_chevron.removeClass('glyphicon-chevron-down');
            dropdown_chevron.addClass('glyphicon-chevron-up');
        });
        
        $('.dropdown').on('hide.bs.dropdown', function () {
            var dropdown_chevron = $(this).find('.dropdown-chevron');
            dropdown_chevron.removeClass('glyphicon-chevron-up');
            dropdown_chevron.addClass('glyphicon-chevron-down');
        });
        
    });
    
</script>