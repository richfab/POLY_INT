<h2>S'inscrire</h2>
    
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
        'class' => 'well form-horizontal'
    ));
    echo $this->Form->input('email', array('placeholder' => 'Votre adresse étudiant'));
    echo $this->Form->input('password', array('placeholder' => 'Mot de passe','label' => 'Mot de passe'));
    echo $this->Form->input('password_confirm', array('placeholder' => 'Confirmation','label' => 'Confirmation',"type"=>"password"));
    echo $this->Form->input('firstname', array('placeholder' => 'Prénom','label' => 'Prénom'));
    echo $this->Form->input('lastname', array('placeholder' => 'Nom','label' => 'Nom'));
    echo $this->Form->input('School.id');
    echo $this->Form->input('school_id', array('placeholder' => 'Polytech','label' => 'Polytech'));
    echo $this->Form->input('Department.id');
    echo $this->Form->input('department_id', array('placeholder' => 'Spécialité','label' => 'Spécialité'));?>
        
    <div class="form-group">
        <?php echo $this->Form->submit("S'inscrire", array(
                'div' => 'col col-md-9 col-md-offset-3',
                'class' => 'btn btn-blue'
        )); ?>
    </div>
<?php echo $this->Form->end(); ?>