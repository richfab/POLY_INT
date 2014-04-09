<h2>Modifier mes informations</h2>

<?php 
    echo $this->Form->create('User', array(
        'inputDefaults' => array(
                'div' => 'form-group',
                'label' => array(
                        'class' => 'col col-md-3 control-label'
                ),
                'wrapInput' => 'col col-md-9',
                'class' => 'form-control'
        ),
        'class' => 'well form-horizontal'
    ));
    echo $this->Form->input('email', array('placeholder' => 'Email'));
    echo $this->Form->input('firstname', array('placeholder' => 'Prénom','label' => 'Prénom'));
    echo $this->Form->input('lastname', array('placeholder' => 'Nom','label' => 'Nom'));
    echo $this->Form->input('School.id');
    echo $this->Form->input('school_id', array('placeholder' => 'Polytech','label' => 'Polytech'));
    echo $this->Form->input('Department.id');
    echo $this->Form->input('department_id', array('placeholder' => 'Spécialité','label' => 'Spécialité'));?>
        
    <div class="form-group">
        <?php echo $this->Form->submit("Enregistrer", array(
                'div' => 'col col-md-9 col-md-offset-3',
                'class' => 'btn btn-blue'
        )); ?>
    </div>

    <p><?= $this->Form->postLink('Supprimer mon compte',
        array('controller'=>'users', 'action' => 'delete'),
        array('confirm' => 'Es-tu sûr de vouloir supprimer ton compte ? Tes expériences et tes informations seront définitivement supprimées.'));
    ?></p>
    <?= $this->Html->link("Retour à mon profil", array('controller'=>'users', 'action' => 'profile')); ?>
    
<?php echo $this->Form->end(); ?>