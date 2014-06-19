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
                        Previous
                    </button>
                    <button type="button" class="btn btn-primary next">
                        Next
                        <i class="glyphicon glyphicon-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="links">
    <?php foreach ($photos as $photo): ?>
    <a href="img/heights-photos/<?php echo $photo['Height']['url']; ?>" author="<?php echo $photo['User']['firstname']; ?> <?php echo $photo['User']['lastname']; ?>" title="<?php echo $photo['Height']['city']; ?>, <?php echo $photo['Height']['country']; ?> - <?php echo $photo['Height']['place']; ?> 
       <?php if($photo['Height']['height'] != NULL):?>
       (<?php echo $photo['Height']['height']; ?> m)
       <?php endif;?>
       " data-gallery>
        <img onmouseover="changeWebsite(<?php echo $photo['User']['school_id'];?>)" style="background-color: #<?php echo $school_colors[$photo['User']['school_id']];?>" src="img/heights-photos/S_<?php echo $photo['Height']['url']; ?>" alt="">
    </a>
    <?php endforeach; ?>
    <?php if(empty($photos)): ?>
        <p>Aucune photo</p>
    <?php endif; ?>
</div>