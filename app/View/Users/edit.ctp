<h2><?= __('Mes informations');?></h2>

<?php 
    echo $this->Form->create('User', array(
        'inputDefaults' => array(
                'div' => 'form-group',
                'label' => array(
                        'class' => 'col col-sm-3 control-label'
                ),
                'wrapInput' => 'col col-sm-6',
                'class' => 'form-control'
        ),
        'class' => 'well form-horizontal',
        'type' => 'file'
    ));
    echo $this->Form->input('firstname', array('placeholder' => __('Prénom'),'label' => __('Prénom').' *'));
    echo $this->Form->input('lastname', array('placeholder' => __('Nom'),'label' => __('Nom').' *'));
    echo $this->Form->input('School.id');
    echo $this->Form->input('school_id', array('placeholder' => 'Polytech','label' => 'Polytech *'));
    echo $this->Form->input('Department.id');
    echo $this->Form->input('department_id', array('placeholder' => __('Spécialité'),'label' => __('Spécialité').' *'));
    echo $this->Form->input('email', array('placeholder' => __('Email'), 'label' => __('Email').' *'));
    echo $this->Form->input('email_is_hidden', array(
		'wrapInput' => 'col col-sm-9 col-sm-offset-3',
		'label' => array('class' => null,
                    'text' => __('Masquer mon email dans mon profil')),
		'class' => false
	));
    echo $this->Form->input('linkedin', array('placeholder' => 'http://fr.linkedin.com/in/pseudo', 'label' => 'LinkedIn'));?>
        
    <div class="form-group">
        <div class="col col-md-9 col-md-offset-3">
            <?= $this->Html->link(__("Retour"), array('controller'=>'users', 'action' => 'profile'),
                    array('class' => 'btn btn-orange'
            )); ?>
            <?php echo $this->Form->button(__('Enregistrer'), array(
                    'class' => 'btn btn-blue'
            ));?>
        </div>
    </div>  
    
<?php echo $this->Form->end(); ?>
<p>
    <?= $this->Html->link(__("Changer mot de passe"), array('controller'=>'users', 'action' => 'change_password'),
                        array('class' => 'btn btn-default'
                )); ?>
</p>
<p>
    <?= $this->Form->postLink(__('Supprimer mon compte'),
        array('controller'=>'users', 'action' => 'delete'),
        array("class" => "btn btn-default btn-xs"),
            __('Es-tu sûr de vouloir supprimer ton compte ? Tes expériences et tes informations seront définitivement supprimées.'));?>
</p>