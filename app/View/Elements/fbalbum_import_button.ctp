<?php
    
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
     
if($experience['Experience']['user_id'] == AuthComponent::user('id')) : ?>
    <div class="fbalbum_import_button" experience_id="<?php echo $experience['Experience']['id']; ?>">
    <?php if($experience['Experience']['fbalbum_id'] !== NULL) : ?>
        <button class="btn btn-xs btn-default" onclick="if(confirm('Es-tu sûr de vouloir supprimer cet album ?'))delete_album(<?php echo $experience['Experience']['id']; ?>)">Supprimer l'album</button>
        <button class="btn btn-xs btn-blue" onclick="update_fb_album(<?php echo $experience['Experience']['id']; ?>, '<?php echo $experience['Experience']['fbalbum_id']; ?>')">Mettre à jour l'album</button>
    <?php else :?>
        <button class="btn btn-xs btn-blue pull-right" onclick="import_fb_album(<?php echo $experience['Experience']['id']; ?>)">Importer un album Facebook</button>
    <?php endif; ?>
    </div>
<?php endif; ?>