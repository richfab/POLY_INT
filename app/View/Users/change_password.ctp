<h2>Changer mot de passe</h2>
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
    echo $this->Form->input('old_password',array('label'=>'Ancien mot de passe','placeholder' => 'Ancien mot de passe', 'type' => 'password'));
    echo $this->Form->input('password',array('label'=>'Nouveau mot de passe','placeholder' => 'Nouveau mot de passe'));
    echo $this->Form->input('password_confirm',array('label'=>'Confirmer','placeholder' => 'Confirmer', 'type' => 'password'));?>

<div class="form-group">
            <?php echo $this->Form->submit('Enregistrer', array(
                    'div' => 'col col-md-9 col-md-offset-3',
                    'class' => 'btn btn-orange'
            )); ?>
</div>
	<?php echo $this->Html->link('Retour au profil', array('controller'=>'users','action'=>'profile'));
        echo $this->Form->end();
?>