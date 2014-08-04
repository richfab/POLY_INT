<?php
    $today = date_create(date('Y-m-d'));
    $date_start_d = date_create($date_start);
    $date_end_d = date_create($date_end);
?>
<!--now-->
    <?php if ($today >= $date_start_d && $today <= $date_end_d) : ?>
        <?= __('En ce moment');?>
<!--past-->
    <?php elseif($date_end_d < $today && $date_start_d < $today) : ?>
<time class="timeago" datetime="<?php echo $date_end; ?>"><?php echo $date_end; ?></time>
<!--future-->
    <?php else : ?>
<time class="timeago" datetime="<?php echo $date_start; ?>"><?php echo $date_start; ?></time>
    <?php endif;?>
        
<script type="text/javascript">
        
    $( function() {
            
        //pour les timeago
        $.timeago.settings.allowFuture = true;
        $("time.timeago").timeago();
              
            
    });
        
</script>