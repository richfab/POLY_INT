<h1>Notez votre expérience</h1>
<?php echo $this->Form->create('Experience'); ?>
<?php
    echo $this->Form->input('note',array('label'=>'Note','type'=>'number','class'=>'rating','data-min' => 1, 'data-max' => 5));
    echo $this->Form->input('comment',array('label'=>'Commentaire','type'=>'textarea'));
?>
<?php echo $this->Form->end(__('Suivant'));