<h2><?= __('Mot de passe oublié');?></h2>
<?php echo $this->Form->create('User', array(
        'inputDefaults' => array(
                'div' => 'form-group',
                'label' => array(
                        'class' => 'col col-sm-3 control-label'
                ),
                'wrapInput' => 'col col-sm-6',
                'class' => 'form-control'
        ),
        'class' => 'well form-horizontal'
    ));
	echo $this->Form->input('email',array('label'=>__("Email utilisé pour l'inscription")));?>

<div class="form-group">
            <?php echo $this->Form->submit(__('Envoyer'), array(
                    'div' => 'col col-md-9 col-md-offset-3',
                    'class' => 'btn btn-orange'
            )); ?>
</div>
	<?php echo $this->Html->link(__('Retour'), array('controller'=>'users','action'=>'login'));
        echo $this->Form->end();
?>