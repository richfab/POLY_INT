<?php echo $this->Session->flash('auth'); ?>
<h2>Se connecter</h2>
<?php echo $this->Form->create('User'); ?>
    <?php
        echo $this->Form->input('email',array('label'=>'Email'));
        echo $this->Form->input('password',array('label'=>'Mot de passe'));
    ?>
<?php echo $this->Form->end(__('Valider')); ?>
<?= $this->Html->link('Oublié ?', array('action' => 'forgotten_password')); ?>
<?= $this->Html->link("S'inscire", array('action' => 'signup')); ?>