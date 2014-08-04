<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>

<h2 class="page-title"><?= __("<b>Tempête</b>");?> <?= __("d'actualités");?>.</h2>

<div class="activity-feed">
	<div class="news">
		<div id="activities">
		        
		</div>
	</div>
</div>

<script type="text/javascript">
    
    $( function() {
        
        //on lance la récupération des actualités avec un offset de 1
        get_activities();
        
    });
    
</script>