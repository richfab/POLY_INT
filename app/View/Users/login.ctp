<?= $this->Session->flash('auth'); ?>
<h2>Se connecter</h2>
    <?php echo $this->Form->create('User', array(
        'inputDefaults' => array(
                'div' => 'form-group',
                'label' => array(
                        'class' => 'col col-md-3 control-label'
                ),
                'wrapInput' => 'col col-md-9',
                'class' => 'form-control'
        ),
        'class' => 'well form-horizontal'
    )); ?>
        <?php echo $this->Form->input('email', array(
                'placeholder' => 'Email'
        )); ?>
        <?php echo $this->Form->input('password', array(
                'placeholder' => 'Mot de passe',
                'label' => 'Mot de passe'
        )); ?>
        <div class="form-group">
            <?php echo $this->Form->submit('Se connecter', array(
                    'div' => 'col col-md-9 col-md-offset-3',
                    'class' => 'btn btn-default'
            )); ?>
        </div>
        
<?= $this->Html->link('Oublié ?', array('action' => 'forgotten_password')); ?>
<?= $this->Html->link("S'inscire", array('action' => 'signup')); ?>
<?php echo $this->Form->end(); ?>