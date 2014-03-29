<h3>S'inscrire</h3>
<?= $this->Form->create('User'); ?>
    <?php
        echo $this->Form->input('email',array('label'=>'Email'));
        echo $this->Form->input('password',array('label'=>'Mot de passe'));
        echo $this->Form->input('password_confirm',array('label'=>'Confirmer mot de passe','type' => 'password'));
        echo $this->Form->input('firstname',array('label'=>"Prénom"));
        echo $this->Form->input('lastname',array('label'=>"Nom"));
        echo $this->Form->input('School.id');
        echo $this->Form->input('school_id',array('label'=>"Polytech"));
        echo $this->Form->input('Department.id');
        echo $this->Form->input('department_id',array('label'=>"Département"));
    ?>
<?= $this->Form->end(__('Valider')); ?>