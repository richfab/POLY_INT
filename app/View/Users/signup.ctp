<h2><?= __("S'inscrire");?></h2>
    
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
    echo $this->Form->input('firstname', array('placeholder' => __('Prénom'),'label' => __('Prénom')));
    echo $this->Form->input('lastname', array('placeholder' => __('Nom'),'label' => __('Nom')));
    echo $this->Form->input('email', array('placeholder' => __('Email étudiant'),
        'afterInput'=>"<span class='help-block'>".__("Je n'ai pas d'email étudiant. ").$this->Html->link(__("Faire une demande d'inscription"), array('action' => 'signup_request'))."</span>"
        ));
    echo $this->Form->input('password', array('placeholder' => __('Mot de passe'),'label' => __('Mot de passe')));
    echo $this->Form->input('password_confirm', array('placeholder' => __('Confirmation'),'label' => __('Confirmation'),"type"=>"password"));
    echo $this->Form->input('School.id');
    echo $this->Form->input('school_id', array('placeholder' => 'Polytech','label' => 'Polytech'));
    echo $this->Form->input('Department.id');
    echo $this->Form->input('department_id', array('placeholder' => __('Spécialité'),'label' => __('Spécialité')));?>
        
    <div class="form-group">
        <?php echo $this->Form->submit(__("S'inscrire"), array(
                'div' => 'col col-md-9 col-md-offset-3',
                'class' => 'btn btn-blue'
        )); ?>
    </div>
<?php echo $this->Form->end(); ?>