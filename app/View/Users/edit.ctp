<h1>Modifier mes informations</h1>
<?php
    echo $this->Form->create('User');
        echo $this->Form->input('email',array('label'=>'Email'));
        echo $this->Form->input('firstname',array('label'=>"Prénom"));
        echo $this->Form->input('lastname',array('label'=>"Nom"));
        echo $this->Form->input('School.id');
        echo $this->Form->input('school_id',array('label'=>"Polytech"));
        echo $this->Form->input('Department.id');
        echo $this->Form->input('department_id',array('label'=>"Département"));
    echo $this->Form->end('Enregistrer');
?>