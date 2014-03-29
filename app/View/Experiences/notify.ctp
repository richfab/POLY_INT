<h1>Retrouvez-vous à l'étranger !</h1>
<?php echo $this->Form->create('Experience'); ?>
<?php
    echo $this->Form->input('notify',array('label'=>"Envoyez-moi un email si quelqu'un est à ... en même temps que moi"));
?>
<?php echo $this->Form->end(__('Terminer'));