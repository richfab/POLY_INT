<div class="row">
    
    <div class="col-sm-3">
        <div class="dropdown">
            <button class="btn btn-default dropdown-toggle filter-button" type="button" id="dropdownMenu1" data-toggle="dropdown">
                <span class="dropdown-title pull-left">Spécialité</span>
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                <li role="presentation" value="0"><a role="menuitem" href="#">Toutes</a></li>
        <?php foreach ($departments as $key => $department):?>
                <li role="presentation" value="<?= $key; ?>"><a role="menuitem" href="#"><?= $department; ?></a></li>
        <?php endforeach;?>
            </ul>
        </div>
    </div>
        
    <div class="col-sm-3">
        <div class="dropdown">
            <button class="btn btn-default dropdown-toggle filter-button" type="button" id="dropdownMenu1" data-toggle="dropdown">
                <span class="dropdown-title pull-left">Motif</span>
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                <li role="presentation" value="0"><a role="menuitem" href="#">Tous</a></li>
        <?php foreach ($motives as $key => $motive):?>
                <li role="presentation" value="<?= $key; ?>"><a role="menuitem" href="#"><?= $motive; ?></a></li>
        <?php endforeach;?>
            </ul>
        </div>
    </div>
        
    <div class="col-sm-3">
        <div class="dropdown">
            <button class="btn btn-default dropdown-toggle filter-button" type="button" id="dropdownMenu1" data-toggle="dropdown">
                <span class="dropdown-title pull-left">École</span>
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                <li role="presentation" value="0"><a role="menuitem" href="#">Toutes</a></li>
        <?php foreach ($schools as $key => $school):?>
                <li role="presentation" value="<?= $key; ?>"><a role="menuitem" href="#"><?= $school; ?></a></li>
        <?php endforeach;?>
            </ul>
        </div>
    </div>
        
    <div class="col-sm-3">
        <div class="dropdown">
            <button class="btn btn-default dropdown-toggle filter-button" type="button" id="dropdownMenu1" data-toggle="dropdown">
                <span class="dropdown-title pull-left">Période</span>
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                <li role="presentation" value="0" date-min="" date-max=""><a role="menuitem" href="#">Toutes</a></li>
                <li role="presentation" value="1" date-min="<?= date_sub(date_create('now'),date_interval_create_from_date_string('3 years'))->format('Y-m-d');?>" date-max="<?= date('Y-m-d');?>" ><a role="menuitem" href="#">Depuis <?= date('Y')-3;?></a></li>
                <li role="presentation" value="2" date-min="<?= date_sub(date_create('now'),date_interval_create_from_date_string('1 year'))->format('Y-m-d');?>" date-max="<?= date('Y-m-d');?>" ><a role="menuitem" href="#">Depuis <?= date('Y')-1;?></a></li>
                <li role="presentation" value="3" date-min="<?= date('Y-m-d');?>" date-max="<?= date('Y-m-d');?>"><a role="menuitem" href="#">Maintenant</a></li>
                <li role="presentation" value="4" date-min="<?= date('Y-m-d');?>" date-max="" ><a role="menuitem" href="#">A venir</a></li>
            </ul>
        </div>
    </div>
        
</div>
<!--utile pour le nombre de resultats a afficher-->
<input type="hidden" name="offset" id="offset" value="0">
    
<script type="text/javascript">
    
    $( function() {
        
        $( "li > a" ).each(function(index) {
            $(this).on("click", function(){
                var value = $(this).text();
                $(this).parents(".dropdown").find(".dropdown-title").text(value);
            });
        });
        
    });
    
</script>