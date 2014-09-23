<?php
    
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?> 
<div class="fbalbum_import_button" experience_id="<?php echo $experience['Experience']['id']; ?>">
    <?php if($experience['Experience']['fbalbum_id'] !== NULL) : ?>
        <button class="btn btn-xs btn-default" onclick="if(confirm('<?php echo __('Es-tu sûr de vouloir supprimer cet album ?');?>'))delete_album(<?php echo $experience['Experience']['id']; ?>)"><?= __("Supprimer l'album");?></button>
        <button class="btn btn-xs btn-blue" onclick="update_fb_album(<?php echo $experience['Experience']['id']; ?>, '<?php echo $experience['Experience']['fbalbum_id']; ?>')"><?= __("Mettre à jour l'album");?></button>
    <?php else :?>
        <button class="btn btn-xs btn-blue pull-right" onclick="import_fb_album(<?php echo $experience['Experience']['id']; ?>)"><?= __("Importer un album Facebook");?></button>
    <?php endif; ?>
</div>