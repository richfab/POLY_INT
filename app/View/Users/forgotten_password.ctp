<h1>Mot de passe oublié</h1>
<?php echo $this->Form->create('User');
    echo $this->Form->input('email',array('label'=>'Email utilisé pour l\'inscription'));
    echo $this->Form->end('Envoyer');
    echo $this->Html->link('Retour à la connexion', array('controller'=>'users','action'=>'login'));
 ?>