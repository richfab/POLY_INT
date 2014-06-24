<h2>Mes informations</h2>

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
    echo $this->Form->input('firstname', array('placeholder' => 'Prénom','label' => 'Prénom *'));
    echo $this->Form->input('lastname', array('placeholder' => 'Nom','label' => 'Nom *'));
    echo $this->Form->input('School.id');
    echo $this->Form->input('school_id', array('placeholder' => 'Polytech','label' => 'Polytech *'));
    echo $this->Form->input('Department.id');
    echo $this->Form->input('department_id', array('placeholder' => 'Spécialité','label' => 'Spécialité *'));
    echo $this->Form->input('email', array('placeholder' => 'Email', 'label' => 'Email *'));
    echo $this->Form->input('email_is_hidden', array(
		'wrapInput' => 'col col-sm-9 col-sm-offset-3',
		'label' => array('class' => null,
                    'text' => 'Masquer mon email dans mon profil'),
		'class' => false
	));
    echo $this->Form->input('linkedin', array('placeholder' => 'http://fr.linkedin.com/in/pseudo', 'label' => 'LinkedIn'));?>
        
    <div class="form-group">
        <div class="col col-md-9 col-md-offset-3">
            <?= $this->Html->link("Retour", array('controller'=>'users', 'action' => 'profile'),
                    array('class' => 'btn btn-orange'
            )); ?>
            <?php echo $this->Form->button('Enregistrer', array(
                    'class' => 'btn btn-blue'
            ));?>
        </div>
    </div>  
    
<?php echo $this->Form->end(); ?>
<p>
    <?= $this->Html->link("Changer mot de passe", array('controller'=>'users', 'action' => 'change_password'),
                        array('class' => 'btn btn-default'
                )); ?>
</p>
<p>
    <?= $this->Form->postLink('Supprimer mon compte',
        array('controller'=>'users', 'action' => 'delete'),
        array("class" => "btn btn-default btn-xs"),
        'Es-tu sûr de vouloir supprimer ton compte ? Tes expériences et tes informations seront définitivement supprimées.');?>
</p>