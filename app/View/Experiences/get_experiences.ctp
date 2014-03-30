<?php if(AuthComponent::user('id')) : ?>
    <?php foreach ($experiences as $experience): ?>
        <p><?= $this->Html->link($experience['User']['firstname'].' '.$experience['User']['lastname'].' | '.$experience['Motive']['name'], array('controller'=>'users', 'action' => 'profile', $experience['User']['id'])); ?></p>
        <p>-----------------------------------</p>
    <?php endforeach;?>
    <?php if(empty($experiences)): ?>
        <p>Sois le premier à <?= $this->Html->link("poster une expérience", array('controller'=>'experiences', 'action' => 'info')); ?> dans ce pays</p>
    <?php endif; ?>
<?php else: ?>
        <p><?= $this->Html->link("Connecte-toi", array('controller'=>'users', 'action' => 'login')); ?> ou <?= $this->Html->link("inscris-toi", array('controller'=>'users', 'action' => 'signup')); ?> pour consulter les expériences</p>
<?php endif; ?>