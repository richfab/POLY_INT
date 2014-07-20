<?php
    
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
    
<!-- The Bootstrap Image Gallery lightbox, should be a child element of the document body -->
<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">
    <!-- The container for the modal slides -->
    <div class="slides"></div>
    <!-- Controls for the borderless lightbox -->
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
    <!-- The modal dialog, which will be used to wrap the lightbox content -->
    <div class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body next"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left prev">
                        <i class="glyphicon glyphicon-chevron-left"></i>
                        Précédent
                    </button>
                    <button type="button" class="btn btn-primary next">
                        Suivant
                        <i class="glyphicon glyphicon-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
    
<div id="links">
    <?php foreach ($photos as $photo): ?>
    <div class="photo_el_wrap">
        <?php if($photo['Experience']['user_id'] == AuthComponent::user('id')) : ?>
            <button type="button" class="close" onclick="delete_photo($(this).parents('.photo_gallery'),<?php echo $photo['Photo']['id']; ?>)">&times;</button>
        <?php endif;?>
        <a href="<?php echo $photo['Photo']['image']; ?>" title="<?php echo $photo['Photo']['caption']; ?>" data-gallery="gallery#<?php echo $photo['Photo']['experience_id']; ?>">
            <div class="photo_el" style="background-image: url(<?php echo $photo['Photo']['picture']; ?>)"></div>
        </a>
    </div>
    <?php endforeach; ?>
    
    <?php foreach ($hidden_photos as $hidden_photo): ?>
        <a href="<?php echo $hidden_photo['Photo']['image']; ?>" title="<?php echo $hidden_photo['Photo']['caption']; ?>" data-gallery="gallery#<?php echo $hidden_photo['Photo']['experience_id']; ?>"></a>
    <?php endforeach; ?>
    
    <?php if(empty($photos)): ?>
    <p>Aucune photo</p>
    <?php endif; ?>
</div>

<p style="text-align: center">
    <?php if(count($hidden_photos) > 0) : ?>
        <a style="cursor: pointer" onclick="get_gallery($(this).parents('.photo_gallery'),'<?php echo $this->Html->url(array('controller' => 'photos', 'action' => 'get_photo_gallery'),true); ?>');$(this).remove();">plus</a>
    <?php elseif(count($photos) > 8) : ?>
        <a style="cursor: pointer" onclick="get_gallery($(this).parents('.photo_gallery'),'<?php echo $this->Html->url(array('controller' => 'photos', 'action' => 'get_photo_gallery'),true); ?>',8);$(this).remove();">moins</a>
    <?php endif; ?>
</p>